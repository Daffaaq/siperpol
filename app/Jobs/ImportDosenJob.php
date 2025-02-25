<?php

namespace App\Jobs;

use App\Events\AlertAndMessagesEvent;
use App\Imports\DosenImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportDosenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $jurusan_id;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @param int $jurusan_id
     */
    public function __construct($filePath, $jurusan_id)
    {
        $this->filePath = $filePath;
        $this->jurusan_id = $jurusan_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Mengimpor file excel dari path
            $file = storage_path('app/' . $this->filePath);  // Dapatkan path file lengkap
            Excel::import(new DosenImport($this->jurusan_id), $file);

            // Data berhasil diimport, trigger event jika diperlukan
            event(new AlertAndMessagesEvent(
                'Data Dosen Ditambahkan',  // Title
                'Dosen baru telah berhasil ditambahkan.',  // Message
                auth()->user()->id  // User ID
            ));
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah saat mengimpor
            Log::error('Error on importing dosen: ' . $e->getMessage());
        }
    }
}
