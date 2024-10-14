<?php

use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\UserController;


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});



Route::resource('users', UserController::class)->only(['index', 'edit', 'update'])->names('admin.users');
Route::resource('clientes', ClienteController::class)->names('admin.clientes');
Route::get('/admin/clientes/{id}', [ClienteController::class, 'getCliente'])->name('admin.clientes.get');




Route::resource('orders', OrderController::class)->names('admin.orders');
// Ruta adicional para confirmar pedidos
Route::post('/admin/orders/confirm', [OrderController::class, 'confirm'])->name('admin.orders.confirm');
Route::get('/admin/orders/{order}/confDelete', [OrderController::class, 'confDelete'])->name('admin.orders.confDelete');
Route::get('/admin/orders/confirmed', [OrderController::class, 'confirmed'])->name('admin.orders.confirmed');
// Ruta para generar el PDF
Route::get('/admin/orders/{order}/pdf', [OrderController::class, 'generatePdf'])->name('admin.orders.pdf');

//Rutas documentos asociados al pedido
Route::resource('documents', DocumentController::class)->names('admin.documents');
Route::get('/admin/documents/{order}/addDocumentOrder', [DocumentController::class, 'addDocumentOrder'])->name('admin.documents.addDocumentOrder');




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dash', function () {
        return view('dash.index');
    })->name('dash');
});
