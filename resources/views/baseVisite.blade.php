<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('fontawesome-free-6.5.1-web/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('assets/app.css')}}">
    <style>
        .custom-height {
            height: 86vh;
        }
        .custom-article-height{
            margin-top: 70px !important;
        }

        .foot{
            margin-top: -50px;
        }
    </style>
</head>
<body>
    <div class="mb-5">
        <nav class="navbar navbar-expand-md fixed-top navbar-light px-4 py-3 mb-5 ">
            <div class="col-3">
                <a class="navbar-brand" href="#"><span class="text-success">Publi</span><span class="text-dark">lab</span></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav w-50 justify-content-around">
                    <a class="nav-link @if(Request::route()->getName() == 'home') font-weight-bold active custom-nav @endif " href="{{route('home')}}">Accueil</a>
                    <a class="nav-link @if(Request::route()->getName() == 'visiteur.article') font-weight-bold active custom-nav @endif" href="{{route('visiteur.article')}}">Articles</a>
                    @yield('details')
                    @yield('Authentification')
                    <a class="nav-link font-weight-bold d-none" href="#">Details</a>
                </div>

                @guest
                    {{-- doit s'afficher que si l'utilisateur n'est pas authentifier --}}
                    <div class="text-center">
                        <a href="{{route('login')}}" type="button" class="btn btn-outline-success mr-5">S'authentifier</a>
                    </div>
                @endguest

            </div>

            @auth
                {{-- doit si l'utilisateur est authentifier --}}
                <div class="btn-group mr-4">
                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    {{Auth::user()->name}}
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item " href="{{route('app_deconnexion')}}">Se deconnecter</a>
                    </div>
                </div>
            @endauth

            {{-- <a href="{{route('chercheur.profil') }}" class="ml-3"><img src="{{asset('img/WhatsApp Image 2024-02-26 à 19.58.41_cd0f47c4.jpg')}}" alt="Nom de l'utilisateur" class="rounded-circle img-fluid" width="35" height="50px">
            </a> --}}
        </nav>
    </div>

    <main>
        @yield('contenue-main')
    </main>

    @if (!isset($excludeFooter) || !$excludeFooter)
        <footer>
            <div class="container-fluid bg-white py-3 text-center foot mt-5">
                <div>
                    &copy; Copy right By INPHB tous droit reserver
                </div>
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-bell"></i>
            </div>
        </footer>
    @endif


    <script src={{asset('assets/bootstrap/jquery-3.7.1.min.js')}}></script>
	<script src={{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}></script>
    <script src={{asset('assets/js/main.js')}}></script>

</body>
</html>
