<!doctype html>
<html lang="en">
<head>
<title>doctorant</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

<title>@yield('title')</title>

<!-- Import des fichiers CSS -->
<link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
<link rel="stylesheet" href="{{ mix('assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ mix('assets/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ mix('assets/css/bootstrap.min.css') }}">

<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" sizes="any" type="image/x-icon">
<link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">

<style>
    .custom-link {
        text-decoration: none;
        color: inherit;
    }

    .custom-link:hover {
        text-decoration: underline;
        color: inherit;
    }

    .active {
        color: #007bff;
        font-weight: bold;
    }
</style>

</head>
<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar" class="bg-light">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <div class="p-4">
                <h1><a href="{{route('visiteur.article')}}" class="logo text-decoration-none">Publi-lab</a></h1>
                <div class="mt-4">
                    <ul class="list-unstyled components mb-5">
                        <li class="mb-3">
                            <a href="{{ route('doctorant.espace') }}" class="{{ request()->routeIs('doctorant.espace') ? 'active' : '' }}">
                                <span class="fas fa-tachometer-alt mr-3"></span>Dashboard
                            </a>
                        </li>

                        <li class="mb-3">
                            <a href="{{ route('doctorant.listeArticles') }}" class="{{ request()->routeIs('doctorant.listeArticles') ? 'active' : '' }}">
                                <span class="fas fa-book-open mr-3"></span>Mes articles
                            </a>
                        </li>

                        <li class="mb-3">
                            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                <span class="fas fa-home mr-3"></span>Page de visite
                            </a>
                        </li>

                        <li class="mb-3">
                            <a href="{{ route('doctorant.profil') }}" class="{{ request()->routeIs('doctorant.profil') ? 'active' : '' }}">
                                <span class="fas fa-user mr-3"></span>Profil
                            </a>
                        </li>
                    </ul>

                    <ul class="footer-sidebar d-flex flex-column">
                        <li><a href="#" class="text-decoration-none"><span class="fas fa-cog mt-4"></span><span class="parameter-label ml-2">Paramètre</span></a></li>
                        <li><a href="{{ route('logout') }}" class="text-decoration-none"><span class="fas fa-sign-out-alt"></span><span class="logout-label ml-2">Se déconnecter</span></a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="content" class="@yield('bg-content')">
            @yield('content')
        </div>
    </div>

    <!-- Import des fichiers JS -->
    <script src="{{ mix('assets/js/jquery.min.js') }}"></script>
    <script src="{{ mix('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="{{ mix('assets/js/app.js') }}"></script>

    <!-- Section des scripts -->
    @yield('scripts')
</body>
</html>
