@extends('template.sistema')

@section('css')
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
                            <a href="/Empresas" class="text-muted text-hover-primary">Empresas</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Editar</li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Informações Empresariais</h3>
                        </div>
                    </div>
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <form class="form" method="POST" action="/Empresas/Editar/{{$empresa->id}}">
                            @csrf
                            <div class="card-body border-top p-9">
                                <div class="row mb-6">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-3 fv-row">
                                                <input type="text" required name="cnpj" id="cnpj" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" value="{{$empresa->cnpj}}"/>
                                            </div>
                                            <div class="col-lg-5 fv-row">
                                                <input type="text" required name="razaosocial" id="razaosocial"  class="form-control form-control-lg form-control-solid" value="{{$empresa->name}}" />
                                            </div>
                                            <div class="col-lg-4 fv-row">
                                                <input type="text" name="fantasia" id="nomeFantasia" class="form-control form-control-lg form-control-solid" value="{{$empresa->fantasy_name}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-4 fv-row">
                                        <input type="text" name="email" id="email" class="form-control form-control-lg form-control-solid" value="{{$empresa->email}}" />
                                    </div>
                                    <div class="col-lg-4 fv-row">
                                        <input type="text" name="contato" id="telefone" class="form-control form-control-lg form-control-solid" value="{{$empresa->phone}}" />
                                    </div>
                                    <div class="col-lg-4 fv-row">
                                        <input type="tel" name="phone" id="celular" class="form-control form-control-lg form-control-solid" value="{{$empresa->mobilePhone}}"/>
                                    </div>
                                </div>
                                <div class="row mb-6">

                                    <div class="col-lg-4 fv-row">
                                        <input type="tel" name="assistencia" class="form-control form-control-lg form-control-solid" value="{{$empresa->assistance_emergency_tel}}"/>
                                    </div>
                                    <div class="col-lg-4 fv-row">
                                        <input type="tel" name="central" class="form-control form-control-lg form-control-solid" value="{{$empresa->theft_emergency_tel}}"/>
                                    </div>
                                    <div class="col-lg-4 fv-row">
                                        <input type="text" name="cep" id="cep" class="form-control form-control-lg form-control-solid" value="{{$empresa->postalCode}}"/>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-7 fv-row">
                                        <input type="text" name="logradouro" id="logradouro" class="form-control form-control-lg form-control-solid" value="{{$empresa->address}}"/>
                                    </div>
                                    <div class="col-lg-2 fv-row">
                                        <input type="text" name="numero" id="numero" class="form-control form-control-lg form-control-solid" value="{{$empresa->addressNumber}}"/>
                                    </div>
                                    <div class="col-lg-3 fv-row">
                                        <input type="text" name="complemento" id="complemento" class="form-control form-control-lg form-control-solid" value="{{$empresa->complement}}"/>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-5 fv-row">
                                        <input type="text" name="bairro" id="bairro" class="form-control form-control-lg form-control-solid" value="{{$empresa->province}}"/>
                                    </div>
                                    <div class="col-lg-5 fv-row">
                                        <input type="text" name="cidade" id="cidade" class="form-control form-control-lg form-control-solid" value="{{$empresa->city}}"/>
                                    </div>
                                    <div class="col-lg-2 fv-row">
                                        <input type="text" name="uf" id="uf" class="form-control form-control-lg form-control-solid" value="{{$empresa->state}}"/>
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
    Inputmask({
        "mask" : "99.999.999/9999-99"
    }).mask("#cnpj");
    Inputmask({
        "mask" : "(99) 9999-9999"
    }).mask("#telefone");
    Inputmask({
        "mask" : "(99) 99999-9999"
    }).mask("#celular");
    Inputmask({
        "mask" : "99999-999"
    }).mask("#cep");

    $('#cnpj').focusout( function(){
            var documento = $('#cnpj').val();

            if(documento.length <= 14){
                // $('#razaoSocial').attr("readonly", true);
            }else{
                // $('#razaoSocial').attr("readonly", false);
                documento = documento.replace('.','');
                documento = documento.replace('-','');
                documento = documento.replace('/','');
                documento = documento.replace('.','');
                $.ajax({
                    url: 'https://www.receitaws.com.br/v1/cnpj/'+documento,
                    dataType: 'jsonp',
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        if(data.status == "OK"){
                            $('#nomeFantasia').val(data.fantasia);
                            $('#razaosocial').val(data.nome);
                            $('#email').val(data.email);
                            $('#telefone').val(data.telefone);
                            $('#cep').val(data.cep);
                            $('#logradouro').val(data.logradouro);
                            $('#numero').val(data.numero);
                            $('#complemento').val(data.complemento);
                            $('#bairro').val(data.bairro);
                            $('#cidade').val(data.municipio);
                            $('#uf').val(data.uf);
                            $("#celular").focus();
                        }else{
                            $("#cnpj").focus();
                            $('#cnpj').val("");
                            iziToast.error({
                                title: 'Erro',
                                message: data.message
                            });
                        }

                    }
                });

            }
        });

</script>
@endsection
