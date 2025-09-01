<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Muestra todos los tutores
    public function index()
    {
        $tutores = Tutor::whereNull('deleted_at')->orderBy('paterno')->paginate(10);
        return view('tutores.index', compact('tutores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // Muestra el formulario para crear un nuevo tutor
    public function create()
    {
        return view('tutores.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    // Almacena un nuevo tutor en la base de datos
    public function store(Request $request)
    {
        //
        $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required|unique:tutores,ci',
            'email' => 'required|email|unique:tutores,email',
            'telefono' => 'required|string|max:20',
            'grado' => 'required|string|max:50',
        ]);

        // Crear usuario asociado
        $primerNombre = explode(' ', trim($request->nombre))[0];
        $user = User::create([
            'name' => $request->nombre . ' ' . $request->paterno,
            'email' => $request->email,
            'password' => Hash::make($primerNombre . '123'), // contraseña por defecto
        ]);

        // Asignar rol "tutor"
        $user->assignRole('tutor');

        // Crear tutor asociado

        Tutor::create([
            'nombre' => $request->nombre,
            'paterno' => $request->paterno,
            'materno' => $request->materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'ci' => $request->ci,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'grado' => $request->grado,
            'user_id' => $user->id,
        ]);

        return redirect()->route('tutores.index')->with('success', 'Tutor registrado correctamente.');
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
    // Muestra el formulario para editar un tutor existente
    public function edit(Tutor $tutor)
    {
        return view('tutores.edit', compact('tutor'));
    }

    /**
     * Update the specified resource in storage.
     */
    // Actualiza un tutor existente
    public function update(Request $request, Tutor $tutor)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required|unique:tutores,ci,' . $tutor->id,
            'email' => 'required|email|unique:tutores,email,' . $tutor->id,
            'telefono' => 'required|string|max:20',
            'grado' => 'required|string|max:50',
            //'user_id' => 'required|exists:users,id',
        ]);

        $tutor->update($request->all());

        return redirect()->route('tutores.index')->with('success', 'Tutor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Elimina lógicamente un tutor existente
    public function destroy(Tutor $tutor)
    {
        $tutor->delete();
        return redirect()->route('tutores.index')->with('success', 'Tutor eliminado correctamente.');
    }
    // Restaurar un tutor eliminado
    public function restore($id)
    {
        $tutor = Tutor::withTrashed()->findOrFail($id);
        $tutor->restore();

        // Buscar el usuario relacionado
        $user = User::find($tutor->user_id);

        if ($user) {
            // Obtener el primer nombre para generar la contraseña
            $primerNombre = explode(' ', trim($tutor->nombre))[0];
            $nuevaPassword = $primerNombre . '123';

            // Regenerar contraseña
            $user->password = \Hash::make($nuevaPassword);

            // Verificar el correo automáticamente si es necesario
            $user->email_verified_at = now();

            $user->save();
        }

        return redirect()->route('tutores.index')
            ->with('success', 'Tutor restaurado correctamente');
    }

    // Muestra los tutores eliminados
    public function eliminados()
    {
        // Obtener solo los tutores eliminados (soft deleted)
        $tutores = Tutor::onlyTrashed()
            ->orderBy('paterno')
            ->get();

        return view('tutores.eliminados', compact('tutores'));
    }
}
