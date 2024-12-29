<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('fontawesome-free-6.5.1-web/css/all.css')}}">
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" sizes="any" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
        /* Styles pour la landing page */
        .landing-header {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4B0082;
        }

        .custom-link {
            text-decoration: none !important;
            cursor: default !important;
        }


        .landing-description {
            font-size: 1.2rem;
            color: #333;
            margin-top: 15px;
            line-height: 2; /* Espace entre les lignes */
        }

        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            width: 150px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card.green { background-color: #28a745; color: white; }
        .card.blue { background-color: #007bff; color: white; }
        .card.red { background-color: #dc3545; color: white; }
        .card.orange { background-color: #fd7e14; color: white; }
        .card.purple { background-color: #6f42c1; color: white; }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .image-section {
            display: flex; /* Utilisez Flexbox pour le positionnement */
            justify-content: center; /* Centre le contenu horizontalement */
            align-items: center; /* Centre le contenu verticalement */
            height: 100%; /* Assurez-vous que la section prend de la hauteur */
            overflow: hidden; /* Pour masquer les débordements si besoin */
        }

        .animated-image {
            width: 100%; /* S'assurer que l'image occupe toute la largeur de la section */
            max-width: 500px; /* Vous pouvez ajuster la taille maximale selon vos besoins */
            border-radius: 15px; /* Ajoute des coins arrondis pour un look plus moderne */
            transition: transform 0.3s ease; /* Animation pour l'effet de transformation */
        }

        .animated-image:hover {
            transform: scale(1.05); /* L'image s'agrandit légèrement au survol */
        }

    </style>
</head>

<body class= @yield("bg-color")>
    <div class="">
        <nav class="navbar navbar-expand-md fixed-top navbar-light px-4 py-3">
            <div class="col-3 mr-5">
                <a class="navbar-brand" href="/"><span class="text-success">Publi</span><span class="text-dark">lab</span></a>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav w-50 justify-content-around">

                    <a class="nav-link @if(Request::route()->getName() == 'home') font-weight-bold active custom-nav @endif " href="{{route('home')}}">Accueil</a>

                    <a class="nav-link @if(Request::route()->getName() == 'visiteur.article') font-weight-bold active custom-nav @endif" href="{{route('visiteur.article')}}">Articles</a>

                    <a class="nav-link font-weight-bold d-none" href="logout">Details</a>
                </div >

                {{-- Affichage du bouton d'authentification si l'utilisateur n'est pas authentifié --}}
                @if(!Auth::guard('visiteur')->check() && !Auth::guard('chercheur')->check() && !Auth::guard('admin')->check())
                    <div class="text-center mr-5" id="btnAuthification">
                        <a href="{{route('login')}}" class="btn btn-outline-success btn_authentifie mr-5">SE CONNECTER</a>
                    </div>
                @endif

            </div>

            {{-- Affichage des informations utilisateur si l'utilisateur est authentifié --}}
            @if(Auth::guard('visiteur')->check())
                <div class="text-center mr-5" style="padding-right: 5%">
                    {{ Auth::guard('visiteur')->user()->nom }}
                    <div class="btn-group mr-4">
                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            {{-- Affichage du nom de l'utilisateur ou autre information pertinente --}}
                        </button>
                        <div class="dropdown-menu">
                            <form action="{{ route('logout') }}" method="GET">
                                @csrf
                                <button type="submit" class="dropdown-item">Se déconnecter</button>
                            </form>
                        </div>

                    </div>
                </div>
            @elseif(Auth::guard('chercheur')->check())
                <div class="text-center mr-5 pr-5">
                    {{ Auth::guard('chercheur')->user()->nomCherch . " " . Auth::guard('chercheur')->user()->prenomCherch }}
                    <div class="btn-group mr-5">
                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            {{-- Affichage du nom de l'utilisateur ou autre information pertinente --}}
                        </button>
                        <div class="dropdown-menu mr-5">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Se déconnecter</button>
                            </form>
                            <a href="{{ route('chercheur.espace') }}" class="dropdown-item">Mon espace</a>
                        </div>
                    </div>

                </div>

            {{-- si c'est un admin qui est connecter --}}
            @elseif(Auth::guard('admin')->check())
                <div class="text-center mr-5 pr-5">
                    {{ Auth::guard('admin')->user()->nom . " " . Auth::guard('admin')->user()->prenom }}
                    <div class="btn-group mr-4">
                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <a type="submit" class="dropdown-item">Se déconnecter</a>
                            </form>
                            <a href="{{ route('admin.espace') }}" class="dropdown-item">Mon espace</a>
                        </div>
                    </div>
                    {{-- <a href="{{ route('admin.profil') }}" class="ml-3 mr-5">
                        <img src="{{ asset('img/WhatsApp Image 2024-02-26 à 19.58.41_cd0f47c4.jpg')}}" alt="Nom de l'utilisateur" class="rounded-circle img-fluid" width="35" height="50px">
                    </a> --}}

                </div>
            @endif

        </nav>
    </div>

    <main class="mt-5 @yield('bg-content')">
        @yield('contenue-main')
    </main>


    @if (!isset($excludeFooter) || !$excludeFooter)
        <div style="margin-bottom: 2rem;">
            <footer class="bg-light text-dark py-4">
                <div class="container">
                    <div class="row justify-content-between">
                        <!-- Partie gauche -->
                        <div class="col-12 col-md-4 d-flex flex-wrap justify-content-center justify-content-md-start align-items-center mb-4 mb-md-0">
                            {{-- <img src="{{ asset('assets/img/ministere.png') }}" alt="Logo 1" style="width: 200px; margin: 5px;"> --}}
                            <img src="{{ asset('assets/img/logo_inp_rm.png') }}" alt="Logo 2" style="width: 70px; margin: 5px;">
                            <img src="{{ asset('assets/img/logo_esi_rm.png') }}" alt="Logo 3" style="width: 80px; margin: 5px;">
                            <!-- Ajouter d'autres images si nécessaire -->
                        </div>


                        <!-- Partie centrale -->
                        <div class="col-12 col-md-4 d-flex flex-column align-items-center text-center mb-4 mb-md-0">
                            <h5>À propos de l'application</h5>
                            <p>Notre application vous permet de gérer vos opérations facilement et efficacement. Profitez de fonctionnalités avancées pour améliorer votre productivité.</p>
                        </div>

                        <!-- Partie droite -->
                        <div class="col-12 col-md-4 d-flex justify-content-center">
                            <div class="d-flex flex-column align-items-center align-items-md-start text-center text-md-start">
                                <h5>Contact</h5>
                                <p>Email : contact@votreapp.com</p>
                                <p>Téléphone : +33 1 23 45 67 89</p>
                                <a href="http://www.inphb.ci" target="_blank">INPHB web site</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
</html>
