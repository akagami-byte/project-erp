<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Izin;
use App\Models\Cuti;
use App\Models\Absensi;
use App\Models\Penggajian;
use Carbon\Carbon;

class HRNexa_DataSeeder extends Seeder
{
    public function run()
    {
        $budi  = User::where('email', 'budi@nexaerp.com')->first();
        $siti  = User::where('email', 'siti@nexaerp.com')->first();
        $ahmad = User::where('email', 'ahmad@nexaerp.com')->first();
        $dewi  = User::where('email', 'dewi@nexaerp.com')->first();

        if (!$budi || !$siti) {
            $this->command->warn('Run HRNexa_UserSeeder first!');
            return;
        }

        // ---- IZIN ----
        $izins = [
            ['user_id' => $budi->id,  'jenis_izin' => 'Sakit',             'tanggal_izin' => '2026-05-12', 'keperluan' => 'Demam tinggi dan flu.',          'status' => 'Approved'],
            ['user_id' => $budi->id,  'jenis_izin' => 'Keperluan Pribadi', 'tanggal_izin' => '2026-04-20', 'keperluan' => 'Urusan keluarga mendesak.',       'status' => 'Pending'],
            ['user_id' => $budi->id,  'jenis_izin' => 'Telat',             'tanggal_izin' => '2026-04-08', 'keperluan' => 'Macet parah di jalan tol.',      'status' => 'Rejected', 'alasan_reject' => 'Tidak ada bukti yang cukup valid.'],
            ['user_id' => $siti->id,  'jenis_izin' => 'Izin Beberapa Jam', 'tanggal_izin' => '2026-05-10', 'keperluan' => 'Ke dokter gigi jam 10-11.',      'status' => 'Approved'],
            ['user_id' => $ahmad->id, 'jenis_izin' => 'Sakit',             'tanggal_izin' => '2026-05-08', 'keperluan' => 'Sakit perut mendadak.',           'status' => 'Pending'],
            ['user_id' => $dewi->id,  'jenis_izin' => 'Keperluan Pribadi', 'tanggal_izin' => '2026-05-07', 'keperluan' => 'Ada acara pernikahan keluarga.', 'status' => 'Pending'],
        ];

        foreach ($izins as $izin) {
            Izin::firstOrCreate(
                ['user_id' => $izin['user_id'], 'tanggal_izin' => $izin['tanggal_izin']],
                $izin
            );
        }

        // ---- CUTI ----
        $cutis = [
            ['user_id' => $budi->id,  'jenis_cuti' => 'Cuti Tahunan', 'tanggal_mulai' => '2026-06-01', 'tanggal_selesai' => '2026-06-05', 'jumlah_hari' => 5, 'status' => 'Pending'],
            ['user_id' => $siti->id,  'jenis_cuti' => 'Cuti Menikah', 'tanggal_mulai' => '2026-05-20', 'tanggal_selesai' => '2026-05-23', 'jumlah_hari' => 4, 'status' => 'Approved'],
            ['user_id' => $ahmad->id, 'jenis_cuti' => 'Cuti Sakit',   'tanggal_mulai' => '2026-05-05', 'tanggal_selesai' => '2026-05-06', 'jumlah_hari' => 2, 'status' => 'Approved'],
            ['user_id' => $dewi->id,  'jenis_cuti' => 'Cuti Lainnya', 'tanggal_mulai' => '2026-05-15', 'tanggal_selesai' => '2026-05-16', 'jumlah_hari' => 2, 'status' => 'Pending'],
        ];

        foreach ($cutis as $cuti) {
            Cuti::firstOrCreate(
                ['user_id' => $cuti['user_id'], 'tanggal_mulai' => $cuti['tanggal_mulai']],
                $cuti
            );
        }

        // ---- ABSENSI (last 5 days) ----
        $users   = [$budi, $siti, $ahmad, $dewi];
        $statuses = ['Hadir', 'Hadir', 'Hadir', 'Terlambat'];

        foreach ($users as $idx => $u) {
            for ($i = 4; $i >= 0; $i--) {
                $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
                // Skip weekends
                $day = Carbon::parse($tanggal)->dayOfWeek;
                if ($day === 0 || $day === 6) continue;

                Absensi::firstOrCreate(
                    ['user_id' => $u->id, 'tanggal' => $tanggal],
                    [
                        'user_id'          => $u->id,
                        'tanggal'          => $tanggal,
                        'jam_masuk'        => $idx === 3 ? '09:15' : '08:00',
                        'jam_pulang'       => $i === 0 ? null : '17:00',
                        'status_kehadiran' => $statuses[$idx],
                    ]
                );
            }
        }

        // ---- PENGGAJIAN (Mei 2026) ----
        $penggajians = [
            ['user_id' => $budi->id,  'nomor_rekening' => '1234567890', 'bank' => 'BCA',     'nominal_gaji' => 8500000,  'status_penggajian' => 'Diproses',      'periode' => 'Mei 2026'],
            ['user_id' => $siti->id,  'nomor_rekening' => '0987654321', 'bank' => 'BNI',     'nominal_gaji' => 7200000,  'status_penggajian' => 'Sudah Dibayar', 'periode' => 'Mei 2026'],
            ['user_id' => $ahmad->id, 'nomor_rekening' => '1122334455', 'bank' => 'Mandiri', 'nominal_gaji' => 9100000,  'status_penggajian' => 'Sudah Dibayar', 'periode' => 'Mei 2026'],
            ['user_id' => $dewi->id,  'nomor_rekening' => '5544332211', 'bank' => 'BRI',     'nominal_gaji' => 6500000,  'status_penggajian' => 'Belum Diproses', 'periode' => 'Mei 2026'],
        ];

        foreach ($penggajians as $p) {
            Penggajian::firstOrCreate(
                ['user_id' => $p['user_id'], 'periode' => $p['periode']],
                $p
            );
        }

        $this->command->info('HRNexa data seeded successfully!');
    }
}
