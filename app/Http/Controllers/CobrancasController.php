<?php

namespace App\Http\Controllers;

use App\Models\Cobrancas;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Http;
use CodePhix\Asaas\Asaas;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $dias = date('Y-m-d', strtotime("+5 days"));
        $cobrancas = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->whereDate('dueDate', "=" , $dias)->where('status', "PENDING")->get();
        foreach ($cobrancas as $cobrancas) {
            $cobranca = new \stdClass();
            $primeiroNome = explode(" ", $cobrancas->name);
            $cobranca->name = $primeiroNome[0];
            $cobranca->email = $cobrancas->email;
            $cobranca->link = $cobrancas->invoiceUrl;
            Mail::send(new \App\Mail\LembreteCincoDias($cobranca));
        }
        $cobrancas = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->whereDate('dueDate', "=" , date('Y-m-d'))->where('status', "PENDING")->get();
        foreach ($cobrancas as $cobrancas) {
            $cobranca = new \stdClass();
            $primeiroNome = explode(" ", $cobrancas->name);
            $cobranca->name = $primeiroNome[0];
            $cobranca->email = $cobrancas->email;
            $cobranca->link = $cobrancas->invoiceUrl;
            Mail::send(new \App\Mail\LembreteDia($cobranca));
        }
        echo "email enviados";

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
            if ($existe == null) {
                $cliente = new Cliente;
                $cliente->name = $dados->name;
                $cliente->externalReference = $dados->id;
                $cliente->cpfCnpj = $dados->cpfCnpj;
                $cliente->email = $dados->email;
                $cliente->phone = $dados->phone;
                $cliente->mobilePhone = $dados->mobilePhone;
                $cliente->address = $dados->address;
                $cliente->addressNumber = $dados->addressNumber;
                $cliente->complement = $dados->complement;
                $cliente->province = $dados->province;
                $cliente->postalCode = $dados->postalCode;
                $cliente->state = $dados->state;
                $cliente->observations = $dados->observations;
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
}
