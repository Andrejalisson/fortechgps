@extends('template.acesso')

@section('css')
@endsection

@section('corpo')
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <!--begin::Content-->
        <div class="d-flex flex-column flex-center text-center p-10">
            <!--begin::Wrapper-->
            <div class="card card-flush w-lg-650px py-5">
                <div class="card-body py-15 py-lg-20">
                    <!--begin::Logo-->
                    <div class="mb-13">
                        <a href="../../demo1/dist/index.html" class="">
                            <img alt="Logo" src="assets/media/logos/custom-2.svg" class="h-40px" />
                        </a>
                    </div>
                    <!--end::Logo-->
                    <!--begin::Title-->
                    <h1 class="fw-bolder text-gray-900 mb-7">Link Expirado.</h1>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="fw-semibold fs-6 text-gray-500 mb-7">Já faz mais de 24h que você solicitou a mudança de senha, favor solicite novamente para que seja gerado um novo link</div>
                    <!--end::Text-->
                    <!--begin::Form-->
                    <form class="w-md-350px mb-2 mx-auto" method="post" action="/EsqueceuSenha" >
                        @csrf
                        <div class="fv-row text-start">
                            <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                                <!--end::Input=-->
                                <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control" />
                                <!--end::Input=-->
                                <!--begin::Submit-->
                                <button class="btn btn-primary text-nowrap" type="submit">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Enviar</span>
                                    <!--end::Indicator label-->
                                </button>
                                <!--end::Submit-->
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                    <!--begin::Illustration-->
                    <div class="mb-n5">
                        <img src="assets/media/auth/chart-graph.png" class="mw-100 mh-300px theme-light-show" alt="" />
                        <img src="assets/media/auth/chart-graph-dark.png" class="mw-100 mh-300px theme-dark-show" alt="" />
                    </div>
                    <!--end::Illustration-->
                </div>
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Content-->
    </div>
@endsection

@section('js')
@endsection

@section('script')
@endsection
