<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\TextilApiService;

// Controlador para la gestión de postulantes

class PostulanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //muestra todos los postulantes
    public function index()
    {
        //
        $postulantes = Postulante::whereNull('deleted_at')->orderBy('paterno')->paginate(10);
        return view('postulantes.index', compact('postulantes'));
    }

    //Importar postulante desde API + completar manualmente datos faltantes
    public function importarDesdeApi(Request $request, TextilApiService $apiService)
    {
        $request->validate([
            'ci' => 'required|string',
            'ru' => 'required|string|unique:postulantes,ru',
            'fecha_nacimiento' => 'required|date',
        ]);

        $data = $apiService->getPostulanteByCI($request->ci);

        if (!$data) {
            return back()->with('error', 'No se encontraron datos en el servicio externo.');
        }

        // Verificar si ya existe en BD
        $existe = Postulante::where('ci', $data['ci'])->first();
        if ($existe) {
            return back()->with('error', 'Este postulante ya está registrado en el sistema.');
        }

        // Crear usuario
        $primerNombre = explode(' ', trim($data['nombre']))[0];
        $user = User::create([
            'name' => $data['nombre'] . ' ' . $data['paterno'],
            'email' => $data['email'],
            'password' => \Hash::make($primerNombre . '123'),
        ]);
        $user->assignRole('postulante');

        // Crear postulante
        Postulante::create([
            'nombre' => $data['nombre'],
            'paterno' => $data['paterno'],
            'materno' => $data['materno'],
            'ci' => $data['ci'],
            'ru' => $request->ru,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $data['email'],
            'telefono' => $data['celular'] ?? 'Sin número',
            'user_id' => $user->id,
        ]);

        return redirect()->route('postulantes.index')->with('success', 'Postulante importado desde API correctamente.');
    }



    /**
     * Show the form for creating a new resource.
     */
    //muestra el formulario para crear un nuevo postulante
    public function create()
    {
        //
        return view('postulantes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    //guarda un nuevo postulante
    public function store(Request $request)
    {
        //
        $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required|unique:postulantes,ci',
            'ru' => 'required|unique:postulantes,ru',
            'email' => 'required|email|unique:postulantes,email',
            'telefono' => 'required|string|max:20',
        ]);

        // Crear usuario asociado
        $primerNombre = explode(' ', trim($request->nombre))[0];
        $user = User::create([
            'name' => $request->nombre . ' ' . $request->paterno,
            'email' => $request->email,
            'password' => Hash::make($primerNombre . '123'), // contraseña por defecto
        ]);

        // Asignar rol "postulante"
        $user->assignRole('postulante');

        // Crear postulante asociado

        Postulante::create([
            'nombre' => $request->nombre,
            'paterno' => $request->paterno,
            'materno' => $request->materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'ci' => $request->ci,
            'ru' => $request->ru,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'user_id' => $user->id,
        ]);

        return redirect()->route('postulantes.index')->with('success', 'Postulante registrado correctamente.');
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
    // Mostrar formulario de edición
    public function edit(Postulante $postulante)
    {
        return view('postulantes.edit', compact('postulante'));
    }

    // Actualizar un postulante
    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'paterno' => 'required|string|max:100',
            'materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required|unique:postulantes,ci,' . $postulante->id,
            'ru' => 'required|unique:postulantes,ru,' . $postulante->id,
            'email' => 'required|email|unique:postulantes,email,' . $postulante->id,
            'telefono' => 'required|string|max:20',
            //'user_id' => 'required|exists:users,id',
        ]);

        $postulante->update($request->all());

        return redirect()->route('postulantes.index')->with('success', 'Postulante actualizado correctamente.');
    }

    // Eliminación lógica (soft delete)
    public function destroy(Postulante $postulante)
    {
        $postulante->delete();
        return redirect()->route('postulantes.index')->with('success', 'Postulante eliminado correctamente.');
    }

    // Restaurar un postulante eliminado
    public function restore($id)
    {
        $postulante = Postulante::withTrashed()->findOrFail($id);
        $postulante->restore();

        // Buscar el usuario relacionado
        $user = User::find($postulante->user_id);

        if ($user) {
            // Obtener el primer nombre para generar la contraseña
            $primerNombre = explode(' ', trim($postulante->nombre))[0];
            $nuevaPassword = $primerNombre . '123';

            // Regenerar contraseña
            $user->password = \Hash::make($nuevaPassword);

            // Verificar el correo automáticamente si es necesario
            $user->email_verified_at = now();

            $user->save();
        }

        return redirect()->route('postulantes.index')
            ->with('success', 'Postulante restaurado correctamente');
    }


    public function eliminados()
    {
        // Obtener solo los postulantes eliminados (soft deleted)
        $postulantes = Postulante::onlyTrashed()
            ->orderBy('paterno')
            ->get();

        return view('postulantes.eliminados', compact('postulantes'));
    }
}
