<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cobrancas;
use CodePhix\Asaas\Cobranca;
use App\Models\Cliente;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller{
    public function financeiro(){
        $title = "Dashboard Financeiro";
        $cobrancas = Cobrancas::whereMonth('dueDate', date('m'))->get();
        $total = $cobrancas->count();
        $previsto = 0;
        $confirmado = 0;
        $pendente = 0;
        $qntConfirmado = 0;
        foreach ($cobrancas as $cobranca) {
            $previsto = $previsto + $cobranca->netValue;
            switch ($cobranca->status) {
                case 'PENDING':
                    $pendente = $pendente + $cobranca->netValue;
                    break;
                case 'RECEIVED':
                    $confirmado = $confirmado + $cobranca->netValue;
                    $qntConfirmado = $qntConfirmado+1;
                    break;
                case 'OVERDUE':
                    $pendente = $pendente + $cobranca->netValue;
                    break;
                case 'RECEIVED_IN_CASH':
                    $confirmado = $confirmado + $cobranca->netValue;
                    $qntConfirmado = $qntConfirmado+1;
                    break;
                case 'AWAITING_RISK_ANALYSIS':
                    $confirmado = $confirmado + $cobranca->netValue;
                    $qntConfirmado = $qntConfirmado+1;
                    break;
            }
        }
        $saldo = Http::withHeaders(['access_token' => env('API_ASSAS')])->get(env('API_LINK_ASSAS').'/finance/balance');
        $json_str = $saldo->body();
        $jsonObj = json_decode($json_str);
        $saldo = $jsonObj->balance;

        $clientes = Cliente::get();
        $atrasado = Cobrancas::Select(DB::raw('clientes.name, SUM(cobrancas.netValue) as total'))->join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->where('status', "OVERDUE")->groupBy('clientes.name')->limit('3')->orderBy('total','DESC')->get();
        return view('dashboards.financeiro')->with(compact('title', 'previsto', 'confirmado', 'pendente', 'saldo', 'total', 'qntConfirmado', 'clientes', 'atrasado'));
    }
}
