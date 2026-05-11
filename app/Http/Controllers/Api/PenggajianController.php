<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penggajian;
use App\Models\User;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'hr') {
            $penggajians = Penggajian::with('user:id,nama,nip,departemen,divisi')
                ->orderByDesc('created_at')
                ->get();
        } else {
            $penggajians = Penggajian::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return response()->json($penggajians);
    }

    public function show(Request $request, $id)
    {
        $user       = $request->user();
        $penggajian = Penggajian::with('user:id,nama,nip,departemen,divisi')->findOrFail($id);

        if ($user->role !== 'hr' && $penggajian->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json($penggajian);
    }

    /**
     * POST /api/penggajians  (HR: create salary record)
     */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'hr') {
            return response()->json(['message' => 'Unauthorized. HR only.'], 403);
        }

        $request->validate([
            'user_id'           => 'required|exists:user,id',
            'nomor_rekening'    => 'required|string',
            'bank'              => 'required|string',
            'nominal_gaji'      => 'required|numeric|min:0',
            'status_penggajian' => 'required|in:Belum Diproses,Diproses,Sudah Dibayar',
            'periode'           => 'required|string',
        ]);

        $penggajian = Penggajian::create($request->only([
            'user_id', 'nomor_rekening', 'bank', 'nominal_gaji', 'status_penggajian', 'periode',
        ]));

        return response()->json($penggajian->load('user:id,nama,nip,departemen,divisi'), 201);
    }

    /**
     * PUT /api/penggajians/{id}  (HR only)
     */
    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'hr') {
            return response()->json(['message' => 'Unauthorized. HR only.'], 403);
        }

        $penggajian = Penggajian::findOrFail($id);

        $request->validate([
            'nomor_rekening'    => 'sometimes|string',
            'bank'              => 'sometimes|string',
            'nominal_gaji'      => 'sometimes|numeric|min:0',
            'status_penggajian' => 'sometimes|in:Belum Diproses,Diproses,Sudah Dibayar',
            'periode'           => 'sometimes|string',
        ]);

        $penggajian->update($request->only([
            'nomor_rekening', 'bank', 'nominal_gaji', 'status_penggajian', 'periode',
        ]));

        return response()->json($penggajian->load('user:id,nama,nip,departemen,divisi'));
    }
}
