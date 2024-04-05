
@extends("baseChercheur")

@section('content')
    <!-- Page Content  -->
    <div class="p-4 p-md-5 pt-5">
        <h2 class="mb-5">PROFIL</h2>
        <div>
            <div class="d-flex flex-column flex-sm-column flex-lg-row mb-2 mt-2">
                <div class="col-lg-9">
                    <form action="" method="POST">
                        <div class="form-group row mb-4">
                            <label for="inputNom" class="col-sm-2 col-form-label">Nom</label>
                            <div class="col-lg-10 col-sm-12">
                                <input type="text" class="form-control" id="inputNom" name="nom">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="inputPrenom" class="col-sm-2 col-form-label">Prenom</label>
                            <div class="col-lg-10 col-sm-12">
                                <input type="text" class="form-control" id="inputPrenom" name="">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="inputEmail" class="col-sm-2 col-form-label">E-mail</label>
                            <div class="col-lg-10 col-sm-12">
                                <input type="email" class="form-control" id="inputEmail">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="d-flex flex-lg-column col-sm-4 col-lg-3 mb-sm-5 mb-5 mb-lg-0 flex-sm-row flex-md-row">
                    <div class="btn btn-outline-danger mb-lg-2 py-2">Modifier</div>
                    <div class="btn btn-outline-success ml-4 ml-lg-0 ml-sm-4">Appliquer</div>
                </div>
            </div>

            <h5>Changer de mot de passe</h5>
            <div class="">
                <div class="col-12 mt-4">
                    <div class="form-group row mb-4 mb-lg-5">
                        <label for="inputPassword" class="col-6 col-md-8 col-sm-8 col-lg-3 col-form-label">Mot de passe actuel</label>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <input type="password" class="form-control" id="inputPassword">
                        </div>
                    </div>

                    <div class="form-group row mb-4 mb-lg-5">
                        <label for="inputPasswordNew" class="col-8 col-md-8 col-sm-8 col-lg-3  col-form-label">Nouveau mot de passe</label>
                        <div class="col-12 col-sm-12 col-md-12 col-12 col-lg-6">
                            <input type="password" class="form-control" id="inputPasswordNew">
                        </div>
                    </div>

                    <div class="form-group row mb-4 mb-lg-5">
                        <label for="inputPasswordConfirm" class="col-8 col-lg-3 col-form-label">Confirmer le mot de passe</label>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <input type="password" class="form-control" id="inputPasswordConfirm">
                        </div>
                    </div>
                </div>
                <div class="btn btn-outline-success ml">Appliquer le changement</div>
            </div>
        </div>
    </div>

@endsection
