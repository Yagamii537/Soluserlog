<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ClienteController extends Controller
{
    protected $provincias = [
        'Azuay' => ['Cuenca', 'Gualaceo', 'Nabón'],
        'Guayas' => ['Guayaquil', 'Daule', 'Samborondón'],
        'Pichincha' => ['Quito', 'Cayambe', 'Rumiñahui']
    ];

    public function getCliente($id)
    {
        // Suponiendo que tienes un modelo Cliente
        $cliente = Cliente::find($id);

        // Retornamos los datos como un JSON para usarlos en el frontend
        return response()->json($cliente);
    }



    public function index()
    {
        $clientes = Cliente::all();
        return view('admin.clientes.index')->with('clientes', $clientes);
    }


    public function create()
    {
        // Formato clave-valor para las provincias
        $provincias = collect($this->provincias)->keys()->mapWithKeys(function ($provincia) {
            return [$provincia => $provincia];
        });

        return view('admin.clientes.create', compact('provincias'));
    }
    public function getCiudades($provincia)
    {
        // Retorna las ciudades de la provincia seleccionada
        $ciudades = $this->provincias[$provincia] ?? [];
        return response()->json($ciudades);
    }


    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'codigoCliente' => 'required|numeric|digits:5|unique:clientes,codigoCliente',
            'ruc' => 'required|string|max:13|unique:clientes,ruc',
            'razonSocial' => 'required|string|max:150',
            'tipoInstitucion' => 'nullable|string|max:150',
            'tipoCliente' => 'nullable|string|max:50',
            'publicoPrivado' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:15',
            'provincia' => 'nullable|string|max:100',
            'ciudad' => 'nullable|string|max:100',
            'zona' => 'nullable|string|max:100',
            'correo' => 'required|email|max:150|unique:clientes,correo',
            'latitud' => 'nullable|string|max:150',
            'longitud' => 'nullable|string|max:150',
        ]);

        // Asignar la fecha actual a fechaCreacion y el estado a 1
        $validatedData['fechaCreacion'] = Carbon::now();
        $validatedData['estado'] = 1; // 1 para Activo

        // Crear el cliente en la base de datos
        Cliente::create($validatedData);
        return redirect()->route('admin.clientes.index');
    }


    public function show(Cliente $cliente)
    {
        //
    }


    public function edit(Cliente $cliente)
    {
        $provincias = collect($this->provincias)->keys()->mapWithKeys(function ($provincia) {
            return [$provincia => $provincia];
        });

        // Retornar la vista de edición con los datos del cliente y las provincias
        return view('admin.clientes.edit', compact('cliente', 'provincias'));
    }


    public function update(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate([
            'codigoCliente' => 'required|numeric|digits:5',
            'ruc' => 'required|string|max:13',
            'razonSocial' => 'required|string|max:150',
            'tipoInstitucion' => 'nullable|string|max:150',
            'tipoCliente' => 'nullable|string|max:50',
            'publicoPrivado' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:15',
            'provincia' => 'nullable|string|max:100',
            'ciudad' => 'nullable|string|max:100',
            'zona' => 'nullable|string|max:100',
            'correo' => 'required|email|max:150',
            'latitud' => 'nullable|string|max:150',
            'longitud' => 'nullable|string|max:150',
        ]);
        $cliente->update($validatedData);

        return redirect()->route('admin.clientes.edit', $cliente)->with('info', 'Los datos se actualizaron correctamente');
    }

    public function destroy(Cliente $cliente)
    {

        //return $cliente;
        $cliente->delete();
        return redirect()->route('admin.clientes.index')->with('info', 'El cliente se elimino correctamente');
    }
}
