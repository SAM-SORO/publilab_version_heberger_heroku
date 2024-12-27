@php
    $excludeFooter = true;
@endphp

@extends('baseVisite')

@section('title', 'Register')

@section('Authentification')
    <a class="nav-link font-weight-bold active" href="#">Authentification</a>
@endsection

@section('contenue-main')
    <div class="container-fluid bg-light d-flex align-items-center justify-content-center mt-1 sign-height">

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show col-10 col-lg-4 col-sm-8 col-md-9 mt-3 mx-auto" role="alert" id="alert-danger-login">
                {{Session::get('error')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('submitRegister') }}" id="form-inscription" class="shadow-lg p-3 bg-white rounded autocomplete-off col-12 col-sm-10 col-md-9 col-lg-5 mt-5">

            <div class="text-center"><h2>Inscription</h2></div><hr>
            @csrf
            <div class="form-group w-100 mt-2">
                <label for="nom">Nom d'utilisateur</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" value="{{ old('nom') }}" name="nom" required>
                <small id="error-nom" class="form-text text-danger">@error('nom') {{ $message }} @enderror</small>
            </div>

            <div class="form-group w-100">
                <label for="email">E-mail</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" name="email" required>
                <small id="error-email" class="form-text text-danger">@error('email') {{ $message }} @enderror</small>
            </div>

            <div class="form-group w-100 mb-4">
                <label for="password">Mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="toggle-password1">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <small id="error-password" class="form-text text-danger">@error('password') {{ $message }} @enderror</small>
            </div>

            <div class="form-group w-100 mb-4">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="toggle-password2">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <small id="error-confirmPassword" class="form-text text-danger"></small>
            </div>

            <div class="w-100 mb-4">
                <button type="submit" class="btn w-100 soumettre" style="background-color: #9b59b6; color: white; border:#9b59b6" id="submit-inscription">S'inscrire</button>
            </div>

            <div class="w-100">
                Déjà un compte? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('.navbar').classList.add('bg-light');
    </script>
@endsection
