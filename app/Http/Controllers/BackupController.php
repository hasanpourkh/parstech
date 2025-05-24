<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use PDF;

class BackupController extends Controller
{
    public function index()
    {
        $tables = [
            'products'   => 'محصولات',
            'customers'  => 'مشتریان',
            'sales'      => 'فروش‌ها',
            'services'   => 'خدمات',
            'categories' => 'دسته‌بندی‌ها',
            'brands'     => 'برندها',
            'people'     => 'اشخاص',
        ];
        return view('backup.index', compact('tables'));
    }

    public function backupAll()
    {
        // گرفتن لیست جدول‌ها بدون doctrine
        $tables = array_map('current', \DB::select('SHOW TABLES'));
        $allData = [];
        foreach ($tables as $table) {
            $allData[$table] = \DB::table($table)->get();
        }
        $filename = 'backup_' . date('Ymd_His') . '.ptech';
        $path = storage_path("app/$filename");
        file_put_contents($path, json_encode($allData, JSON_UNESCAPED_UNICODE));
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function export(Request $request)
    {
        $tables = $request->input('tables', []);
        $format = $request->input('format', 'excel'); // excel, pdf, ptech

        if (empty($tables)) {
            return back()->with('error', 'هیچ بخشی انتخاب نشده است.');
        }

        $data = [];
        foreach ($tables as $table) {
            $data[$table] = DB::table($table)->get();
        }

        if ($format == 'excel') {
            $filename = 'backup_' . join('_', $tables) . '_' . date('Ymd_His') . '.xlsx';
            return Excel::download(new GenericExport($data), $filename);
        } elseif ($format == 'pdf') {
            $filename = 'backup_' . join('_', $tables) . '_' . date('Ymd_His') . '.pdf';
            $pdf = PDF::loadView('backup.pdf', compact('data'));
            return $pdf->download($filename);
        } elseif ($format == 'ptech') {
            $filename = 'backup_' . join('_', $tables) . '_' . date('Ymd_His') . '.ptech';
            Storage::disk('local')->put($filename, json_encode($data, JSON_UNESCAPED_UNICODE));
            return response()->download(storage_path("app/$filename"))->deleteFileAfterSend(true);
        }

        return back()->with('error', 'فرمت خروجی نامعتبر است.');
    }
}
