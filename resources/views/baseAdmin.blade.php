
<!doctype html>
<html lang="en">
<head>
<title>chercheur</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{asset('assets/app.css')}}">
</head>
<body>

    <div class="wrapper d-flex align-items-stretch">
		<nav id="sidebar">
			<div class="custom-menu">
				<button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
				</button>
			</div>
			<div class="p-4">
				<h1><a href="index.html" class="logo">Publi-lab</a></h1>
				<ul class="list-unstyled components mb-5">
					{{-- <li class="mb-3"><a href="{{route('article.chercheur')}}"><span class="fas fa-book mr-3"></span>Chercheur</a></li>
					<li class="mb-3"><a href="{{route('publier')}}"><span class="fas fa-feather-alt mr-3"></span>Enregistrer chercheur</a></li>
					<li class="mb-3"><a href="{{route('home')}}"><span class="fa-solid fa-house mr-3"></span>Page de visite</a></li>
					<li class="mb-3"><a href="{{route('admin.profil')}}"><span class="fa-regular fa-user mr-3"></span> Profil</a></li>
                    <li class=" d-flex flex-column">
                        <a href="{{route('admin.profil') }}" class="ml-4"><img src="{{asset('img/WhatsApp Image 2024-02-26 à 19.58.41_cd0f47c4.jpg')}}" alt="Nom de l'utilisateur" class="rounded-circle img-fluid" width="35" height="50px">
                        </a> --}}
                        <p>Soro samuel - Admin</p>
                    {{-- </li> --}}
				</ul>
                <div>
                    <ul class="footer-sidebar d-flex flex-column">
                        <li>
                            <a href="#">
                                <span class="fas fa-cog mt-4"></span>
                                <span class="parameter-label ml-2">Paramètre</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="#">
                                <span class="fas fa-sign-out-alt"></span>
                                <span class="logout-label ml-2">Se déconnecter</span>
                            </a>
                        </li>
                    </ul>
                </div>
			</div>
		</nav>

        <div id="content">
            @yield('content')
        </div>

    </div>

<script src={{asset('assets/bootstrap/jquery-3.7.1.min.js')}}></script>
<script src={{asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}></script>
<script src={{asset('assets/js/main.js')}}></script>

{{-- <script src="js/popper.js"></script> --}}
</body>
</html>
