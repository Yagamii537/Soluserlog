<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClienteController extends Controller
{
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
        return view('admin.clientes.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'razonSocial' => 'required',
            'ruc' => 'required',
            'direccion' => 'required',
            'localidad' => 'required',
            'pisos' => 'required',
            'CodigoPostal' => 'required',
            'ampliado' => 'required',
            'celular' => 'required',
            'telefono' => 'required',
            'correo' => 'required',
            'contribuyente' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        ]);
        Cliente::create($request->all());
        return redirect()->route('admin.clientes.index');
    }


    public function show(Cliente $cliente)
    {
        //
    }


    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }


    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'razonSocial' => 'required',
            'ruc' => 'required',
            'direccion' => 'required',
            'localidad' => 'required',
            'pisos' => 'required',
            'CodigoPostal' => 'required',
            'ampliado' => 'required',
            'celular' => 'required',
            'telefono' => 'required',
            'correo' => 'required',
            'contribuyente' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        ]);
        $cliente->update($request->all());

        return redirect()->route('admin.clientes.edit', $cliente)->with('info', 'Los datos se actualizaron correctamente');
    }

    public function destroy(Cliente $cliente)
    {

        //return $cliente;
        $cliente->delete();
        return redirect()->route('admin.clientes.index')->with('info', 'El cliente se elimino correctamente');
    }
}
