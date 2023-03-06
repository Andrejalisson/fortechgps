<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AcessoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CobrancasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnterpriseController;

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home');
    Route::get('/Sobre', 'sobre');
    Route::get('/Planos', 'planos');
    Route::get('/Assistencia', 'assistencia');
});


Route::get('/Login', [AcessoController::class, 'login'])->name('login');
Route::get('/EsqueceuSenha', [AcessoController::class, 'forgot']);
Route::post('/EsqueceuSenha', [AcessoController::class, 'forgotPost']);
Route::post('/Verifica', [AcessoController::class, 'verifica']);
Route::get('/Recuperar/{token}', [AcessoController::class, 'verificaToken']);
Route::post('/Recuperar/{token}', [AcessoController::class, 'novaSenha']);
Route::get('/Sair', [AcessoController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/DashBoard', 'financeiro');
    });
    Route::controller(EnterpriseController::class)->group(function () {
        Route::get('/ImportarEmpresas', 'importSoftruck');
        Route::get('/Empresas', 'lista');
        Route::post('/todasEmpresas', 'todasEmpresas')->name('todasEmpresas');
        Route::get('/Empresas/Adicionar', 'add');
        Route::post('/Empresas/Adicionar', 'addPost');
        Route::get('/Empresas/Perfil/{id}', 'view');
        Route::get('/Empresas/Editar/{id}', 'editar');
        Route::post('/Empresas/Editar/{id}', 'editarPost');
    });

    Route::controller(ClienteController::class)->group(function () {
        Route::get('/ImportarClientes', 'importAsaas');
        Route::get('/Clientes', 'lista');
        Route::post('/todosClientes', 'todosClientes')->name('todosClientes');
        Route::get('/Clientes/Adicionar', 'add');
        Route::post('/Clientes/Adicionar', 'addPost');
        Route::get('/Clientes/Editar/{id}', 'editar');
        Route::post('/Clientes/Editar/{id}', 'editarPost');
    });

    Route::controller(CobrancasController::class)->group(function () {
        Route::get('/Cobrancas', 'lista');
        Route::get('/Cobrancas/Notificacao/{id}', 'notificacao');
        Route::post('/todasCobrancas', 'todasCobrancas')->name('todasCobrancas');
        Route::get('/Cobrancas/Atualiza', 'atualiza');
        Route::get('/Cobrancas/Adicionar', 'add');
        Route::post('/Cobrancas/Adicionar', 'addPost');
        Route::get('/Cobrancas/Editar/{id}', 'editar');
        Route::post('/Cobrancas/Editar/{id}', 'editarPost');
        Route::get('/Cobrancas/Email', 'emails');
    });


});


Route::controller(CobrancasController::class)->group(function () {
    Route::get('/Cobrancas/Atualizar', 'atualiza');
    Route::post('/Cobrancas/Webhook', 'webhook');
    Route::get('/Cobrancas/notificacao', 'notificacao');
});





Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
