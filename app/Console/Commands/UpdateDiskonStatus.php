<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Diskon; // Use your actual Diskon model namespace
use DateTime;

class UpdateDiskonStatus extends Command
{
    protected $signature = 'diskon:update-status';
    protected $description = 'Update diskon status based on current date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = new DateTime();
        $diskons = Diskon::all();

        foreach ($diskons as $diskon) {
            $startDate = new DateTime($diskon->tanggal_mulai);
            $endDate = new DateTime($diskon->tanggal_akhir);
            if ($endDate < $today) {
                $diskon->status = 0; // Tidak Berlaku
            } elseif ($startDate > $today) {
                $diskon->status = 2; // Akan Berlaku
            } else {
                $diskon->status = 1; // Masih Berlaku
            }
            $diskon->save();
        }

        $this->info('Diskon statuses have been updated successfully!');
    }
}
