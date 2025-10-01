<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\TribunalController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReporteController;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\RevisionAvanceController;
use App\Http\Controllers\BackupController;





Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Postulantes
|--------------------------------------------------------------------------
*/
Route::get('postulantes/eliminados', [PostulanteController::class, 'eliminados'])->name('postulantes.eliminados');
Route::post('postulantes/{id}/restore', [PostulanteController::class, 'restore'])->name('postulantes.restore');

// Importar desde API (buscar y mostrar completar.blade.php)
Route::post('postulantes/importar-api', [PostulanteController::class, 'importarDesdeApi'])
    ->name('postulantes.importar.api');

// Guardar después de completar manualmente
Route::post('postulantes/store-desde-api', [PostulanteController::class, 'storeDesdeApi'])
    ->name('postulantes.storeDesdeApi');

// CRUD normal
Route::resource('postulantes', PostulanteController::class)->parameters(['postulantes' => 'postulante']);
/*
|--------------------------------------------------------------------------
| Tutores
|--------------------------------------------------------------------------
*/
Route::get('tutores/eliminados', [TutorController::class, 'eliminados'])->name('tutores.eliminados');
Route::post('tutores/{id}/restore', [TutorController::class, 'restore'])->name('tutores.restore');
// Importar desde API (buscar y mostrar completar.blade.php)
Route::post('tutores/importar-api', [TutorController::class, 'importarDesdeApi'])->name('tutores.importar.api');
Route::post('tutores/store-desde-api', [TutorController::class, 'storeDesdeApi'])->name('tutores.storeDesdeApi');
// CRUD normal
Route::resource('tutores', TutorController::class)->parameters(['tutores' => 'tutor']);

// Avances que puede ver un tutor
Route::get('/tutor/avances', [AvanceController::class, 'verComoTutor'])
    ->middleware(['auth', 'role:tutor'])
    ->name('tutor.avances');

/*
|--------------------------------------------------------------------------
| Tribunales
|--------------------------------------------------------------------------
*/
Route::get('tribunales/eliminados', [TribunalController::class, 'eliminados'])->name('tribunales.eliminados');
Route::post('tribunales/{id}/restore', [TribunalController::class, 'restore'])->name('tribunales.restore');
// Importar desde API (buscar y mostrar completar.blade.php)
Route::post('tribunales/importar-api', [TribunalController::class, 'importarDesdeApi'])->name('tribunales.importar.api');
Route::post('tribunales/store-desde-api', [TribunalController::class, 'storeDesdeApi'])->name('tribunales.storeDesdeApi');
// CRUD normal
Route::resource('tribunales', TribunalController::class)->parameters(['tribunales' => 'tribunal']);

/*
|--------------------------------------------------------------------------
| Proyectos
|--------------------------------------------------------------------------
*/
Route::get('proyectos/eliminados', [ProyectoController::class, 'eliminados'])->name('proyectos.eliminados');
Route::post('proyectos/{id}/restore', [ProyectoController::class, 'restore'])->name('proyectos.restore');
Route::get('/proyecto/{id}/informe', [ProyectoController::class, 'generarInforme'])
    ->name('proyecto.generar_informe')
    ->middleware('auth');
Route::resource('proyectos', ProyectoController::class)->parameters(['proyectos' => 'proyecto']);

/*
|--------------------------------------------------------------------------
| Avances (Postulante) y Revisiones (Tutor/Tribunales)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    //  Avances del postulante
    Route::get('/mis-avances', [AvanceController::class, 'misAvances'])->name('avances.mis');
    Route::get('/mis-avances/create', [AvanceController::class, 'create'])->name('avances.create');
    Route::post('/mis-avances', [AvanceController::class, 'store'])->name('avances.store');
    Route::delete('/avances/{id}', [AvanceController::class, 'destroy'])->name('avances.destroy');

    //  Revisión de avances por tutor/tribunal
    Route::get('/avances-revision', [RevisionAvanceController::class, 'index'])->name('avances.revision');
    Route::get('/revisiones/{avance}', [RevisionAvanceController::class, 'show'])->name('revisiones.show');
    Route::put('/revisiones/{revision}', [RevisionAvanceController::class, 'update'])->name('revisiones.update');

    //  Informes
    Route::get('/informes', [RevisionAvanceController::class, 'informes'])->name('informes.index');
    Route::get('/informes/{id}/descargar', [RevisionAvanceController::class, 'descargarInforme'])->name('informes.descargar');

    /*
    |--------------------------------------------------------------------------
    | Reportes (solo superadmin y director)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:superadmin|director')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/proyectos', [ReporteController::class, 'proyectos'])->name('reportes.proyectos');
        Route::get('/reportes/proyectos/{id}', [ReporteController::class, 'detalle'])->name('reportes.proyectos.detalle');
        Route::get('/reportes/exportar/excel', [ReporteController::class, 'exportExcel'])->name('reportes.export.excel');
        Route::get('/reportes/general-pdf', [ReporteController::class, 'exportGeneralPDF'])->name('reportes.general.pdf');
    });
});

/*
|--------------------------------------------------------------------------
| Administración (solo superadmin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // Gestión de permisos de roles
    Route::get('/roles/{role}/permisos', [RoleController::class, 'editPermisos'])->name('roles.permisos.edit');
    Route::put('/roles/{role}/permisos', [RoleController::class, 'updatePermisos'])->name('roles.permisos.update');

    // Backups
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/run', [BackupController::class, 'run'])->name('backups.run');
    Route::get('/backups/download/{file}', [BackupController::class, 'download'])->name('backups.download');
});