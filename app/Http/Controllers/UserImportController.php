<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,super_admin']);
    }

    public function showImportForm()
    {
        return view('admin.users.import');
    }

    public function import(Request $request)
    {
        // Manual validation
        if (!$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded.'
            ], 400);
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed.'
            ], 400);
        }

        // Increase execution time for large files
        set_time_limit(3000); // 5 minutes

        try {
            $data = $this->readCSV($file);

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The file is empty or contains only headers.'
                ], 400);
            }

            $totalRows = count($data);

            // Create import record
            $userImport = UserImport::create([
                'admin_id' => Auth::id(),
                'filename' => $file->getClientOriginalName(),
                'total_rows' => $totalRows,
                'status' => 'processing'
            ]);

            \Log::info('Starting to process rows', ['total_rows' => $totalRows]);

            // Process rows in batches to avoid memory issues
            $successful = 0;
            $failed = 0;
            $batchSize = 50; // Process 50 rows at a time

            for ($i = 0; $i < count($data); $i += $batchSize) {
                $batch = array_slice($data, $i, $batchSize);

                foreach ($batch as $row) {
                    try {
                        // Validate required fields
                        if (empty($row['name']) || empty($row['email'])) {
                            $failed++;
                            continue;
                        }

                        // Check if email already exists
                        if (User::where('email', $row['email'])->exists()) {
                            $failed++;
                            continue;
                        }

                        // Create user
                        User::create([
                            'name' => $row['name'],
                            'email' => $row['email'],
                            'password' => Hash::make('password123'),
                            'role' => 'user',
                            'company_id' => Auth::user()->company_id,
                            'phone' => $row['phone'] ?? null,
                            'address' => $row['address'] ?? null,
                        ]);

                        $successful++;
                    } catch (\Exception $e) {
                        \Log::error('Error creating user: ' . $e->getMessage());
                        $failed++;
                    }
                }

                // Update progress after each batch
                $userImport->update([
                    'processed_rows' => min($i + $batchSize, $totalRows),
                    'successful_rows' => $successful,
                    'failed_rows' => $failed
                ]);

                \Log::info('Processed batch', ['processed' => $i + $batchSize, 'successful' => $successful, 'failed' => $failed]);
            }

            // Mark import as completed
            $userImport->update([
                'status' => 'completed',
                'processed_rows' => $totalRows,
                'successful_rows' => $successful,
                'failed_rows' => $failed
            ]);

            \Log::info('Import completed successfully');

            return response()->json([
                'success' => true,
                'message' => 'User import completed successfully!',
                'data' => [
                    'import_id' => $userImport->id,
                    'total_rows' => $totalRows,
                    'successful' => $successful,
                    'failed' => $failed
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());

            // Mark import as failed
            if (isset($userImport)) {
                $userImport->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error importing file: ' . $e->getMessage()
            ], 500);
        }
    }

    private function readCSV($file)
    {
        $data = [];

        try {
            $handle = fopen($file->getPathname(), 'r');

            if (!$handle) {
                throw new \Exception('Could not open file');
            }

            // Read and skip header row
            $header = fgetcsv($handle);
            \Log::info('CSV header:', $header ?: ['empty']);

            $rowCount = 0;
            while (($row = fgetcsv($handle)) !== FALSE) {
                $rowCount++;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                $data[] = [
                    'name' => $row[0] ?? '',
                    'email' => $row[1] ?? '',
                    'phone' => $row[2] ?? '',
                    'address' => $row[3] ?? '',
                ];

                // Prevent memory issues with very large files
                if ($rowCount > 1000) { // Limit to 1000 rows for testing
                    break;
                }
            }

            fclose($handle);
            \Log::info('CSV read completed', ['rows_found' => $rowCount, 'rows_processed' => count($data)]);

        } catch (\Exception $e) {
            \Log::error('CSV read error: ' . $e->getMessage());
            throw $e;
        }

        return $data;
    }

    public function importStatus(UserImport $import)
    {
        if ($import->admin_id !== Auth::id() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'import' => $import
        ]);
    }

    public function importStatusJson(UserImport $import)
    {
        if ($import->admin_id !== Auth::id() && !Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $progressPercent = $import->total_rows > 0 ?
            round(($import->processed_rows / $import->total_rows) * 100) : 0;

        return response()->json([
            'id' => $import->id,
            'status' => $import->status,
            'total_rows' => $import->total_rows,
            'processed_rows' => $import->processed_rows,
            'successful_rows' => $import->successful_rows,
            'failed_rows' => $import->failed_rows,
            'error_message' => $import->error_message,
            'progress_percent' => $progressPercent,
            'created_at' => $import->created_at->toISOString(),
            'updated_at' => $import->updated_at->toISOString()
        ]);
    }

    public function importHistory()
    {
        $imports = UserImport::where('admin_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('admin.users.import-history', compact('imports'));
    }

    public function downloadTemplate()
    {
        $template = "Name,Email,Phone,Address\nJohn Doe,john@example.com,+1234567890,123 Main St\nJane Smith,jane@example.com,+1234567891,456 Oak Ave";

        return response($template)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="user-import-template.csv"');
    }
}
