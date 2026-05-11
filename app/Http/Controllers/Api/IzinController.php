<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    /**
     * GET /api/izins
     * Karyawan: list izin milik sendiri
     * HR: list semua izin
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'hr') {
            $izins = Izin::with('user:id,nama,nip,departemen,divisi')
                ->orderByDesc('created_at')
                ->get();
        } else {
            $izins = Izin::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return response()->json($izins);
    }

    /**
     * GET /api/izins/{id}
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $izin = Izin::with('user:id,nama,nip,departemen,divisi')->findOrFail($id);

        if ($user->role !== 'hr' && $izin->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json($izin);
    }

    /**
     * POST /api/izins
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_izin'   => 'required|in:Sakit,Keperluan Pribadi,Telat,Izin Beberapa Jam',
            'tanggal_izin' => 'required|date',
            'keperluan'    => 'required|string',
        ]);

        $izin = Izin::create([
            'user_id'      => $request->user()->id,
            'jenis_izin'   => $request->jenis_izin,
            'tanggal_izin' => $request->tanggal_izin,
            'keperluan'    => $request->keperluan,
            'status'       => 'Pending',
        ]);

        return response()->json($izin, 201);
    }

    /**
     * PUT /api/izins/{id}
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $izin = Izin::findOrFail($id);

        if ($user->role !== 'hr' && $izin->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'jenis_izin'   => 'sometimes|in:Sakit,Keperluan Pribadi,Telat,Izin Beberapa Jam',
            'tanggal_izin' => 'sometimes|date',
            'keperluan'    => 'sometimes|string',
        ]);

        $izin->update($request->only(['jenis_izin', 'tanggal_izin', 'keperluan']));

        return response()->json($izin);
    }

    /**
     * PATCH /api/izins/{id}/approve
     * HR only: Approve or Reject izin
     */
    public function approve(Request $request, $id)
    {
        if ($request->user()->role !== 'hr') {
            return response()->json(['message' => 'Unauthorized. HR only.'], 403);
        }

        $request->validate([
            'status'        => 'required|in:Approved,Rejected',
            'alasan_reject' => 'required_if:status,Rejected|nullable|string',
        ]);

        $izin = Izin::findOrFail($id);
        $izin->update([
            'status'        => $request->status,
            'alasan_reject' => $request->status === 'Rejected' ? $request->alasan_reject : null,
        ]);

        return response()->json($izin);
    }
}
