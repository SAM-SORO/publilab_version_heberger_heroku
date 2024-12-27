<!doctype html>
<html lang="en">
<head>
<title>chercheur</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/app.css')}}">
<link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
<link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" sizes="any" type="image/x-icon">
<link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">

<style>
    .custom-link {
            text-decoration: none;
            color: inherit; /* Keeps the original color */
        }

    .custom-link:hover {
        text-decoration: underline;
        color: inherit; /* Ensures color does not change on hover */
    }

    /* Ajouter une couleur ou un style distinct pour l'élément actif */
    .active {
        color: #007bff; /* Couleur bleue par exemple */
        font-weight: bold;
        /* text-decoration: underline; Optionnel: ajouter un soulignement pour plus de visibilité */
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
                        <ul class="list-unstyled components mb-5">
                            <li class="mb-3">
                                <a href="{{ route('chercheur.espace') }}" class="{{ request()->routeIs('chercheur.espace') ? 'active' : '' }}">
                                    <span class="fas fa-book-open mr-3"></span>Mes articles
                                </a>
                            </li>
                            <li class="mb-3">
                                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                    <span class="fas fa-home mr-3"></span>Page de visite
                                </a>
                            </li>
                            <li class="mb-3">
                                <a href="{{ route('chercheur.profil') }}" class="{{ request()->routeIs('chercheur.profil') ? 'active' : '' }}">
                                    <span class="fas fa-user mr-3"></span>Profil
                                </a>
                            </li>
                        </ul>


                        {{-- <li class="mb-3">
                            <a href="{{ route('chercheur.publierArticle') }}">
                                <span class="fas fa-pencil-alt mr-3"></span>Enregistrer un article
                            </a>
                        </li> --}}
                        {{-- <li class="mb-3">
                            <a href="{{ route('chercheur.associer-article-revue') }}">
                                <span class="fas fa-link mr-3"></span>Articles publier
                            </a>
                        </li> --}}

                        <!-- <ul class="list-unstyled components mb-5">
                        <li class="mb-3"><a href="route('chercheur.publierArticle'"><span class="fas fa-feather-alt mr-3"></span>Enregistrer un article</a></li>
                        {{-- <li class="mb-3"><a href="{{route('chercheur.enregistrerRevueFormulaire')}}"><span class="fas fa-journal-whills mr-3"></span>Enregistrer une revue</a></li> --}}
                        {{-- <li class="mb-3"><a href="{{route('chercheur.listeRevues')}}"><span class="fas fa-list mr-3"></span>Liste des Revues</a></li> --}}
                        <li class="mb-3"><a href="route('chercheur.associer-article-revue')"><span class="fas fa-link mr-3"></span>Associer article-revue</a></li>
                    </ul>  -->

                    </ul>



                    <ul class="footer-sidebar d-flex flex-column">
                        {{-- <!-- Affichage du nom de l'utilisateur connecté, avec word wrapping activé -->
                        <li class="user-name mt-5 text-left">
                            <span class="user-name-label" style="word-wrap: break-word;">User: {{ Auth::user()->nomCherch }}</span>
                        </li> --}}

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



<script src={{asset('assets/bootstrap/jquery-3.7.1.min.js')}}></script>
<script src={{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/select2/select2.min.js') }}"></script>

<!-- Section des scripts (en bas de la page pour les performances) -->
@yield('scripts') <!-- Cette ligne permet d'inclure les scripts spécifiques à chaque vue -->

{{-- <script src="js/popper.js"></script> --}}
</body>
</html>



{{--
    #sidebar {
        position: fixed;
        /* width: 250px; */
        /* overflow-x: auto; */
        /* padding-top: 20px;
        z-index: 1000; */


    #content {
        /* margin-left: 250px; Same width as the sidebar */
        /* padding: 20px; */
        /* width: calc(100% - 450px); */
        /* overflow-y: auto; */
    }


    <li class="mb-3">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
        <span class="fas fa-user-graduate mr-3"></span>Doctorants
    </a>
    </li>
    <li class="mb-3">
        <a href="{{ route('grades') }}" class="{{ request()->routeIs('grades') ? 'active' : '' }}">
            <span class="fas fa-medal mr-3"></span>Grades
        </a>
    </li>
    <li class="mb-3">
        <a href="{{ route('edp') }}" class="{{ request()->routeIs('edp') ? 'active' : '' }}">
            <span class="fas fa-university mr-3"></span>EDP
        </a>
    </li>
    <!-- Répétez cette structure pour chaque élément de menu -->





    --}}
