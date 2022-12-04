<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Enterprise;



class EnterpriseController extends Controller{

    public function lista(){
        $title = "Empresas";
        return view('empresas.lista')->with(compact('title'));
    }

    public function add(){
        $title = "Adicionar Empresas";
        return view('empresas.add')->with(compact('title'));
    }

    public function editar($id){
        $title = "Editar Empresa";
        $empresa = Enterprise::find($id);
        return view('empresas.editar')->with(compact('title', 'empresa'));
    }

    public function todasEmpresas(Request $request){
        $columns = array(
            0 =>'fantasy_name',
            1 =>'cnpj',
            2 =>'phone1',
            3 =>'email',
        );

        $totalData = Enterprise::all()->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value'))){
            $empresas = Enterprise::offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }
        else{
            $search = $request->input('search.value');
            $empresas =  Enterprise::where('fantasy_name','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            $totalFiltered = Enterprise::where('fantasy_name','LIKE',"%{$search}%")->count();
        }
        $data = array();

        if(!empty($empresas)){
            foreach ($empresas as $empresa){
                $nestedData['fantasia'] = $empresa->fantasy_name;
                $nestedData['cnpj'] = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $empresa->cnpj);
                $nestedData['contato'] = preg_replace("/(\d{2})(\d{1})(\d{4})(\d{4})/", "(\$1)\$2 \$3-\$4", $empresa->phone1);
                $nestedData['email'] = $empresa->email;
                $nestedData['opcoes'] = "<a href=\"#\" class=\"btn btn-icon btn-success\"><i class=\"fas fa-eye fs-4 me-2\"></i></a>
                                        <a href=\"/Empresas/Editar/".$empresa->id."\" class=\"btn btn-icon btn-warning\"><i class=\"fas fa-pen fs-4 me-2\"></i></a>";

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

    public function importSoftruck(Request $request){
        $empresa = Http::withToken($request->session()->get('tokenSoftruck'))->get(env('API_SOFTRUCK').'/enterprises');
        $json_str = $empresa->body();
        $jsonObj = json_decode($json_str);
        $empresas = json_encode($jsonObj->data->rows);
        foreach (json_decode($empresas) as $empresas) {
            $enterprise = Enterprise::where('cnpj', $empresas->cnpj)->first();
            $url = "https://publica.cnpj.ws/cnpj/".$empresas->cnpj;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);
            $dados = json_decode($resp);

            if($enterprise == null){
                $empresa = new Enterprise();
                $empresa->name = $empresas->name;
                $empresa->fantasy_name = $empresas->fantasy_name;
                $empresa->cnpj = $empresas->cnpj;
                $empresa->softruck_id = $empresas->uuid;
                $empresa->email = $empresas->email;
                $empresa->phone = $empresas->phone1;
                $empresa->postalCode = $dados->estabelecimento->cep;
                $empresa->address = $dados->estabelecimento->tipo_logradouro.": ".$dados->estabelecimento->logradouro;
                $empresa->addressNumber = $dados->estabelecimento->numero;
                $empresa->complement = $dados->estabelecimento->complemento;
                $empresa->province = $dados->estabelecimento->bairro;
                $empresa->city = $dados->estabelecimento->cidade->nome;
                $empresa->state = $dados->estabelecimento->estado->sigla;
                $empresa->theft_emergency_tel = $empresas->theft_emergency_tel;
                $empresa->assistance_emergency_tel = $empresas->assistance_emergency_tel;
                $empresa->save();
            }else{
                echo "Já tem essa empresa: ".$empresas->name;
            }
        }
        return redirect('/Empresas');
    }

    public function addPost(Request $request){
        $cnpj = trim($request->cnpj);
        $cnpj = str_replace(".", "", $cnpj);
        $cnpj = str_replace(",", "", $cnpj);
        $cnpj = str_replace("-", "", $cnpj);
        $cnpj = str_replace("/", "", $cnpj);

        $telefone = trim($request->contato);
        $telefone = str_replace("(", "", $telefone);
        $telefone = str_replace(")", "", $telefone);
        $telefone = str_replace("-", "", $telefone);
        $telefone = str_replace(" ", "", $telefone);

        $celular = trim($request->phone);
        $celular = str_replace("(", "", $celular);
        $celular = str_replace(")", "", $celular);
        $celular = str_replace("-", "", $celular);
        $celular = str_replace(" ", "", $celular);

        $cep = trim($request->cep);
        $cep = str_replace("-", "", $cep);
        $empresa = new Enterprise();
        $empresa->name                      = $request->razaosocial;
        $empresa->fantasy_name              = $request->fantasia;
        $empresa->cnpj                      = $cnpj;
        $empresa->email                     = $request->email;
        $empresa->phone                     = $telefone;
        $empresa->mobilePhone               = $celular;
        $empresa->postalCode                = $cep;
        $empresa->address                   = $request->logradouro;
        $empresa->addressNumber             = $request->numero;
        $empresa->complement                = $request->complemento;
        $empresa->province                  = $request->bairro;
        $empresa->city                      = $request->cidade;
        $empresa->state                     = $request->uf;
        $empresa->theft_emergency_tel       = $request->central;
        $empresa->assistance_emergency_tel  = $request->assistencia;
        $empresa->save();
        $id = $empresa->id;
        if ($request->integracao == 1) {
            $data = array(
                "data" => array(
                    "name" => $request->fantasia,
                    "fantasy_name" => $request->razaosocial,
                    "cnpj" => $request->cnpj,
                    "email" => $request->email,
                    "timezone" => "America/Sao-paulo",
                    "phone1" => $telefone,
                    "theft_emergency_tel" => $request->central,
                    "assistance_emergency_tel" => $request->assistencia,
                    "enterprise" => array(
                        "cnpj" => "42685026000160"
                    )
                )
            );

            $empresas = Http::withToken($request->session()->get('tokenSoftruck'))->post(env('API_SOFTRUCK').'/enterprises', $data);
            $json_str = $empresas->body();
            $jsonObj = json_decode($json_str);
            $dados = json_encode($jsonObj->data->uuid);
            $empresass = Enterprise::find($id);
            $empresass->softruck_id = json_decode($dados);
            $empresass->save();
        };
        return redirect('/Empresas');
    }

    public function editarPost(Request $request, $id){
        $cnpj = trim($request->cnpj);
        $cnpj = str_replace(".", "", $cnpj);
        $cnpj = str_replace(",", "", $cnpj);
        $cnpj = str_replace("-", "", $cnpj);
        $cnpj = str_replace("/", "", $cnpj);

        $telefone = trim($request->contato);
        $telefone = str_replace("(", "", $telefone);
        $telefone = str_replace(")", "", $telefone);
        $telefone = str_replace("-", "", $telefone);
        $telefone = str_replace(" ", "", $telefone);

        $celular = trim($request->phone);
        $celular = str_replace("(", "", $celular);
        $celular = str_replace(")", "", $celular);
        $celular = str_replace("-", "", $celular);
        $celular = str_replace(" ", "", $celular);

        $cep = trim($request->cep);
        $cep = str_replace("-", "", $cep);
        $empresa = Enterprise::find($id);
        $empresa->name                      = $request->razaosocial;
        $empresa->fantasy_name              = $request->fantasia;
        $empresa->cnpj                      = $cnpj;
        $empresa->email                     = $request->email;
        $empresa->phone                     = $telefone;
        $empresa->mobilePhone               = $celular;
        $empresa->postalCode                = $cep;
        $empresa->address                   = $request->logradouro;
        $empresa->addressNumber             = $request->numero;
        $empresa->complement                = $request->complemento;
        $empresa->province                  = $request->bairro;
        $empresa->city                      = $request->cidade;
        $empresa->state                     = $request->uf;
        $empresa->theft_emergency_tel       = $request->central;
        $empresa->assistance_emergency_tel  = $request->assistencia;
        if ($empresa->softruck_id != null) {
            $data = array(
                "data" => array(
                    "name" => $request->fantasia,
                    "fantasy_name" => $request->razaosocial,
                    "cnpj" => $request->cnpj,
                    "email" => $request->email,
                    "timezone" => "America/Sao-paulo",
                    "phone1" => $telefone,
                    "theft_emergency_tel" => $request->central,
                    "assistance_emergency_tel" => $request->assistencia
                )
            );
            Http::withToken($request->session()->get('tokenSoftruck'))->patch(env('API_SOFTRUCK')."/enterprises/".$cnpj, $data);
        };
        $empresa->save();
        return redirect('/Empresas');
    }

}
