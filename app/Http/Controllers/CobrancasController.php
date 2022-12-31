<?php

namespace App\Http\Controllers;

use App\Models\Cobrancas;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Http;
use CodePhix\Asaas\Asaas;
use App\Models\User;
use App\Models\Logs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CobrancasController extends Controller{
    public function lista(){
        $title = "Cobrancas";
        return view('cobrancas.lista')->with(compact('title'));
    }

    public function email(){
        $title = "Cobrancas";
        return view('mail.lembreteCincoDias')->with(compact('title'));
    }

    public function emails(){
        $asaas = new Asaas(env('API_ASSAS'), 'producao');
        $filtro = array(
            'limit' => 100,
        );
        $cobrancas = $asaas->Cobranca()->getAll($filtro);
        dd($cobrancas);
        foreach ($cobrancas->data as $cobranca) {
            $fatura = Cobrancas::where('externalReference', $cobranca->id)->first();
            $quantidade = Cobrancas::where('externalReference', $cobranca->id)->count();
            if ($quantidade > 0) {
                $cliente = Cliente::where('externalReference', $cobranca->customer)->first();
                if($cobranca->status =! $fatura->status) {
                    switch ($cobranca->status) {
                        case 'RECEIVED':
                            $wpp =  "Cliente ".$cliente->name." Pagou a fatura no dia ".date('d/m/Y',  strtotime($cobranca->clientPaymentDate))."\n Valor Líguido: R$ ".number_format($cobranca->netValue, 2, ',', '.');
                            break;
                        case 'CONFIRMED':
                            $wpp =  "Cliente ".$cliente->name." Pagou a fatura no dia ".date('d/m/Y',  strtotime($cobranca->clientPaymentDate))."\n Valor Líguido: R$ ".number_format($cobranca->netValue, 2, ',', '.');
                            break;
                        case 'OVERDUE':
                            $wpp =  "Cliente ".$cliente->name." Não pagou a fatura do dia ".date('d/m/Y',  strtotime($cobranca->dueDate))."\n Valor Líguido: R$ ".number_format($cobranca->netValue, 2, ',', '.');
                            break;

                        default:
                            $wpp = "A cobrança veio diferente ".$cobranca->status." do cliente ".$cliente->name;
                            break;
                    }
                    Http::withHeaders([
                        'sessionkey' => '31036700'
                    ])->post(env('API_WPP')."/sendText", [
                        'session' => env('SESSION_WPP'),
                        'number' => '558585965372',
                        'text' => $wpp
                    ]);
                }
            }
        }
    }

    public function atualiza(Request $request){

       $asaas = new Asaas(env('API_ASSAS'), 'producao');
        $filtro = array(
            'limit' => 100,
        );
        $usuarios = Http::withToken($request->session()->get('tokenSoftruck'))->get(env('API_SOFTRUCK').'/users?limit=100&page=1');
        $json_str = $usuarios->body();
        $jsonObj = json_decode($json_str);
        $dadosUser = json_encode($jsonObj);
        $clientes = $asaas->Cliente()->getAll($filtro);
        foreach($clientes->data as $dados){
            $existe = Cliente::where("cpfCnpj", $dados->cpfCnpj)->first();
            $celular = trim($dados->mobilePhone);
            $celular = str_replace("(", "", $celular);
            $celular = str_replace(")", "", $celular);
            $celular = str_replace("-", "", $celular);
            $celular = str_replace(" ", "", $celular);
            $telefone = trim($dados->phone);
            $telefone = str_replace("(", "", $telefone);
            $telefone = str_replace(")", "", $telefone);
            $telefone = str_replace("-", "", $telefone);
            $telefone = str_replace(" ", "", $telefone);
            if ($existe == null) {
                $cliente = new Cliente;
                $cliente->name              = $dados->name;
                $cliente->externalReference = $dados->id;
                $cliente->cpfCnpj           = $dados->cpfCnpj;
                $cliente->email             = $dados->email;
                $cliente->phone             = $telefone;
                $cliente->mobilePhone       = $celular;
                $cliente->address           = $dados->address;
                $cliente->addressNumber     = $dados->addressNumber;
                $cliente->complement        = $dados->complement;
                $cliente->province          = $dados->province;
                $cliente->postalCode        = $dados->postalCode;
                $cliente->state             = $dados->state;
                $cliente->observations      = $dados->observations;
                $cliente->save();
                $id = $cliente->id;
            }else{
                $id = $existe->id;
            }
            $cobrancas = $asaas->Cobranca()->getByCustomer($dados->id);
            foreach ($cobrancas->data as $cobrancas) {
                $fatura = Cobrancas::where("externalReference",$cobrancas->id)->first();
                if ($fatura == null) {
                    $cobranca = new Cobrancas;
                    $cobranca->cliente_id               = $id;
                    $cobranca->externalReference        = $cobrancas->id;
                    $cobranca->paymentLink              = $cobrancas->paymentLink;
                    $cobranca->value                    = $cobrancas->value;
                    $cobranca->netValue                 = $cobrancas->netValue;
                    $cobranca->grossValue               = $cobrancas->grossValue;
                    $cobranca->originalValue            = $cobrancas->originalValue;
                    $cobranca->interestValue            = $cobrancas->interestValue;
                    $cobranca->description              = $cobrancas->description;
                    $cobranca->status                   = $cobrancas->status;
                    $cobranca->dueDate                  = $cobrancas->dueDate;
                    $cobranca->originalDueDate          = $cobrancas->originalDueDate;
                    $cobranca->billingType              = $cobrancas->billingType;
                    $cobranca->installmentNumber        = $cobrancas->installmentNumber;
                    $cobranca->invoiceUrl               = $cobrancas->invoiceUrl;
                    $cobranca->invoiceNumber            = $cobrancas->invoiceNumber;
                    $cobranca->deleted                  = $cobrancas->deleted;
                    $cobranca->anticipated              = $cobrancas->anticipated;
                    $cobranca->anticipable              = $cobrancas->anticipable;
                    $cobranca->creditDate               = $cobrancas->creditDate;
                    $cobranca->estimatedCreditDate      = $cobrancas->estimatedCreditDate;
                    $cobranca->transactionReceiptUrl    = $cobrancas->transactionReceiptUrl;
                    $cobranca->nossoNumero              = $cobrancas->nossoNumero;
                    $cobranca->bankSlipUrl              = $cobrancas->bankSlipUrl;
                    $cobranca->description              = $cobrancas->description;
                    $cobranca->postalService            = $cobrancas->postalService;
                    $cobranca->save();
                }else{
                    $cobranca = Cobrancas::find($fatura->id);
                    $cobranca->cliente_id               = $id;
                    $cobranca->externalReference        = $cobrancas->id;
                    $cobranca->paymentLink              = $cobrancas->paymentLink;
                    $cobranca->value                    = $cobrancas->value;
                    $cobranca->netValue                 = $cobrancas->netValue;
                    $cobranca->grossValue               = $cobrancas->grossValue;
                    $cobranca->originalValue            = $cobrancas->originalValue;
                    $cobranca->interestValue            = $cobrancas->interestValue;
                    $cobranca->description              = $cobrancas->description;
                    $cobranca->status                   = $cobrancas->status;
                    $cobranca->dueDate                  = $cobrancas->dueDate;
                    $cobranca->originalDueDate          = $cobrancas->originalDueDate;
                    $cobranca->billingType              = $cobrancas->billingType;
                    $cobranca->installmentNumber        = $cobrancas->installmentNumber;
                    $cobranca->invoiceUrl               = $cobrancas->invoiceUrl;
                    $cobranca->invoiceNumber            = $cobrancas->invoiceNumber;
                    $cobranca->deleted                  = $cobrancas->deleted;
                    $cobranca->anticipated              = $cobrancas->anticipated;
                    $cobranca->anticipable              = $cobrancas->anticipable;
                    $cobranca->creditDate               = $cobrancas->creditDate;
                    $cobranca->estimatedCreditDate      = $cobrancas->estimatedCreditDate;
                    $cobranca->transactionReceiptUrl    = $cobrancas->transactionReceiptUrl;
                    $cobranca->nossoNumero              = $cobrancas->nossoNumero;
                    $cobranca->bankSlipUrl              = $cobrancas->bankSlipUrl;
                    $cobranca->description              = $cobrancas->description;
                    $cobranca->postalService            = $cobrancas->postalService;
                    $cobranca->save();
                }

            }

        }
        $dados = json_decode($dadosUser);
        foreach ($dados->data->rows as $usuario) {
            if ($usuario->user_type == "OWNER") {
                $existes = User::where('username', $usuario->username)->first();
                if ($existes == null) {
                    $user = new User;
                    $user->name = $usuario->name;
                    $user->username = $usuario->username;
                    $user->email = $usuario->email;
                    $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
                    $user->email_verified_at = now();
                    $user->remember_token = Str::random(10);
                    $cpf = trim($usuario->cpf);
                    $cpf = str_replace(".", "", $cpf);
                    $cpf = str_replace("-", "", $cpf);
                    $cliente = Cliente::where("cpfCnpj", $cpf)->first();
                    if ($cliente != null) {
                        $user->cliente_id = $cliente->id;
                    }
                    $user->save();
                }


            }


        }
        $request->session()->flash('sucesso', 'Clientes importados do Asaas.');
        return redirect()->back();


    }



    public function todasCobrancas(Request $request){
        $columns = array(
            0 =>'name',
            1 =>'value',
            2 =>'dueDate',
            3 =>'status',
        );
        $intervalo = $request->input('intervalo');
        $inicio = implode('-', array_reverse(explode('/', substr($intervalo, 0, 10))));
        $fim = implode('-', array_reverse(explode('/', substr($intervalo, 13, 20))));
        $status = array();
        if($request->input('pago') == "true"){
            array_push($status, "RECEIVED", "CONFIRMED");
        }
        if($request->input('pagoManual') == "true"){
            array_push($status, "RECEIVED_IN_CASH");
        }
        if($request->input('vencida') == "true"){
            array_push($status, "OVERDUE", "DUNNING_REQUESTED");
        }
        if($request->input('pendente') == "true"){
            array_push($status, "PENDING");
        }
        $totalData = Cobrancas::whereBetween('dueDate', [$inicio, $fim])
                                ->whereIn('status', $status )
                                ->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value'))){
            $cobrancas = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->whereBetween('dueDate', [$inicio, $fim])->whereIn('status', $status )->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }
        else{
            $search = $request->input('search.value');
            $cobrancas = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')
                                    ->whereBetween('dueDate', [$inicio, $fim])
                                    ->whereIn('status', $status )
                                    ->where('clientes.name','LIKE',"%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
            $totalFiltered = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')
                                    ->whereBetween('dueDate', [$inicio, $fim])
                                    ->whereIn('status', $status )
                                    ->where('clientes.name','LIKE',"%{$search}%")
                                    ->count();
        }
        $data = array();

        if(!empty($cobrancas)){
            foreach ($cobrancas as $cobranca){
                $nestedData['nome'] =$cobranca->name;
                $nestedData['valor'] ="R$ ".number_format($cobranca->value, 2, ',', '.');
                $nestedData['vencimento'] =date('d/m/Y',  strtotime($cobranca->dueDate));
                switch ($cobranca->status) {
                    case 'PENDING':
                        $nestedData['status'] = "<span class=\"badge badge-light-primary\">Pendente</span>";
                        break;
                    case 'RECEIVED':
                        $nestedData['status'] = "<span class=\"badge badge-light-success\">Pago</span>";
                        break;
                    case 'CONFIRMED':
                        $nestedData['status'] = "<span class=\"badge badge-light-success\">Confirmada</span>";
                        break;
                    case 'OVERDUE':
                        $nestedData['status'] = "<span class=\"badge badge-light-danger\">Vencida</span>";
                        break;
                    case 'REFUNDED':
                        $nestedData['status'] = "<span class=\"badge badge-light-dark\">Estornada</span>";
                        break;
                    case 'RECEIVED_IN_CASH':
                        $nestedData['status'] = "<span class=\"badge badge-light-success\">Pago em dinheiro</span>";
                        break;
                    case 'REFUND_REQUESTED':
                        $nestedData['status'] = "<span class=\"badge badge-light-warning\">Estorno Solicitado</span>";
                        break;
                    case 'CHARGEBACK_REQUESTED':
                        $nestedData['status'] = "<span class=\"badge badge-light-success\">Dispulta Favorável</span>";
                        break;
                    case 'CHARGEBACK_DISPUTE':
                        $nestedData['status'] = "<span class=\"badge badge-light-warning\">Em Dispulta</span>";
                        break;
                    case 'AWAITING_CHARGEBACK_REVERSAL':
                        $nestedData['status'] = "<span class=\"badge badge-light-success\">Disputa vencida</span>";
                        break;
                    case 'DUNNING_REQUESTED':
                        $nestedData['status'] = "<span class=\"badge badge-light-info\">Negativada</span>";
                        break;
                    case 'DUNNING_RECEIVED':
                        $nestedData['status'] = "<span class=\"badge badge-light-success\">Recuperada</span>";
                        break;
                    case 'AWAITING_RISK_ANALYSIS':
                        $nestedData['status'] = "<span class=\"badge badge-light-warning\">Pagamento em análise</span>";
                        break;
                }

                $nestedData['opcoes'] = "<a href=\"#\" class=\"btn btn-icon btn-success\"><i class=\"fas fa-eye fs-4 me-2\"></i></a>
                <a href=\"/Clientes/Editar/".$cobranca->id."\" class=\"btn btn-icon btn-warning\"><i class=\"fas fa-pen fs-4 me-2\"></i></a>";

                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function webhook(Request $request){
        $json = $request->all();
        $cobranca = (object)$json;

        $cliente = Cliente::where('externalReference', $cobranca->payment['customer'])->first();
        if($cliente == null){
            $asaas = new Asaas(env('API_ASSAS'), 'producao');
            $dados = $asaas->Cliente()->getById($cobranca->payment['customer']);
            $cliente = new Cliente;
            $cliente->name              = $dados->name;
            $cliente->externalReference = $dados->id;
            $cliente->cpfCnpj           = $dados->cpfCnpj;
            $cliente->email             = $dados->email;
            $cliente->phone             = $dados->phone;
            $cliente->mobilePhone       = $dados->mobilePhone;
            $cliente->address           = $dados->address;
            $cliente->addressNumber     = $dados->addressNumber;
            $cliente->complement        = $dados->complement;
            $cliente->province          = $dados->province;
            $cliente->postalCode        = $dados->postalCode;
            $cliente->state             = $dados->state;
            $cliente->observations      = $dados->observations;
            $cliente->save();
            $mensagem = "O cliente ".$cliente->name." acabou de ser cadastrado no sistema";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
            $cliente = Cliente::where('externalReference', $cobranca->payment['customer'])->first();
        }
        switch ($cobranca->event) {
            case 'PAYMENT_CREATED': //Geração de nova cobrança.
                $mensagem = "Uma nova cobrança foi criada do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.');
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_AWAITING_RISK_ANALYSIS': //Pagamento em cartão aguardando aprovação pela análise manual de risco.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." paga via cartão de crédito está em análise manual de risco.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_APPROVED_BY_RISK_ANALYSIS': //Pagamento em cartão aprovado pela análise manual de risco.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." paga via cartão de crédito foi aprovada pela análise manual de risco.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_REPROVED_BY_RISK_ANALYSIS': //Pagamento em cartão reprovado pela análise manual de risco.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." paga via cartão de crédito foi reprovada pela análise manual de risco.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_UPDATED': //Alteração no vencimento ou valor de cobrança existente.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." teve uma alteração manual no valor ou vencimento.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_CONFIRMED': //Cobrança confirmada (pagamento efetuado, porém o saldo ainda não foi disponibilizado).
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." foi paga via cartão de crédito, precisa fazer a antecipação do valor.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                $mensagem = "Oi ".$cliente->name.", tudo bem? 🤩\nEstamos muito felizes em informá-lo(a) que *seu pagamento foi confirmado!!!*\nMuito obrigado por confiar na gente, e continuar mais um mês conosco!\nTenha um ótimo dia, e *MUITO OBRIGADO!!!* 💙💙";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '5585'.substr($cliente->mobilePhone,3),
                    'text' => $mensagem
                ]);

                break;
            case 'PAYMENT_RECEIVED': //Cobrança recebida.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." foi paga.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                $mensagem = "Oi ".$cliente->name.", tudo bem? 🤩\nEstamos muito felizes em informá-lo(a) que *seu pagamento foi confirmado!!!*\nMuito obrigado por confiar na gente, e continuar mais um mês conosco!\nTenha um ótimo dia, e *MUITO OBRIGADO!!!* 💙💙";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '5585'.substr($cliente->mobilePhone,3),
                    'text' => $mensagem
                ]);

                break;
            case 'PAYMENT_OVERDUE': //Cobrança vencida.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." venceu e não foi identificado pagamento até então.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                $mensagem = "Oi ".$cliente->name.", tudo bem? 🤩\nSabemos que na correria do dia a dia pode acontecer de esquecermos alguns compromissos.\nEntão, com o intuito de te ajudar, vinhemos lembrar que a sua fatura deste mês *encontra-se vencida.*\n\nmportante lembrar que a *inadimplência* resulta na aplicação de *multa e juros* sobre o valor devido, além do *bloqueio do seu rastreamento* e a impossibilidade de solicitação de assistência 24h.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '5585'.substr($cliente->mobilePhone,3),
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_DELETED': //Cobrança removida.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." foi removida do sistema.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_RESTORED': //Cobrança restaurada.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." foi restaurada no sistema.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_REFUNDED': //Cobrança estornada.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." foi estornada.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_RECEIVED_IN_CASH_UNDONE': //Recebimento em dinheiro desfeito.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." foi desfeito o recebimento manual.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_CHARGEBACK_REQUESTED': //Recebido chargeback.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." teve o valor creditado depois de ganharmos a disputa.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_CHARGEBACK_DISPUTE': //Em disputa de chargeback (caso sejam apresentados documentos para contestação).
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." teve dispulta solicitada.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_AWAITING_CHARGEBACK_REVERSAL': //Disputa vencida, aguardando repasse da adquirente.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." teve o prazo de disputa encerrado, aguardando o repasse.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_DUNNING_RECEIVED': //Recebimento de negativação.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." que estava negativado no SERASA, teve o pagamento efetuado.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_DUNNING_REQUESTED': //Requisição de negativação.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." teve a solicitação de negativação no SERASA.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_BANK_SLIP_VIEWED': //Boleto da cobrança visualizado pelo cliente.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." teve o boleto gerado pelo cliente.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;
            case 'PAYMENT_CHECKOUT_VIEWED': //Fatura da cobrança visualizada pelo cliente.
                $mensagem = "A cobrança do cliente ".$cliente->name." no valor de R$".number_format($cobranca->payment['value'], 2, ',', '.')." foi visualizada.";
                Http::withHeaders([
                    'sessionkey' => 'Aa@31036700.'
                ])->post(env('API_WPP')."/sendText", [
                    'session' => env('SESSION_WPP'),
                    'number' => '558585965372',
                    'text' => $mensagem
                ]);
                break;

            default:
                # code...
                break;
        }
        $fatura = Cobrancas::where('cobrancas.externalReference', $cobranca->payment['id'])->first();
        if ($fatura == null) {
            $fatura = new Cobrancas;
        }
        $fatura->externalReference      =   $cobranca->payment['id'];
        $fatura->paymentLink            =   $cobranca->payment['paymentLink'];
        $fatura->value                  =   $cobranca->payment['value'];
        $fatura->netValue               =   $cobranca->payment['netValue'];
        $fatura->originalValue          =   $cobranca->payment['originalValue'];
        $fatura->interestValue          =   $cobranca->payment['interestValue'];
        $fatura->description            =   $cobranca->payment['description'];
        $fatura->status                 =   $cobranca->payment['status'];
        $fatura->dueDate                =   $cobranca->payment['dueDate'];
        $fatura->originalDueDate        =   $cobranca->payment['originalDueDate'];
        $fatura->billingType            =   $cobranca->payment['billingType'];
        $fatura->installmentNumber      =   $cobranca->payment['installmentNumber'];
        $fatura->invoiceUrl             =   $cobranca->payment['invoiceUrl'];
        $fatura->invoiceNumber          =   $cobranca->payment['invoiceNumber'];
        $fatura->deleted                =   $cobranca->payment['deleted'];
        $fatura->anticipated            =   $cobranca->payment['anticipated'];
        $fatura->anticipable            =   $cobranca->payment['anticipable'];
        $fatura->creditDate             =   $cobranca->payment['creditDate'];
        $fatura->estimatedCreditDate    =   $cobranca->payment['estimatedCreditDate'];
        $fatura->transactionReceiptUrl  =   $cobranca->payment['transactionReceiptUrl'];
        $fatura->bankSlipUrl            =   $cobranca->payment['bankSlipUrl'];
        $fatura->description            =   $cobranca->payment['description'];
        $fatura->postalService          =   $cobranca->payment['postalService'];
        $fatura->save();
        return response()->json([
            'message' => 'Ok'
        ], 200);
    }
}
