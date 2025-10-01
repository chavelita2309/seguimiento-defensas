<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tribunal;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\TextilApiService;

class TribunalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Muestra todos los tribunales
    public function index()
    {
        //
        $tribunales = Tribunal::orderBy('paterno')->paginate(10);

        return view('tribunales.index', compact('tribunales'));
    }

    public function importarDesdeApi(Request $request, TextilApiService $apiService)
    {
        $request->validate([
            'ci' => 'required|string',
        ]);

        $data = $apiService->getPersonaByCI($request->ci);


        if (!$data || empty($data['data'])) {
            return back()->with('error', 'No se encontraron datos en el servicio externo.');
        }

        $tribunalApi = $data['data'][0];

        // Verificar si ya existe en BD
        $existe = Tribunal::where('ci', $tribunalApi['ci'])->first();
        if ($existe) {
            return back()->with('error', 'Este tribunal ya está registrado en el sistema.');
        }

        // Enviar datos a la vista completar.blade.php
        return view('tribunales.completar', ['data' => $tribunalApi]);
    }
    // guardar el tribunal con datos completados
    public function storeDesdeApi(Request $request)
    {
        $request->validate([
            'ci' => 'required|unique:tutores,ci',
            'fecha_nacimiento' => 'required|date',
            'nombre' => 'required|string',
            'paterno' => 'nullable|string',
            'materno' => 'nullable|string',
            'email' => 'required|email|unique:tutores,email',
            'telefono' => 'required|string|max:20',
            'grado' => 'required|string|max:50',
        ]);

        // Buscar si el usuario ya existe por email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Si no existe, lo creamos
            $primerNombre = explode(' ', trim($request->nombre))[0];
            $user = User::create([
                'name' => trim($request->nombre . ' ' . ($request->paterno ?? '')),
                'email' => $request->email,
                'password' => \Hash::make($primerNombre . '123'),
            ]);
        }

        // Asignar rol tribunal si aún no lo tiene
        if (!$user->hasRole('tribunal')) {
            $user->assignRole('tribunal');
        }

        // Crear tribunal evitando duplicados
        Tribunal::firstOrCreate(
            ['ci' => $request->ci], // clave única
            [
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'email' => $request->email,
                'telefono' => $request->telefono ?? 'Sin número',
                'grado' => $request->grado,
                'user_id' => $user->id,
            ]
        );

        return redirect()->route('tribunales.index')
            ->with('success', 'Tribunal importado y guardado correctamente.');
    }

    /**
     * Show the form for creating a new resource.
     */
    // Muestra el formulario para crear un nuevo tribunal
    public function create()
    {
        //
        return view('tribunales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // Almacena un nuevo tribunal en la base de datos
    public function store(Request $request)
    {
        //
        $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'nullable|string|max:100',
            'materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required|unique:tribunales,ci',
            'email' => 'required|email|unique:tribunales,email',
            'telefono' => 'required|string|max:20',
            'grado' => 'required|string|max:50',
        ]);

        // Buscar si el usuario ya existe por email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Si no existe, lo creamos
            $primerNombre = explode(' ', trim($request->nombre))[0];
            $user = User::create([
                'name' => trim($request->nombre . ' ' . ($request->paterno ?? '')),
                'email' => $request->email,
                'password' => \Hash::make($primerNombre . '123'),
            ]);
        }

        // Asignar rol tribunal si aún no lo tiene
        if (!$user->hasRole('tribunal')) {
            $user->assignRole('tribunal');
        }

        // Crear tribunal evitando duplicados
        Tribunal::firstOrCreate(
            ['ci' => $request->ci], // clave única
            [
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'email' => $request->email,
                'telefono' => $request->telefono ?? 'Sin número',
                'grado' => $request->grado,
                'user_id' => $user->id,
            ]
        );

        return redirect()->route('tribunales.index')
            ->with('success', 'Tribunal importado y guardado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tribunal $tribunal)
    {
        return view('tribunales.edit', compact('tribunal'));
    }

    /**
     * Update the specified resource in storage.
     */
    // Actualiza un tribunal existente
    public function update(Request $request, Tribunal $tribunal)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required|unique:tribunales,ci,' . $tribunal->id,
            'email' => 'required|email|unique:tribunales,email,' . $tribunal->id,
            'telefono' => 'required|string|max:20',
            'grado' => 'required|string|max:50',
        ]);

        $tribunal->update($request->all());

        return redirect()->route('tribunales.index')->with('success', 'Tribunal actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Elimina lógicamente un tribunal existente
    public function destroy(Tribunal $tribunal)
    {
        $tribunal->delete();
        return redirect()->route('tribunales.index')->with('success', 'Tribunal eliminado correctamente.');
    }

    // Restaurar un tribunal eliminado
    public function restore($id)
    {
        $tribunal = Tribunal::withTrashed()->findOrFail($id);
        $tribunal->restore();

        // Buscar el usuario relacionado
        $user = User::find($tribunal->user_id);

        if ($user) {
            // Obtener el primer nombre para generar la contraseña
            $primerNombre = explode(' ', trim($tribunal->nombre))[0];
            $nuevaPassword = $primerNombre . '123';

            // Regenerar contraseña
            $user->password = \Hash::make($nuevaPassword);

            // Verificar el correo automáticamente si es necesario
            $user->email_verified_at = now();

            $user->save();
        }

        return redirect()->route('tribunales.index')
            ->with('success', 'Tribunal restaurado correctamente');
    }

    // Muestra los tribunales eliminados
    public function eliminados()
    {
        // Obtener solo los tribunales eliminados (soft deleted)
        $tribunales = Tribunal::onlyTrashed()
            ->orderBy('paterno')
            ->get();

        return view('tribunales.eliminados', compact('tribunales'));
    }
}
