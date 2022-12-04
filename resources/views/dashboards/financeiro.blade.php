@extends('template.sistema')

@section('css')
<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('corpo')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dashboard Financeiro</h1>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row g-5 g-xl-10 mb-xl-10">
                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 mb-xl-10">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 h-md-50 mb-5 mb-xl-10" style="background-color: #080655">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{$total}}</span>
                                    <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Cobranças Previstas</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                                <div class="d-flex align-items-center flex-column mt-3 w-100">
                                    <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                                        <span>{{$qntConfirmado}} Pagos no mês corrente</span>
                                        @php
                                            $resultado = round(($qntConfirmado / $total) * 100, 1);
                                        @endphp
                                        <span>{{$resultado}}%</span>
                                    </div>
                                    <div class="h-8px mx-3 w-100 bg-light-danger rounded">
                                        <div class="bg-danger rounded h-8px" role="progressbar" style="width: {{$resultado}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{$clientes->count()}}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Usuários Ativos</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end pe-0">
                                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Ultimos Cadastrados</span>
                                <div class="symbol-group symbol-hover flex-nowrap">
                                    @php
                                        $x=0;
                                    @endphp
                                    @foreach ($clientes as $clientes)
                                        @php
                                            $x=$x+1;
                                        @endphp
                                        @if ($x % 2 == 0 and $x <= 10)
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="{{$clientes->name}}">
                                                <span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                                            </div>
                                        @elseif($x <= 10)
                                            <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="{{$clientes->name}}">
                                                <span class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
                                            </div>
                                        @endif
                                    @endforeach



                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">R$</span>
                                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($saldo, 2, ',', '.')}}</span>
                                        <span class="badge badge-light-success fs-base">
                                    </div>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Dinheiro em caixa(Assas)</span>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                                <div class="d-flex flex-center me-5 pt-2">
                                    <div id="kt_card_widget_17_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div>
                                </div>
                                <div class="d-flex flex-column content-justify-center flex-row-fluid">
                                    <div class="d-flex fw-semibold align-items-center">
                                        <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                                        <div class="text-gray-500 flex-grow-1 me-4">Previsto</div>
                                        <div class="fw-bolder text-gray-700 text-xxl-end">R$ {{number_format($previsto, 2, ',', '.')}}</div>
                                    </div>
                                    <div class="d-flex fw-semibold align-items-center my-3">
                                        <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                                        <div class="text-gray-500 flex-grow-1 me-4">Confirmado</div>
                                        <div class="fw-bolder text-gray-700 text-xxl-end">R$ {{number_format($confirmado, 2, ',', '.')}}</div>
                                    </div>
                                    <div class="d-flex fw-semibold align-items-center">
                                        <div class="bullet w-8px h-3px rounded-2 me-3" style="background-color: #E4E6EF"></div>
                                        <div class="text-gray-500 flex-grow-1 me-4">Pendente</div>
                                        <div class="fw-bolder text-gray-700 text-xxl-end">R$ {{number_format($pendente, 2, ',', '.')}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush h-lg-50">
                            <div class="card-header pt-5">
                                <h3 class="card-title text-gray-800">Clientes Mais Inadimplentes</h3>

                            </div>
                            <div class="card-body pt-5">
                                @foreach ($atrasado as $atrasado)
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">{{$atrasado->name}}</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">R$ {{$atrasado->total}}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
@endsection

@section('script')
<script>


</script>
@endsection
