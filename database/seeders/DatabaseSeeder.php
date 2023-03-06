<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use CodePhix\Asaas\Asaas;
use Illuminate\Support\Facades\Http;
use App\Models\Cliente;
use App\Models\Cobrancas;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Enterprise;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'André Jálisson',
            'email' => 'andrejalisson@gmail.com',
            'username' => 'andrejalisson',
        ]);
        $asaas = new Asaas(env('API_ASSAS'), 'producao');
        $filtro = array(
            'limit' => 100,
        );
        $response = Http::accept('application/json')->post('https://public-api.softruck.com/api/v1/auth/login', [
            'username' => 'andrejalisson',
            'password' => 'Aa@31036700.',
        ]);
        $token = json_decode($response->body());
        foreach ($token as $dados) {
            $tokenSoftruck = $dados->token;
        }
        $empresa = Http::withToken($tokenSoftruck)->get(env('API_SOFTRUCK').'/enterprises');
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
                // $empresa->postalCode = $dados->estabelecimento->cep;
                // $empresa->address = $dados->estabelecimento->tipo_logradouro.": ".$dados->estabelecimento->logradouro;
                // $empresa->addressNumber = $dados->estabelecimento->numero;
                // $empresa->complement = $dados->estabelecimento->complemento;
                // $empresa->province = $dados->estabelecimento->bairro;
                // $empresa->city = $dados->estabelecimento->cidade->nome;
                // $empresa->state = $dados->estabelecimento->estado->sigla;
                $empresa->theft_emergency_tel = $empresas->theft_emergency_tel;
                $empresa->assistance_emergency_tel = $empresas->assistance_emergency_tel;
                $empresa->save();
            }else{
                echo "Já tem essa empresa: ".$empresas->name;
            }
        }
        $usuarios = Http::withToken($tokenSoftruck)->get(env('API_SOFTRUCK').'/users?limit=100&page=1');
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
                    //dd($cobrancas);
                    $cobranca->save();
                }else{
                    dd($cobrancas);
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

    }
}
