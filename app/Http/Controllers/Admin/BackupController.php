<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $backups = [];
        $backupPath = storage_path('app/backups');

        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                if ($file->getExtension() === 'sql') {
                    $backups[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatSize($file->getSize()),
                        'date' => Carbon::parse($file->getMTime())->format('d/m/Y H:i:s'),
                    ];
                }
            }
        }

        usort($backups, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

        return view('admin.backup.index', compact('backups'));
    }

    public function backup()
    {
        try {
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $filename = 'tualmart_ai_backup_' . now()->format('Y_m_d_His') . '.sql';
            $filepath = $backupPath . '/' . $filename;

            $db = config('database.connections.' . config('database.default'));
            $command = sprintf(
                'mysqldump -h%s -P%s -u%s %s > %s 2>&1',
                escapeshellarg($db['host']),
                escapeshellarg($db['port'] ?? '3306'),
                escapeshellarg($db['username']),
                escapeshellarg($db['database']),
                escapeshellarg($filepath)
            );

            if (!empty($db['password'])) {
                $command = sprintf(
                    'mysqldump -h%s -P%s -u%s -p%s %s > %s 2>&1',
                    escapeshellarg($db['host']),
                    escapeshellarg($db['port'] ?? '3306'),
                    escapeshellarg($db['username']),
                    escapeshellarg($db['password']),
                    escapeshellarg($db['database']),
                    escapeshellarg($filepath)
                );
            }

            exec($command, $output, $returnVar);

            if ($returnVar !== 0 && !File::exists($filepath)) {
                // Fallback: use Laravel's query to export
                $this->backupWithQuery($filepath);
            }

            ActivityLogService::log('backup', 'system', 'Backup database berhasil dibuat: ' . $filename);

            return back()->with('success', 'Backup database berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        if (!File::exists($filepath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }
        ActivityLogService::log('download', 'backup', 'Download backup: ' . $filename);
        return response()->download($filepath);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt,gz',
        ]);

        try {
            $file = $request->file('backup_file');
            $tempPath = $file->getPathname();
            $content = file_get_contents($tempPath);

            // Split SQL content and execute
            $queries = array_filter(
                array_map('trim', explode(';', $content)),
                fn($q) => !empty($q) && !str_starts_with($q, '--') && $q !== ';'
            );

            foreach ($queries as $query) {
                if (!empty(trim($query))) {
                    \DB::statement($query);
                }
            }

            ActivityLogService::log('restore', 'system', 'Restore database dari file: ' . $file->getClientOriginalName());

            return back()->with('success', 'Database berhasil di-restore.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal restore database: ' . $e->getMessage());
        }
    }

    public function destroy($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        if (File::exists($filepath)) {
            File::delete($filepath);
            ActivityLogService::log('delete', 'backup', 'Menghapus backup: ' . $filename);
        }
        return back()->with('success', 'Backup berhasil dihapus.');
    }

    private function backupWithQuery($filepath)
    {
        $tables = \DB::select('SHOW TABLES');
        $database = config('database.connections.' . config('database.default') . '.database');
        $key = 'Tables_in_' . $database;

        $sql = "-- Pangan Cerdas (Panca) Maryadat Kota Tual Database Backup\n-- Generated: " . now() . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;

            // Get create table statement
            $createTable = \DB::selectOne("SHOW CREATE TABLE `{$tableName}`");
            $createKey = 'Create Table';
            if (isset($createTable->$createKey)) {
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable->$createKey . ";\n\n";
            }

            // Get data
            $rows = \DB::select("SELECT * FROM `{$tableName}`");
            foreach ($rows as $row) {
                $values = [];
                foreach ((array)$row as $value) {
                    $values[] = is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                }
                $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n";
        }

        File::put($filepath, $sql);
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
