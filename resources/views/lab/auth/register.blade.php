@php
    $excludeFooter = true;
@endphp

@extends('baseVisite')

@section('title', 'Register')

@section('Authentification')
    <a class="nav-link font-weight-bold active" href="#">Authentification</a>
@endsection

@section('contenue-main')


    <div class="sign-height mx-auto justify-content-center">

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show col-10 col-lg-4 col-sm-8 col-md-9 mx-auto" style="margin-bottom: -20px; margin-top: 40px" role="alert" id="alert-danger-login">
                {{Session::get('error')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif


        <div class="container-fluid d-flex align-items-center justify-content-center sign-height">

            <form method="POST" action="{{ route('submitRegister') }}" id="form-inscription" class="shadow-lg p-3 bg-white rounded autocomplete-off col-12 col-sm-10 col-md-9 col-lg-5 mt-5">
                <div class="text-center"><h2>Inscription</h2></div><hr>
                @csrf

                <!-- Type d'utilisateur -->
                <div class="form-group w-100 mb-1">
                    <div class="d-flex justify-content-center">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio"
                                id="type_chercheur"
                                name="type_compte"
                                value="chercheur"
                                class="custom-control-input @error('type_compte') is-invalid @enderror"
                                {{ old('type_compte', 'chercheur') == 'chercheur' ? 'checked' : '' }}
                                required>
                            <label class="custom-control-label cursor-pointer" for="type_chercheur">Chercheur</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio"
                                id="type_doctorant"
                                name="type_compte"
                                value="doctorant"
                                class="custom-control-input @error('type_compte') is-invalid @enderror"
                                {{ old('type_compte') == 'doctorant' ? 'checked' : '' }}
                                required>
                            <label class="custom-control-label cursor-pointer" for="type_doctorant">Doctorant</label>
                        </div>
                    </div>
                    @error('type_compte')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Nom -->
                <div class="form-group w-100 mt-2">
                    <label for="nom">Nom <span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control @error('nom') is-invalid @enderror"
                        id="nom"
                        value="{{ old('nom') }}"
                        name="nom"
                        required>
                    @error('nom')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group w-100">
                    <label for="email">E-mail <span class="text-danger">*</span></label>
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        value="{{ old('email') }}"
                        name="email"
                        required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="form-group w-100 mb-4">
                    <label for="password">Mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="toggle-password1">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group w-100 mb-4">
                    <label for="password_confirmation">Confirmer le mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="toggle-password2">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="w-100 mb-4">
                    <button type="submit" class="btn w-100 soumettre" style="background-color: #9b59b6; color: white; border:#9b59b6">
                        S'inscrire
                    </button>
                </div>

                <div class="w-100">
                    Déjà un compte? <a href="{{ route('login') }}">Se connecter</a>
                </div>
            </form>
        </div>
    </div>

    {{-- <div class="d-flex justify-content-center">
        <div class="col-12 col-sm-10 col-md-9 col-lg-5 mt-5">
            <p class="text-center">En vous inscrivant, vous acceptez nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a>.</p>
        </div>
    </div> --}}

    <script>
        document.querySelector('.navbar').classList.add('bg-light');
    </script>
@endsection
