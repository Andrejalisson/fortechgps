<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AcessoController;

Route::get('/', [SiteController::class, 'home']);
Route::get('/Sobre', [SiteController::class, 'sobre']);
Route::get('/Planos', [SiteController::class, 'planos']);
Route::get('/Assistencia', [SiteController::class, 'assistencia']);

Route::get('/Login', [AcessoController::class, 'login']);
Route::get('/EsqueceuSenha', [AcessoController::class, 'forgot']);
Route::post('/Verifica', [AcessoController::class, 'verifica']);
Route::get('/Recuperar/{token}', [AcessoController::class, 'verificaToken']);
Route::post('/Recuperar', [AcessoController::class, 'novasenha']);
Route::get('/Sair', [AcessoController::class, 'logout']);

