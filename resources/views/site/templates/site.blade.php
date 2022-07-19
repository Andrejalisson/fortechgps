<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}} | Fortech GPS Rastreamento Veicular</title>
    <link rel="apple-touch-icon" sizes="57x57" href="/site/images/favicon//apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/site/images/favicon//apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/site/images/favicon//apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/site/images/favicon//apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/site/images/favicon//apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/site/images/favicon//apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/site/images/favicon//apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/site/images/favicon//apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/site/images/favicon//apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/site/images/favicon//android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/site/images/favicon//favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/site/images/favicon//favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/site/images/favicon//favicon-16x16.png">
    <link rel="manifest" href="/site/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="/site/css/style.css">
    <link rel="stylesheet" href="/site/css/responsive.css">
    @yield('css')
</head>

<body>
<div class="preloader"></div><!-- /.preloader -->

<div class="page-wrapper">
    <header class="main-header header-style-two">
        <div class="top-header">
            <div class="container">
                <div class="left-info">
                    <p><i class="cameron-icon-email"></i><a href="mailto:contato@fortechgps.com.br">contato@fortechgps.com.br</a></p>
                </div><!-- /.left-info -->
                <div class="right-info">
                    <ul class="info-block">
                        <li><i class="cameron-icon-support"></i><a href="#">(85) 3300 1816</a></li>
                    </ul>
                </div><!-- /.right-info -->
            </div><!-- /.container -->
        </div><!-- /.top-header -->
        <nav class="navbar navbar-expand-lg navbar-light header-navigation stricky">
            <div class="container clearfix">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="logo-box clearfix">
                    <a class="navbar-brand" href="index.html">
                        <img src="/site/images/resources/logo-1-1.png" class="main-logo" alt="Fortech GPS LOGO"/>
                        <img src="/site/images/resources/logo-1-2.png" class="stricky-logo" alt="Fortech GPS LOGO"/>
                    </a>
                    <button class="menu-toggler" data-target=".header-style-two .main-navigation">
                        <span class="fa fa-bars"></span>
                    </button>
                </div><!-- /.logo-box -->
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="main-navigation">
                    <ul class=" navigation-box">
                        @if($title == "Home")
                            <li class="current">
                                <a href="/">Home</a>
                            </li>
                        @else
                            <li>
                                <a href="/">Home</a>
                            </li>
                        @endif
                        @if($title == "Sobre")
                            <li class="current">
                                <a href="/Sobre">Sobre</a>
                            </li>
                        @else
                            <li>
                                <a href="/Sobre">Sobre</a>
                            </li>
                        @endif
                        @if($title == "Planos")
                            <li class="current">
                                <a href="/Planos">Planos</a>
                            </li>
                        @else
                            <li>
                                <a href="/Planos">Planos</a>
                            </li>
                        @endif
                        @if($title == "Assistência 24h")
                            <li class="current">
                                <a href="/Assistencia">Assistência 24h</a>
                            </li>
                        @else
                            <li>
                                <a href="/Assistencia">Assistência 24h</a>
                            </li>
                        @endif
                        <li>
                            <a href="https://fortechgps.softruck.com/access/login" target="_blank">Plataforma</a>
                            <ul class="sub-menu">
                                <li><a href="https://fortechgps.softruck.com/access/login" target="_blank">Área do Cliente</a></li>
                                <li><a href="/Login">Área administrativa</a></li>
                            </ul><!-- /.sub-menu -->
                        </li>

                    </ul>
                </div><!-- /.navbar-collapse -->
                <div class="right-side-box">
                    <div class="social">
                        <a href="https://www.instagram.com/fortechgps/" target="_blank"><i class="fa fa-instagram"></i></a>
                        <a href="https://web.facebook.com/fortechgps" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="https://g.page/r/CVhtJ3r-yM3kEAg/review" target="_blank"><i class="fa fa-google-plus"></i></a>
                    </div><!-- /.social -->
                </div><!-- /.right-side-box -->
            </div>
            <!-- /.container -->
        </nav>
    </header><!-- /.main-header header-style-one -->
    @yield('corpo')
    <footer class="site-footer">
        <div class="main-footer">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget about-widget">
                            <a href="index.html" class="footer-logo">
                                <img src="/site/images/resources/footer-logo-1-1.png" alt="awesome image">
                            </a>
                            <p>Fortech GPS Tem o Rastreador Ideal para o Seu Veículo. Saiba Mais. Empresa Especializada no Monitoramento e Rastreamento de Veículos Via Satélite.</p>
                            <div class="social-block">
                                <a href="https://g.page/r/CVhtJ3r-yM3kEAg/review" target="_blank"><i class="fa fa-google-plus"></i></a>
                                <a href="https://www.instagram.com/fortechgps/" target="_blank"><i class="fa fa-instagram"></i></a>
                                <a href="https://web.facebook.com/fortechgps" target="_blank"><i class="fa fa-facebook"></i></a>
                            </div><!-- /.social-block -->
                        </div><!-- /.footer-widget -->
                    </div><!-- /.col-lg-3 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget links-widget">
                            <div class="footer-widget-title">
                                <h3>SiteMap</h3>
                            </div><!-- /.footer-widget-title -->
                            <ul class="links-lists">
                                <li><a href="/">HOME </a></li>
                                <li><a href="/Sobre">SOBRE </a></li>
                                <li><a href="/Planos">PLANOS </a></li>
                                <li><a href="/Assistencia">ASSISTÊNCIA 24H</a></li>
                                <li><a href="https://fortechgps.softruck.com/access/login" target="_blank">ÁREA DO CLIENTE</a></li>
                                <li><a href="#">ÁREA ADMINISTRATIVA</a></li>
                            </ul><!-- /.links-lists -->
                        </div><!-- /.footer-widget -->
                    </div><!-- /.col-lg-3 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget works-widget">
                            <div class="footer-widget-title">
                                <h3>Facebook </h3>
                            </div><!-- /.footer-widget-title -->
                            <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Ffortechgps%2F&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=348801248988519" width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        </div><!-- /.footer-widget -->
                    </div><!-- /.col-lg-3 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div><!-- /.main-footer -->
        <div class="bottom-footer text-center">
            <div class="container">
                <p><a href="/">Fortech GPS</a> &copy; {{date('Y')}} Todos os direitos reservados.</p>
            </div><!-- /.container -->
        </div><!-- /.bottom-footer -->
    </footer><!-- /.site-footer -->
</div><!-- /.page-wrapper -->

<a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>
<!-- /.scroll-to-top -->
<script src="/site/js/jquery.js"></script>
<script src="/site/js/bootstrap.bundle.min.js"></script>
<script src="/site/js/owl.carousel.min.js"></script>
<script src="/site/js/waypoints.min.js"></script>
<script src="/site/js/jquery.counterup.min.js"></script>
<script src="/site/js/wow.js"></script>
<script src="/site/js/theme.js"></script>
<script src="//code-sa1.jivosite.com/widget/gS5KupgEMg" async></script>
@yield('js')
@yield('script')
</body>

</html>
