<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ImportJob;
use App\Models\ImportJobError;
use App\Models\SheetSource;
use App\Services\GoogleSheetsClient;
use App\Services\Imports\AttendanceSheetImporter;
use App\Services\Imports\ScoreSheetImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SheetSyncController extends Controller
{
    public function sync(
        SheetSource $sheetSource,
        GoogleSheetsClient $client,
        AttendanceSheetImporter $attendanceImporter,
        ScoreSheetImporter $scoreImporter
    ) {
        try {
            \Log::info('=== SYNC START ===', ['sheet_source_id' => $sheetSource->id, 'type' => $sheetSource->type]);

            $job = ImportJob::create([
                'sheet_source_id' => $sheetSource->id,
                'run_by' => auth()->id(),
                'status' => 'running',
            ]);

            $rows = $client->readTab($sheetSource->sheet_id, $sheetSource->tab_name);
            \Log::info('Sheet data retrieved', ['rows_count' => count($rows)]);

            if (count($rows) <= 1) {
                $job->update(['status' => 'failed', 'message' => 'Sheet has no data rows']);
                \Log::error('No data in sheet');
                return back()->with('error', 'Sheet has no data.');
            }

            if (strtolower($sheetSource->type) === 'attendance') {
                \Log::info('Processing as attendance');
                $result = $attendanceImporter->import(
                    $rows,
                    auth()->id(),
                    $sheetSource->school_class_id
                );
            } else {
                \Log::info('Processing as scores');
                $result = $scoreImporter->import(
                    $rows,
                    auth()->id(),
                    $sheetSource->school_class_id,
                    $sheetSource->subject_id,
                    $sheetSource->term_id
                );
            }

            \Log::info('Import completed', ['total' => $result['total'], 'ok' => $result['ok'], 'failed' => $result['failed']]);

            if (!empty($result['errors'])) {
                \Log::warning('Import has errors', ['error_count' => count($result['errors']), 'sample' => array_slice($result['errors'], 0, 3)]);
            }

            $job->update([
                'status' => $result['failed'] > 0 ? 'failed' : 'success',
                'total_rows' => $result['total'],
                'success_rows' => $result['ok'],
                'failed_rows' => $result['failed'],
                'message' => $result['failed'] ? 'Some rows failed, check errors.' : 'Imported successfully.',
            ]);

            foreach ($result['errors'] as [$rowNo, $studentCode, $err, $raw]) {
                ImportJobError::create([
                    'import_job_id' => $job->id,
                    'row_number' => $rowNo,
                    'student_code' => $studentCode ?: null,
                    'error' => $err,
                    'raw_row' => $raw,
                ]);
            }

            $sheetSource->update(['last_synced_at' => now()]);
            \Log::info('=== SYNC COMPLETE ===', ['success' => $result['ok'], 'failed' => $result['failed']]);

            return back()
                ->with('success', "Sync done: {$result['ok']} rows imported, {$result['failed']} failed.")
                ->with('sync_job_id', $job->id);
        } catch (\Throwable $e) {
            \Log::error('SYNC FAILED', ['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);

            if (isset($job)) {
                $job->update(['status' => 'failed', 'message' => $e->getMessage()]);
            }

            return back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }
}
