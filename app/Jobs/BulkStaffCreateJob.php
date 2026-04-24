<?php

namespace App\Jobs;

use App\Events\Jobs\BulkStaffCreated;
use App\Models\User;
use Hash;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Spatie\SimpleExcel\SimpleExcelReader;

class BulkStaffCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $filePath,
        public int $restaurantId
    ) {
    }

    public function handle(): void
    {
        $createdStaffs = [];

        SimpleExcelReader::create(storage_path("app/{$this->filePath}"))
            ->getRows()
            ->each(function ($row) use (&$createdStaffs) {

                if (User::where('email', $row['email'])->exists()) {
                    return;
                }

                $user = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password'] ?? '123456'),
                    'restaurant_id' => $this->restaurantId,
                ]);

                $user->assignRole('staff');

                $createdStaffs[] = $user->id;
            });

        if (!empty($createdStaffs)) {
            Log::info('STARTING');
            event(new BulkStaffCreated($createdStaffs));
        }
    }
}
