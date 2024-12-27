<!doctype html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" sizes="any" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <style>
        .custom-link {
            text-decoration: none;
            color: inherit; /* Keeps the original color */
        }

        .custom-link:hover {
            text-decoration: underline;
            color: inherit; /* Ensures color does not change on hover */
        }

        /* Style pour l'élément de menu actif */
        .active {
            color: #2a52be; /* Couleur personnalisée pour l'élément sélectionné */
            font-weight: bold; /* Optionnel : pour accentuer l'élément sélectionné */
        }


    /* Survol du champ de sélection */
    .select2-selection--multiple:hover {
        background-color: #fff9f9; /* Couleur de fond au survol */
    }

    /* Survol des options dans la liste déroulante */
    .select2-results__option:hover {
        background-color: #e5eaef; /* Couleur de fond au survol */
        color: white; /* Couleur du texte au survol */
    }



    </style>
</head>

<body>

    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar" class="bg-light">
            {{-- <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div> --}}
            <div class="p-4">
                <h1><a href="{{ route('visiteur.article') }}" class="logo text-decoration-none">Publi-lab</a></h1>
                <div class="mt-4">
                    <div>
                        <ul class="list-unstyled components mb-5">
                            <li class="mb-3">
                                <a href="{{ route('admin.espace') }}" class="{{ request()->routeIs('admin.espace') ? 'active' : '' }}">
                                    <span class="fas fa-tachometer-alt mr-3"></span>Dashboard
                                </a>
                            </li>
                            <li class="mb-3">
                                <a href="{{ route('admin.liste-articles') }}" class="{{ request()->routeIs('admin.liste-articles') ? 'active' : '' }}">
                                    <span class="fas fa-book mr-3"></span>Articles
                                </a>
                            </li>
                            <li class="mb-3">
                                <a href="{{ route('admin.listeRevue') }}" class="{{ request()->routeIs('admin.listeRevue') ? 'active' : '' }}">
                                    <span class="fas fa-list mr-3"></span>Revues
                                </a>
                            </li>
                            {{-- <li class="mb-3">
                                <a href="#" class="{{ request()->routeIs('associer.article-revue') ? 'active' : '' }}">
                                    <span class="fas fa-link mr-3"></span>Associer Article-Revue
                                </a>
                            </li> --}}
                            <li class="mb-3">
                                <a href="{{ route('admin.listeBaseIndexation') }}" class="{{ request()->routeIs('admin.listeBaseIndexation') ? 'active' : '' }}">
                                    <span class="fa-solid fa-house mr-3"></span>Bd indexation
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeChercheurs') }}" class="{{ request()->routeIs('admin.listeChercheurs') ? 'active' : '' }}">
                                    <span class="fas fa-user-graduate mr-3"></span>Chercheurs
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeGrade') }}" class="{{ request()->routeIs('admin.listeGrade') ? 'active' : '' }}">
                                    <span class="fas fa-medal mr-3"></span>Grades
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeDoctorant') }}" class="{{ request()->routeIs('admin.listeDoctorant') ? 'active' : '' }}">
                                    <span class="fas fa-user-graduate mr-3"></span>Doctorants
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeTheme') }}" class="{{ request()->routeIs('admin.listeTheme') ? 'active' : '' }}">
                                    <span class="fas fa-lightbulb mr-3"></span>Thèmes
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeAxeRecherche') }}" class="{{ request()->routeIs('admin.listeAxeRecherche') ? 'active' : '' }}">
                                    <span class="fas fa-project-diagram mr-3"></span>Axes de recherche
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeLaboratoires') }}" class="{{ request()->routeIs('admin.listeLaboratoires') ? 'active' : '' }}">
                                    <span class="fas fa-microscope mr-3"></span>Laboratoires
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeUmris') }}" class="{{ request()->routeIs('admin.listeUmris') ? 'active' : '' }}">
                                    <span class="fas fa-flask mr-3"></span>UMRIS
                                </a>
                            </li>

                            <li class="mb-3">
                                <a href="{{ route('admin.listeEdp') }}" class="{{ request()->routeIs('admin.listeEdp') ? 'active' : '' }}">
                                    <span class="fas fa-university mr-3"></span>EDP
                                </a>
                            </li>
                        </ul>


                        {{-- <li class="mb-3"><a href="{{ route('admin.publier-article') }}"><span class="fas fa-feather-alt mr-3"></span>Enregistrer un Article</a></li> --}}
                        {{-- <li class="mb-3"><a href="{{ route('admin.enregistrer-revue-formulaire') }}"><span class="fas fa-journal-whills mr-3"></span>Enregistrer une Revue</a></li> --}}

                        {{-- <li class="d-flex flex-column justify-content-center justify-center">
                            <p>{{ Auth::guard('admin')->user()->nom }} - Admin</p>
                        </li> --}}

                    </div>
                    <div>
                        <ul class="footer-sidebar d-flex flex-column">
                            <li>
                                <a href="#" class="text-decoration-none">
                                    <span class="fas fa-cog mt-4"></span>
                                    <span class="parameter-label ml-2">Paramètre</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('logout') }}" class=" text-decoration-none">
                                    <span class="fas fa-sign-out-alt"></span>
                                    <span class="logout-label ml-2">Se Déconnecter</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div id="content" class="@yield('bg-content')">
            @yield('content')
        </div>

    </div>

    <script src="{{ asset('assets/bootstrap/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/select2/select2.min.js') }}"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

    <!-- Section des scripts (en bas de la page pour les performances) -->
    @yield('scripts') <!-- Cette ligne permet d'inclure les scripts spécifiques à chaque vue -->

</body>
</html>
