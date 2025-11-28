<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCheckinTimezone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-checkin-timezone {--confirm : Konfirmasi sebelum memperbaiki data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki timezone data check_in yang salah dari UTC ke Asia/Makassar (WITA)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Memulai perbaikan timezone check_in...');
        
        // Ambil semua participant yang memiliki check_in tidak null
        $participants = Participant::whereNotNull('check_in')->get();
        
        if ($participants->isEmpty()) {
            $this->info('✅ Tidak ada data check_in yang perlu diperbaiki.');
            return 0;
        }
        
        $this->info("📊 Ditemukan {$participants->count()} data check_in yang akan diperbaiki.");
        
        // Tampilkan beberapa contoh data yang akan diperbaiki
        $this->table(
            ['ID', 'Nama', 'NIP', 'Check In (UTC)', 'Check In (WITA)'],
            $participants->take(5)->map(function ($participant) {
                $originalTime = $participant->check_in;
                $fixedTime = $originalTime->copy()->addHours(8); // UTC+8 untuk WITA
                
                return [
                    $participant->id,
                    $participant->nama,
                    $participant->nip,
                    $originalTime->format('Y-m-d H:i:s') . ' UTC',
                    $fixedTime->format('Y-m-d H:i:s') . ' WITA'
                ];
            })
        );
        
        if ($participants->count() > 5) {
            $remaining = $participants->count() - 5;
            $this->info("... dan {$remaining} data lainnya.");
        }
        
        // Konfirmasi jika menggunakan flag --confirm
        if ($this->option('confirm')) {
            if (!$this->confirm('⚠️  Apakah Anda yakin ingin memperbaiki semua data check_in di atas?')) {
                $this->info('❌ Perbaikan dibatalkan.');
                return 0;
            }
        }
        
        // Proses perbaikan
        $this->info('🔄 Sedang memperbaiki data...');
        $progressBar = $this->output->createProgressBar($participants->count());
        $progressBar->start();
        
        $fixedCount = 0;
        $errorCount = 0;
        
        foreach ($participants as $participant) {
            try {
                // Tambahkan 8 jam untuk konversi UTC ke WITA (Asia/Makassar)
                $fixedTime = $participant->check_in->copy()->addHours(8);
                
                // Update database menggunakan query langsung untuk menghindari issue timezone
                DB::table('participants')
                    ->where('id', $participant->id)
                    ->update(['check_in' => $fixedTime]);
                
                $fixedCount++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Gagal memperbaiki data ID {$participant->id}: {$e->getMessage()}");
                $errorCount++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        // Tampilkan hasil
        $this->newLine();
        $this->info('✅ Perbaikan selesai!');
        $this->info("📈 Data berhasil diperbaiki: {$fixedCount}");
        if ($errorCount > 0) {
            $this->error("❌ Data gagal diperbaiki: {$errorCount}");
        }
        
        // Tampilkan contoh hasil perbaikan
        if ($fixedCount > 0) {
            $this->newLine();
            $this->info('📋 Contoh hasil perbaikan:');
            $this->table(
                ['ID', 'Nama', 'NIP', 'Check In (Setelah Perbaikan)'],
                Participant::whereNotNull('check_in')
                    ->take(3)
                    ->get()
                    ->map(function ($participant) {
                        return [
                            $participant->id,
                            $participant->nama,
                            $participant->nip,
                            $participant->check_in->format('Y-m-d H:i:s') . ' WITA'
                        ];
                    })
            );
        }
        
        return $errorCount > 0 ? 1 : 0;
    }
}
