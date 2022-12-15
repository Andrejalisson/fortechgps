@extends('template.sistema')

@section('css')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('corpo')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" id="pesquisa" class="form-control form-control-solid w-250px ps-14" placeholder="Pesquisar" />
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <a type="button" href="/Cobrancas/Atualizar" class="btn btn-primary" ><span class="svg-icon svg-icon-2"><i class="fa-solid fa-rotate"></i></span>Atualizar Registros</a> 
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_1"><i class="fa-solid fa-filter"></i></button> 

                        <a type="button" href="/Clientes/Adicionar" class="btn btn-primary" >
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>
                        Adicionar Cobrança</a>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="cobrancas">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Cliente</th>
                            <th class="min-w-100px">Valor</th>
                            <th class="min-w-100px">Vencimento</th>
                            <th class="min-w-100px">Status</th>
                            <th class="text-end min-w-150px">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtros</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="mb-0">
                    <label for="" class="form-label">Selecione um intervalo</label>
                    <input class="form-control form-control-solid" id="data"/>
                </div>
                <div class="mb-0">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" id="pago" checked/>
                        <label class="form-check-label" for="flexCheckDefault">Pagos</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" id="pagoManual" checked/>
                        <label class="form-check-label" for="flexCheckDefault">Pagos Manual</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" id="vencida" checked/>
                        <label class="form-check-label" for="flexCheckDefault">Vencidas</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" id="pendente" checked/>
                        <label class="form-check-label" for="flexCheckDefault">Pendentes</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="recarregar">Aplicar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>

@endsection

@section('script')
<script>
    $("#recarregar").click(function() {
        var intervalo = $("#data").val();

        if( $("#pago").is(":checked") == true){
            var pago = true
        }else{
            var pago = false
        }
        if( $("#pagoManual").is(":checked") == true){
            var pagoManual = true
        }else{
            var pagoManual = false
        }
        if( $("#vencida").is(":checked") == true){
            var vencida = true
        }else{
            var vencida = false
        }
        if( $("#pendente").is(":checked") == true){
            var pendente = true
        }else{
            var pendente = false
        }
        $('#cobrancas').dataTable({
                "pageLength": 10,
                "responsive": true,
                "processing": true,
                "order": [[ 2, "ASC" ]],
                "serverSide": true,
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ registros por página",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sInfo": "Mostrando _END_ de _TOTAL_ registro(s)",
                    "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de _MAX_ registros)",
                    "sSearch": "Pesquisar: ",
                    "oPaginate": {
                        "sFirst": "Início",
                        "sPrevious": "Anterior",
                        "sNext": "Próximo",
                        "sLast": "Último"
                    }
                },
                "ajax":{
                    "url": "{{ url('todasCobrancas') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{
                        _token: "{{csrf_token()}}",
                        intervalo: intervalo,
                        pago: pago,
                        pagoManual: pagoManual,
                        vencida: vencida,
                        pendente: pendente

                    }
                },
                "destroy" : true,
                "columns": [
                    { "data": "nome" },
                    { "data": "valor" },
                    { "data": "vencimento" },
                    { "data": "status" },
                    { "data": "opcoes" }
                ]
            });
    });
    $(document).ready(function () {
        var start = moment().startOf("month");
        var end = moment().endOf("month");
        $("#data").daterangepicker({
            "startDate": start,
            "endDate": end,
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
        var intervalo = $("#data").val();
        if( $("#pago").is(":checked") == true){
            var pago = true
        }else{
            var pago = false
        }
        if( $("#pagoManual").is(":checked") == true){
            var pagoManual = true
        }else{
            var pagoManual = false
        }
        if( $("#vencida").is(":checked") == true){
            var vencida = true
        }else{
            var vencida = false
        }
        if( $("#pendente").is(":checked") == true){
            var pendente = true
        }else{
            var pendente = false
        }
        $('#cobrancas').dataTable({
                "pageLength": 10,
                "responsive": true,
                "processing": true,
                "order": [[ 2, "ASC" ]],
                "serverSide": true,
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ registros por página",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sInfo": "Mostrando _END_ de _TOTAL_ registro(s)",
                    "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de _MAX_ registros)",
                    "sSearch": "Pesquisar: ",
                    "oPaginate": {
                        "sFirst": "Início",
                        "sPrevious": "Anterior",
                        "sNext": "Próximo",
                        "sLast": "Último"
                    }
                },
                "ajax":{
                    "url": "{{ url('todasCobrancas') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{
                        _token: "{{csrf_token()}}",
                        intervalo: intervalo,
                        pago: pago,
                        pagoManual: pagoManual,
                        vencida: vencida,
                        pendente: pendente

                    }
                },
                "columns": [
                    { "data": "nome" },
                    { "data": "valor" },
                    { "data": "vencimento" },
                    { "data": "status" },
                    { "data": "opcoes" }
                ]
            });
            $("#pesquisa").on('keyup', function (){
                $('#cobrancas').dataTable().fnFilter(this.value);
            });
        });

</script>
@endsection
