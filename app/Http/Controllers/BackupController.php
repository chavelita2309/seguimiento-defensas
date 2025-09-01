<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    // Ejecuta el backup
    public function run()
    {
        Artisan::call('backup:run');

        return back()->with('success', 'Backup generado correctamente.');
    }

    // Listar backups disponibles
    public function index()
    {
        $files = Storage::disk('local')->files('Laravel'); // Carpeta por defecto
        $backups = collect($files)->map(function ($file) {
            return [
                'path' => $file,
                'name' => basename($file),
                'size' => round(Storage::disk('local')->size($file) / 1024 / 1024, 2) . ' MB',
                'date' => date('d/m/Y H:i', Storage::disk('local')->lastModified($file)),
            ];
        })->sortByDesc('date');

        return view('backups.index', compact('backups'));
    }

    // Descargar un backup
    public function download($file)
    {
        $path = "Laravel/" . $file;

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }

        return back()->with('error', 'El archivo no existe.');
    }
}
