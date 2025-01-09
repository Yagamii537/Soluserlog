<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GuiaController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CamionController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\AyudanteController;
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\TrackingController;
use App\Http\Controllers\Admin\ConductorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManifiestoController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\FacturacionController;
use App\Http\Controllers\Admin\DetalleBitacoraController;

Route::get('/', function () {
    return view('auth.login');
});






Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    /*Route::get('/dash', function () {
        return view('dash.index');
    })->name('dash');*/

    Route::get('dash', [DashboardController::class, 'index'])->name('dash');

    Route::resource('manifiestos', ManifiestoController::class)->names('admin.manifiestos');
    Route::get('/admin/manifiestos/{manifiesto}/pdf', [ManifiestoController::class, 'generatePdf'])->name('admin.manifiestos.pdf');




    Route::resource('camiones', CamionController::class)->names('admin.camiones');
    Route::resource('conductores', ConductorController::class)->names('admin.conductores');

    Route::resource('users', UserController::class)->names('admin.users');
    Route::resource('roles', RoleController::class)->names('admin.roles');
    Route::resource('permissions', PermissionController::class)->names('admin.permissions');
    Route::get('/admin/profile/profile', [UserController::class, 'profile'])->name('admin.profile.profile');

    Route::resource('clientes', ClienteController::class)->names('admin.clientes');
    Route::get('/admin/clientes/inactivos', [ClienteController::class, 'inactivos'])->name('admin.clientes.inactivos');

    Route::patch('/admin/clientes/{cliente}/reactivar', [ClienteController::class, 'reactivar'])->name('admin.clientes.reactivar');




    Route::resource('orders', OrderController::class)->names('admin.orders');
    // Ruta adicional para confirmar pedidos
    Route::post('/admin/orders/confirm', [OrderController::class, 'confirm'])->name('admin.orders.confirm');
    Route::get('/admin/orders/{order}/confDelete', [OrderController::class, 'confDelete'])->name('admin.orders.confDelete');
    Route::get('/admin/orders/confirmed', [OrderController::class, 'confirmed'])->name('admin.orders.confirmed');
    // Ruta para generar el PDF
    Route::get('/admin/orders/{order}/pdf', [OrderController::class, 'generatePdf'])->name('admin.orders.pdf');
    Route::get('/admin/orders/{order}/boxes', [OrderController::class, 'generateBoxesPdf'])->name('admin.orders.boxes');

    //Rutas documentos asociados al pedido
    Route::resource('documents', DocumentController::class)->names('admin.documents');
    Route::get('/admin/documents/{order}/addDocumentOrder', [DocumentController::class, 'addDocumentOrder'])->name('admin.documents.addDocumentOrder');
    Route::get('admin/documents/edit/{order}', [DocumentController::class, 'editDocumentOrder'])->name('admin.documents.editDocumentOrder');
    Route::put('admin/documents/update/{order}', [DocumentController::class, 'updateDocumentOrder'])->name('admin.documents.updateDocumentOrder');


    Route::get('/guias/select-manifiesto', [GuiaController::class, 'selectManifiesto'])->name('admin.guias.select_manifiesto');
    Route::get('/guias', [GuiaController::class, 'index'])->name('admin.guias.index');
    Route::get('/guias/{guia}/pdf', [GuiaController::class, 'generatePdf'])->name('admin.guias.pdf');
    Route::get('/guias/create/{manifiesto}', [GuiaController::class, 'create'])->name('admin.guias.create');
    Route::post('/guias/store', [GuiaController::class, 'store'])->name('admin.guias.store');
    Route::resource('ayudantes', AyudanteController::class)->names('admin.ayudantes');

    // Bitácoras
    Route::resource('bitacoras', BitacoraController::class)->names('admin.bitacoras');

    Route::get('/bitacoras/{bitacora}/seleccionar-detalles', [BitacoraController::class, 'seleccionarDetalles'])->name('admin.bitacoras.seleccionarDetalles');
    Route::get('/bitacoras/{bitacora}/detalle/{order}/editar', [DetalleBitacoraController::class, 'edit'])->name('admin.detalle_bitacoras.edit');
    Route::put('/bitacoras/{bitacora}/detalle/{order}', [DetalleBitacoraController::class, 'update'])->name('admin.detalle_bitacoras.update');


    Route::get('/admin/bitacoras/{bitacora}/pdf', [BitacoraController::class, 'generatePdf'])->name('admin.bitacoras.pdf');
    Route::get('/bitacoras/{bitacora}/mapa', [BitacoraController::class, 'showMapa'])->name('admin.bitacoras.mapa');

    // Detalle Bitácoras
    Route::resource('detalle-bitacoras', DetalleBitacoraController::class)->names('admin.detalle-bitacoras');
    Route::delete('/admin/detalle_bitacoras/{imageId}/delete', [DetalleBitacoraController::class, 'deleteImage'])->name('admin.detalle_bitacoras.deleteImage');


    Route::get('/tracking', [TrackingController::class, 'index'])->name('admin.tracking.index');
    Route::post('/tracking/search', [TrackingController::class, 'search'])->name('admin.tracking.search');
    Route::get('/admin/tracking/{orderId}/pdf', [TrackingController::class, 'downloadPDF'])->name('admin.tracking.pdf');

    Route::resource('facturacion', FacturacionController::class)->names('admin.facturacion');
});
