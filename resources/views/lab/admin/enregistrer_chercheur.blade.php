@extends("baseAdmin")

@section('content')
    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-5">Enregistrer chercheur</h2>
        <div>
            <div class="col-12">
                <form action="{{route('register')}}" method="POST">
                    @csrf
                    <div class="form-group row mb-4">
                        <label class="col-sm-3" for="nom">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nom" name="nom">
                            <small id="error-nom" class="form-text text-danger"></small>
                        </div>
                        @error('nom')
                            <div class="error"><small id="error-nom" class="form-text text-danger"></small></div>
                        @enderror
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-sm-3" for="prenom">prenom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="prenom" name="prenom">
                            <small id="error-prenom" class="form-text text-danger"></small>
                        </div>
                        @error('prenom')
                            <div class="error"><small id="error-prenom" class="form-text"></small></div>
                        @enderror
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-sm-3" for="email">E-mail</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email-name" autocomplete="email">
                            <small id="error-email" class="form-text text-danger"></small>
                        </div>

                        @error('email')
                            <div class="error"><small id="error-email" class="form-text"></small></div>
                        @enderror
                    </div>


                    <div class="form-group row mb-4">
                        <label class="col-sm-3" for="contact">Contact</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="contact" name="contact">
                            <small id="error-contact" class="form-text text-danger"></small>
                        </div>

                        @error('contact')
                            <div class="error"><small id="error-contact" class="form-text"></small></div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="btn btn-outline-success ml-3 mt-5 col-12 btn-submit" id="submit-enregistrer-chercheur">Enregistrer le chercheur</div>
        </div>
    </div>
@endsection

