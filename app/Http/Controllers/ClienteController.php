<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use CodePhix\Asaas\Asaas;
use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Cobrancas;
use CodePhix\Asaas\Cobranca;
use Illuminate\Support\Facades\Http;

class ClienteController extends Controller{
    public function lista(){
        $title = "Clientes";
        return view('clientes.lista')->with(compact('title'));
    }

    public function add(){
        $title = "Adicionar Cliente";
        return view('clientes.add')->with(compact('title'));
    }

    public function editar($id){
        $title = "Editar Cliente";
        $cliente = Cliente::find($id);
        return view('clientes.editar')->with(compact('title', 'cliente'));
    }

    public function addPost(Request $request){

        $cpf = trim($request->cpf);
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);

        $telefone = trim($request->phone);
        $telefone = str_replace("(", "", $telefone);
        $telefone = str_replace(")", "", $telefone);
        $telefone = str_replace("-", "", $telefone);
        $telefone = str_replace(" ", "", $telefone);

        $celular = trim($request->celular);
        $celular = str_replace("(", "", $celular);
        $celular = str_replace(")", "", $celular);
        $celular = str_replace("-", "", $celular);
        $celular = str_replace(" ", "", $celular);

        $emergenciaTelefone = trim($request->emergenciaTelefone);
        $emergenciaTelefone = str_replace("(", "", $emergenciaTelefone);
        $emergenciaTelefone = str_replace(")", "", $emergenciaTelefone);
        $emergenciaTelefone = str_replace("-", "", $emergenciaTelefone);
        $emergenciaTelefone = str_replace(" ", "", $emergenciaTelefone);

        $cep = trim($request->cep);
        $cep = str_replace("-", "", $cep);

        $existe = Cliente::where('cpfCnpj', $cpf)->first();
        if ($existe == null) {
            $cliente = new Cliente;
            $cliente->name                              = $request->name;
            $cliente->email                             = $request->email;
            $cliente->rg                                = $request->rg;
            $cliente->cpfCnpj                           = $cpf;
            $cliente->birthDate                         = $request->nascimento;
            $cliente->phone                             = $telefone;
            $cliente->mobilePhone                       = $celular;
            $cliente->postalCode                        = $cep;
            $cliente->address                           = $request->logradouro;
            $cliente->addressNumber                     = $request->numero;
            $cliente->complement                        = $request->complemento;
            $cliente->province                          = $request->bairro;
            $cliente->city                              = $request->cidade;
            $cliente->state                             = $request->uf;
            $cliente->emergencyContact                  = $request->emergenciaNome;
            $cliente->emergencyContactMobilePhone       = $emergenciaTelefone;
            $cliente->observations                      = $request->observacoes;
            $cliente->save();
            $id = $cliente->id;
        }
        if ($request->softruck == 1){
            $data = array(
                "data" => array(
                    "name" => $request->name,
                    "username" => $request->usuario,
                    "email" => $request->email,
                    "cpf" => $request->cpf,
                    "phone1" => "55".$celular,
                    "locale" => "pt_BR",
                    "user_type" => "REGULAR",
                    "enterprise" => array(
                        "cnpj" => "42685026000160"
                    )
                )
            );

            $empresas = Http::withToken($request->session()->get('tokenSoftruck'))->post(env('API_SOFTRUCK').'/users', $data);
            $json_str = $empresas->body();
            $jsonObj = json_decode($json_str);
            $dados = json_encode($jsonObj->data->uuid);
            $empresass = Cliente::find($id);
            $empresass->softruck_id = json_decode($dados);
            $empresass->save();
        }

    }

    public function importAsaas(Request $request){
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
            }
            $cobrancas = $asaas->Cobranca()->getByCustomer($dados->id);

            foreach ($cobrancas->data as $cobrancas) {
                $cobranca = new Cobrancas;
                if (isset($id)) {
                    $cobranca->cliente_id               = $id;
                    $cobranca->externalReference        = $cobrancas->id;
                    $cobranca->paymentLink              = $cobrancas->paymentLink;
                    $cobranca->value                 = $cobrancas->value;
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
                    //dd($cobrancas);
                    $cobranca->save();
                }else{
                    //dd($cobrancas);
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


    public function todosClientes(Request $request){
        $columns = array(
            0 =>'name',
            1 =>'cpfCnpj',
            2 =>'email',
            3 =>'mobilePhone',
        );

        $totalData = Cliente::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value'))){
            $clientes = Cliente::offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }
        else{
            $search = $request->input('search.value');
            $clientes =  Cliente::where('name','LIKE',"%{$search}%")
                                    ->orwhere('cpfCnpj','LIKE',"%{$search}%")
                                    ->orwhere('email','LIKE',"%{$search}%")
                                    ->orwhere('mobilePhone','LIKE',"%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();
            $totalFiltered = Cliente::where('name','LIKE',"%{$search}%")
                                    ->orwhere('cpfCnpj','LIKE',"%{$search}%")
                                    ->orwhere('email','LIKE',"%{$search}%")
                                    ->orwhere('mobilePhone','LIKE',"%{$search}%")
                                    ->count();
        }
        $data = array();

        if(!empty($clientes)){
            foreach ($clientes as $cliente){
                $nestedData['nome'] =$cliente->name;
                $nestedData['documento'] =$cliente->cpfCnpj;
                $nestedData['email'] =$cliente->email;
                $nestedData['telefone'] =$cliente->mobilePhone;
                $nestedData['opcoes'] = "<a href=\"#\" class=\"btn btn-icon btn-success\"><i class=\"fas fa-eye fs-4 me-2\"></i></a>
                <a href=\"/Clientes/Editar/".$cliente->id."\" class=\"btn btn-icon btn-warning\"><i class=\"fas fa-pen fs-4 me-2\"></i></a>";

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
