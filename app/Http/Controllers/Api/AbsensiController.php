<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Absensi::with('user:id,nama,nip,departemen,divisi')
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at');

        if ($user->role !== 'hr') {
            $query->where('user_id', $user->id);
        }

        // HR: filter by date or user
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->has('user_id') && $user->role === 'hr') {
            $query->where('user_id', $request->user_id);
        }

        return response()->json($query->get());
    }

    public function show(Request $request, $id)
    {
        $user    = $request->user();
        $absensi = Absensi::with('user:id,nama,nip,departemen,divisi')->findOrFail($id);

        if ($user->role !== 'hr' && $absensi->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json($absensi);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'          => 'required|date',
            'jam_masuk'        => 'required|date_format:H:i',
            'status_kehadiran' => 'required|in:Hadir,Terlambat,Alpha,Pulang Cepat',
        ]);

        // Check duplicate for same user & date
        $existing = Absensi::where('user_id', $request->user()->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Absensi untuk tanggal ini sudah ada.'], 422);
        }

        $absensi = Absensi::create([
            'user_id'          => $request->user()->id,
            'tanggal'          => $request->tanggal,
            'jam_masuk'        => $request->jam_masuk,
            'jam_pulang'       => $request->jam_pulang ?? null,
            'status_kehadiran' => $request->status_kehadiran,
        ]);

        return response()->json($absensi, 201);
    }

    public function update(Request $request, $id)
    {
        $user    = $request->user();
        $absensi = Absensi::findOrFail($id);

        if ($user->role !== 'hr' && $absensi->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'jam_pulang'       => 'sometimes|nullable|date_format:H:i',
            'status_kehadiran' => 'sometimes|in:Hadir,Terlambat,Alpha,Pulang Cepat',
        ]);

        $absensi->update($request->only(['jam_pulang', 'status_kehadiran']));

        return response()->json($absensi);
    }
}
