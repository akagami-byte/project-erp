<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'hr') {
            $cutis = Cuti::with('user:id,nama,nip,departemen,divisi')
                ->orderByDesc('created_at')
                ->get();
        } else {
            $cutis = Cuti::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return response()->json($cutis);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $cuti = Cuti::with('user:id,nama,nip,departemen,divisi')->findOrFail($id);

        if ($user->role !== 'hr' && $cuti->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json($cuti);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_cuti'      => 'required|in:Cuti Tahunan,Cuti Menikah,Cuti Sakit,Cuti Lainnya',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah_hari'     => 'required|integer|min:1',
        ]);

        $cuti = Cuti::create([
            'user_id'         => $request->user()->id,
            'jenis_cuti'      => $request->jenis_cuti,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_hari'     => $request->jumlah_hari,
            'status'          => 'Pending',
        ]);

        return response()->json($cuti, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $cuti = Cuti::findOrFail($id);

        if ($user->role !== 'hr' && $cuti->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'jenis_cuti'      => 'sometimes|in:Cuti Tahunan,Cuti Menikah,Cuti Sakit,Cuti Lainnya',
            'tanggal_mulai'   => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date',
            'jumlah_hari'     => 'sometimes|integer|min:1',
        ]);

        $cuti->update($request->only(['jenis_cuti', 'tanggal_mulai', 'tanggal_selesai', 'jumlah_hari']));

        return response()->json($cuti);
    }

    public function approve(Request $request, $id)
    {
        if ($request->user()->role !== 'hr') {
            return response()->json(['message' => 'Unauthorized. HR only.'], 403);
        }

        $request->validate([
            'status'        => 'required|in:Approved,Rejected',
            'alasan_reject' => 'required_if:status,Rejected|nullable|string',
        ]);

        $cuti = Cuti::findOrFail($id);
        $cuti->update([
            'status'        => $request->status,
            'alasan_reject' => $request->status === 'Rejected' ? $request->alasan_reject : null,
        ]);

        return response()->json($cuti);
    }
}
