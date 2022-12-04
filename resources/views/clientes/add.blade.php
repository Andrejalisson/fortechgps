@extends('template.sistema')

@section('css')
<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('corpo')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{$title}}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="/Clientes" class="text-muted text-hover-primary">Clientes</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Adicionar</li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Informações Cliente</h3>
                        </div>
                    </div>
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <form class="form" method="POST" action="/Clientes/Adicionar">
                            @csrf
                            <div class="card-body border-top p-9">
                                <div class="row mb-6">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 fv-row">
                                                <label class="required form-label">Nome Completo</label>
                                                <input type="text" required name="nome"  class="form-control form-control-lg form-control-solid" placeholder="Nome" />
                                            </div>
                                            <div class="col-lg-3 fv-row">
                                                <label class="required form-label">E-mail</label>
                                                <input type="text" required name="email" id="email" class="form-control form-control-lg form-control-solid" placeholder="E-mail" />
                                            </div>
                                            <div class="col-lg-3 fv-row">
                                                <label class="required form-label">Usuário</label>
                                                <input type="text" required name="usuario" id="usuario" class="form-control form-control-lg form-control-solid" placeholder="Usuário" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-3 fv-row">
                                        <label class="form-label">RG</label>
                                        <input type="text" name="rg" class="form-control form-control-lg form-control-solid" placeholder="RG" />
                                    </div>
                                    <div class="col-lg-3 fv-row">
                                        <label class="required form-label">CPF</label>
                                        <input type="text" required name="cpf" id="cpf" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="CPF"/>
                                    </div>
                                    <div class="col-lg-2 fv-row">
                                        <label class="form-label">Data de Nascimento</label>
                                        <input type="tel" name="nascimento" id="nascimento" class="form-control form-control-lg form-control-solid" placeholder="Nascimento"/>
                                    </div>
                                    <div class="col-lg-2 fv-row">
                                        <label class="form-label">Telefone</label>
                                        <input type="tel" name="phone" id="phone" class="form-control form-control-lg form-control-solid" placeholder="Telefone"/>
                                    </div>
                                    <div class="col-lg-2 fv-row">
                                        <label class="required form-label">Celular <small>(Whatsapp)</small></label>
                                        <input type="tel" required name="celular" id="celular" class="form-control form-control-lg form-control-solid" placeholder="Celular"/>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-2 fv-row">
                                        <label class="form-label">CEP</label>
                                        <input type="text" name="cep" id="cep" class="form-control form-control-lg form-control-solid" placeholder="CEP"/>
                                    </div>
                                    <div class="col-lg-5 fv-row">
                                        <label class="form-label">Logradouro</label>
                                        <input type="text" name="logradouro" id="logradouro" class="form-control form-control-lg form-control-solid" placeholder="Logradouro"/>
                                    </div>
                                    <div class="col-lg-2 fv-row">
                                        <label class="form-label">Número</label>
                                        <input type="text" name="numero" id="numero" class="form-control form-control-lg form-control-solid" placeholder="Número"/>
                                    </div>
                                    <div class="col-lg-3 fv-row">
                                        <label class="form-label">Complemento</label>
                                        <input type="text" name="complemento" id="complemento" class="form-control form-control-lg form-control-solid" placeholder="Complemento"/>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-5 fv-row">
                                        <label class="form-label">Bairro</label>
                                        <input type="text" name="bairro" id="bairro" class="form-control form-control-lg form-control-solid" placeholder="Bairro"/>
                                    </div>
                                    <div class="col-lg-5 fv-row">
                                        <label class="form-label">Cidade</label>
                                        <input type="text" name="cidade" id="cidade" class="form-control form-control-lg form-control-solid" placeholder="Cidade"/>
                                    </div>
                                    <div class="col-lg-2 fv-row">
                                        <label class="form-label">Estado</label>
                                        <input type="text" name="uf" id="uf" class="form-control form-control-lg form-control-solid" placeholder="Estado"/>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-9 fv-row">
                                        <label class="form-label">Contato de Emergência</label>
                                        <input type="text" name="emergenciaNome" class="form-control form-control-lg form-control-solid" placeholder="Contato de emergência"/>
                                    </div>
                                    <div class="col-lg-3 fv-row">
                                        <label class="form-label">Telefone de Emergência</label>
                                        <input type="tel" name="emergenciaTelefone" id="emergenciaTelefone" class="form-control form-control-lg form-control-solid" placeholder="Telefone"/>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 fv-row">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control form-control-lg form-control-solid" name="observacoes" placeholder="Observações" style="height: 100px"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Integração</label>
                                    <div class="col-lg-8 fv-row">
                                        <div class="d-flex align-items-center mt-3">
                                            <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="softruck" checked type="checkbox" value="1" />
                                                <span class="fw-semibold ps-2 fs-6">Softruck</span>
                                            </label>
                                            <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="asaas" checked type="checkbox" value="1" />
                                                <span class="fw-semibold ps-2 fs-6">Assas</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Cancelar</button>
                                <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script src="/assets/plugins/global/plugins.bundle.js"></script>
@endsection

@section('script')
<script>

    $("#nascimento").daterangepicker({
        "singleDatePicker": true,
        "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "De",
        "toLabel": "Até",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Dom",
            "Seg",
            "Ter",
            "Qua",
            "Qui",
            "Sex",
            "Sáb"
        ],
        "monthNames": [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro"
        ],
        "firstDay": 0
        }
    });
    Inputmask({
        "mask" : "999.999.999-99"
    }).mask("#cpf");
    Inputmask({
        "mask" : "(99)9999-9999"
    }).mask("#phone");
    Inputmask({
        "mask" : "(99)99999-9999"
    }).mask("#celular");
    Inputmask({
        "mask" : "99999-999"
    }).mask("#cep");
    Inputmask({
        "mask" : "(99)99999-9999"
    }).mask("#emergenciaTelefone");
    $('#email').focusout( function(){
        var email = $('#email').val();
        resultado = email.substring(0 ,email.lastIndexOf("@"));
        $('#usuario').val(resultado);
        $('#usuario').attr("readonly", true);
    });

    $('#cep').focusout( function(){
            var documento = $('#cep').val();

            if(documento.length <= 8){

                // $('#razaoSocial').attr("readonly", true);
            }else{
                // $('#razaoSocial').attr("readonly", false);
                documento = documento.replace('.','');
                documento = documento.replace('-','');
                documento = documento.replace('/','');
                documento = documento.replace('.','');
                $.ajax({
                    url: 'https://viacep.com.br/ws/'+documento+'/json/',
                    dataType: 'jsonp',
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                            $('#cep').val(data.cep);
                            $('#cep').attr("readonly", true);
                            $('#logradouro').val(data.logradouro);
                            $('#logradouro').attr("readonly", true);
                            $('#complemento').val(data.complemento);
                            $('#complemento').attr("readonly", true);
                            $('#bairro').val(data.bairro);
                            $('#bairro').attr("readonly", true);
                            $('#cidade').val(data.localidade);
                            $('#cidade').attr("readonly", true);
                            $('#uf').val(data.uf);
                            $('#uf').attr("readonly", true);
                            $("#numero").focus();

                    }
                });

            }
        });

</script>
@endsection
