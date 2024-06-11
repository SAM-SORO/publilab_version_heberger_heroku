@php
    $excludeFooter = true;
@endphp

@extends('baseVisite')

@section('title','Register')

@section('Authentification')
    <a class="nav-link font-weight-bold active" href="#">Authentification</a>
@endsection


@section ('contenue-main')
    <div class="container-fluid bg-light d-flex align-items-center justify-content-center mt-1 sign-height">
        <form method="POST" action="{{route('register')}}" id="form-inscription" class="shadow-lg p-3 bg-white rounded autocomplete-off col-12 col-sm-10 col-md-9 col-lg-5 mt-5 mt-sm-5  mt-md-5">
            <div class="text-center"><h2>Inscription</h2></div><hr>
            @csrf
            <div class="form-group w-100 mt-2">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" value="{{old('username')}}" name="username" required>
                <small id="error-username" class="form-text text-danger"></small>
                @error('username')
                    <div class="error"><small id="error-username" class="form-text text-danger"></small></div>
                @enderror
            </div>

            <div class="form-group w-100">
                <label for="email">E-mail</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{old('email')}}" name="email"  required>
                {{-- url-emailExist='{{route('app_exist_email')}}' token ={{'csrf_to()'}}  --}}
                <small id="error-email" class="form-text text-danger"></small>
                @error('email')
                    <div class="error"><small id="error-email" class="form-text text-danger"></small></div>
                @enderror
            </div>


            <div class="form-group W-100 mb-4">
                <label for="password">Mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="toggle-password1">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <small id="error-password" class="form-text text-danger"></small>
                @error('password')
                    <div class="error"><small id="error-password" class="form-text text-danger"></small></div>
                @enderror
            </div>

            <div class="form-group W-100 mb-4">
                <label for="confirm-password">Confirmer le mot de Passe</label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="confirm-password" name="confirm-password" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="toggle-password2">
                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <small id="error-confirmPassword" class="form-text text-danger"></small>
                @error('password')
                    <div class="error"><small id="confirm-email" class="form-text text-danger"></small></div>
                @enderror
            </div>

            <div class="w-100 mb-4">
                <button type="submit" class="btn w-100 soumettre" style="background-color: #9b59b6; color: white; border:#9b59b6" id="submit-inscription">S'inscrire</button>
            </div>

            <div class="w-100">
                Dejà un compte? <a href="{{route('login')}}">Se connecter</a>
            </div>
        </form>

        {{-- style="background-color: #9b59b6; color: white; border:#9b59b6 !important; --}}

    </div>

    <script>
        document.querySelector('.navbar').classList.add('bg-light');
    </script>
@endsection
