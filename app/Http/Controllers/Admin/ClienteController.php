<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
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
        // Validar los datos del formulario, incluyendo las direcciones
        $validatedData = $request->validate([
            'codigoCliente' => 'required|numeric|digits:5|unique:clientes,codigoCliente',
            'ruc' => 'required|string|max:13|unique:clientes,ruc',
            'razonSocial' => 'required|string|max:150',
            'tipoInstitucion' => 'nullable|string|max:150',
            'tipoCliente' => 'nullable|string|max:50',
            'publicoPrivado' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'required|email|max:150|unique:clientes,correo',
            'latitud' => 'nullable|string|max:150',
            'longitud' => 'nullable|string|max:150',

            // Validación para las direcciones
            'direcciones' => 'required|array', // Asegura que haya al menos una dirección
            'direcciones.*.nombre_sucursal' => 'nullable|string|max:150',
            'direcciones.*.direccion' => 'required|string|max:255',
            'direcciones.*.ciudad' => 'required|string|max:100',
            'direcciones.*.provincia' => 'nullable|string|max:150',
            'direcciones.*.zona' => 'nullable|string|max:100',
        ]);

        // Asignar la fecha actual a fechaCreacion y el estado a 1
        $validatedData['fechaCreacion'] = Carbon::now();
        $validatedData['estado'] = 1; // 1 para Activo

        // Excluir el array de direcciones antes de crear el cliente
        $clienteData = Arr::except($validatedData, ['direcciones']);

        // Crear el cliente en la base de datos sin las direcciones
        $cliente = Cliente::create($clienteData);

        // Crear las direcciones asociadas al cliente
        foreach ($validatedData['direcciones'] as $direccion) {
            $cliente->addresses()->create($direccion);
        }

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente creado exitosamente con sus direcciones.');
    }


    public function show(Cliente $cliente)
    {
        //
    }


    public function edit(Cliente $cliente)
    {
        // Cargamos las direcciones con el cliente
        $cliente->load('addresses');

        return view('admin.clientes.edit', compact('cliente'));
    }


    public function update(Request $request, Cliente $cliente)
    {
        // Validar los datos del formulario, incluyendo las direcciones
        $validatedData = $request->validate([
            'codigoCliente' => 'required|numeric|digits:5|unique:clientes,codigoCliente,' . $cliente->id,
            'ruc' => 'required|string|max:13|unique:clientes,ruc,' . $cliente->id,
            'razonSocial' => 'required|string|max:150',
            'tipoInstitucion' => 'nullable|string|max:150',
            'tipoCliente' => 'nullable|string|max:50',
            'publicoPrivado' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'required|email|max:150|unique:clientes,correo,' . $cliente->id,
            'latitud' => 'nullable|string|max:150',
            'longitud' => 'nullable|string|max:150',

            // Validación para las direcciones
            'direcciones' => 'required|array',
            'direcciones.*.nombre_sucursal' => 'nullable|string|max:150',
            'direcciones.*.direccion' => 'required|string|max:255',
            'direcciones.*.ciudad' => 'required|string|max:100',
            'direcciones.*.provincia' => 'nullable|string|max:150',
            'direcciones.*.zona' => 'nullable|string|max:100',
        ]);

        // Actualizar datos del cliente
        $cliente->update(Arr::except($validatedData, ['direcciones']));

        // Procesar direcciones
        $existingAddresses = $cliente->addresses->pluck('id')->toArray();  // IDs de direcciones actuales
        $updatedAddresses = collect($validatedData['direcciones'])->pluck('id')->filter()->toArray();  // IDs de direcciones en el formulario
        $deletedAddresses = array_diff($existingAddresses, $updatedAddresses);  // Direcciones a eliminar

        // Eliminar direcciones que ya no están en el formulario
        Address::destroy($deletedAddresses);

        // Crear o actualizar las direcciones
        foreach ($validatedData['direcciones'] as $direccion) {
            if (isset($direccion['id'])) {
                // Actualizar dirección existente
                Address::where('id', $direccion['id'])->update(Arr::except($direccion, 'id'));
            } else {
                // Crear nueva dirección
                $cliente->addresses()->create($direccion);
            }
        }

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente actualizado exitosamente con sus direcciones.');
    }


    public function destroy(Cliente $cliente)
    {

        //return $cliente;
        $cliente->delete();
        return redirect()->route('admin.clientes.index')->with('info', 'El cliente se elimino correctamente');
    }
}
