<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PubliLab-Page de Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('fontawesome-free-6.5.1-web/css/all.css')}}">
    <style>
        .custom-height {
            height: 88vh;
            /* background-image: url("{{asset('img/publab Acceuil.jpg')}}");
            background-size: cover;
            background-repeat: no-repeat */
        }
        .foot{
            margin-top: -20px;
        }
    </style>
</head>
<body class="">
    <nav class="navbar navbar-expand-md fixed-top navbar-light bg-white px-4 py-3">
        <div class="col-3">
            <a class="navbar-brand" href="#"><span class="text-success">Publi</span><span class="text-dark">lab</span></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <div class="navbar-nav mx-4">
                <a class="nav-link active font-weight-bold" href="#">Accueil</a>
                <a class="nav-link  active font-weight-bold" href="#">Recherches</a>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-outline-success mr-5 d-none d-md-block">Se connecter</button>
            </div>
        </div>

    </nav>

    <div class="container-fluid bg-light d-flex align-items-center justify-content-center mt-5  mt-sm-5  mt-md-5 custom-height">
        <form action="" class="shadow-lg p-3 mb-5 bg-white rounded autocomplete-off col-12 col-sm-10 col-md-9 col-lg-4 mt-5 mt-sm-5  mt-md-5">
            <div class="form-group">
                <label for="nom">Email</label>
                <input type="email" class="form-control" id="nom">
            </div>
            <div class="form-group">
                <label for="prenom">Mot de passe</label>
                <input type="password" class="form-control" id="prenom">
            </div>
            <div class="row p-3 justify-content-between">
                <div class="form-check mb-2 mr-sm-2">
                    <input class="form-check-input" type="checkbox" id="inlineFormCheck">
                    <label class="form-check-label" for="inlineFormCheck"> Se souvenir de moi</label>
                </div>
                <div>
                    <a href="#">Mot de passe oublié !</a>
                </div>
            </div>
            <button type="submit" class="btn mb-2 w-100"  style="background-color: #9b59b6; color: white; border:#9b59b6 !important;">Se connecter</button>
            <div class="my-2 text-center"> Pas de compte ? <a href="#" class="ml-2"> S'inscrire</a></div>
        </form>
    </div>

    <footer>
        <div class="container-fluid bg-white py-3 text-center foot">
            <div>
                &copy; Copy right By INPHB tous droit reserver
                <div><i class="fas fa-bell"></i></div>
                <i class="fas fa-bell"></i>
                <i class="fas fa-bell"></i>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>
