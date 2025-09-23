<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserImport;
use App\Notifications\UserImportCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProcessUserImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userImport;
    public $chunkData;
    public $chunkIndex;

    public function __construct(UserImport $userImport, array $chunkData, int $chunkIndex)
    {
        $this->userImport = $userImport;
        $this->chunkData = $chunkData;
        $this->chunkIndex = $chunkIndex;
    }

    public function handle()
    {
        $successful = 0;
        $failed = 0;

        foreach ($this->chunkData as $row) {
            try {
                // Validate row data
                $validator = Validator::make($row, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'nullable|string',
                    'address' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    $failed++;
                    continue;
                }

                // Create user
                User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'user',
                    'company_id' => $this->userImport->admin->company_id,
                    'phone' => $row['phone'] ?? null,
                    'address' => $row['address'] ?? null,
                ]);

                $successful++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        // Update the import progress
        $this->userImport->increment('processed_rows', count($this->chunkData));
        $this->userImport->increment('successful_rows', $successful);
        $this->userImport->increment('failed_rows', $failed);

        // Check if this is the last chunk
        $totalProcessed = $this->userImport->processed_rows;
        if ($totalProcessed >= $this->userImport->total_rows) {
            $this->userImport->update(['status' => 'completed']);

            // Send notification
            $this->userImport->admin->notify(new UserImportCompleted($this->userImport));
        }
    }

    public function failed(\Throwable $exception)
    {
        $this->userImport->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage()
        ]);
    }
}
