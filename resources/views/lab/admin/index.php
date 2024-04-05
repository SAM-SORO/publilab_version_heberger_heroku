@php
    $excludeFooter = true;
@endphp

@extends('baseVisite')

@section('bg-nav', 'bg-white')

@section('title','Inscription')

@section('Authentification')
<a class="nav-link font-weight-bold active" href="#">Authentification</a>
@endsection



@section ('contenue-main')
    <div class="container-fluid bg-light d-flex align-items-center justify-content-center mt-1 sign-height">
        <form action="" class="shadow-lg p-3 bg-white rounded autocomplete-off col-12 col-sm-10 col-md-9 col-lg-4 mt-5 mt-sm-5  mt-md-5">

            <div class="form-group w-100">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom">
                @error('nom')
                    <div class="error"><small id="error-nom" class="form-text text-muted">{{message}}</small></div>
                @enderror
            </div>

            <div class="form-group w-100">
                <label for="prenom">prenom</label>
                <input type="text" class="form-control" id="prenom" name="prenom">
                @error('prenom')
                    <div class="error"><small id="error-prenom" class="form-text text-muted">{{message}}</small></div>
                @enderror
            </div>

            <div class="form-group w-100">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email-name" autocomplete="email">

                @error('email')
                    <div class="error"><small id="error-email" class="form-text text-muted">{{message}}</small></div>
                @enderror
            </div>


            <div class="form-group w-100">
                <label for="contact">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact">
                @error('contact')
                    <div class="error"><small id="error-contact" class="form-text text-muted">{{message}}</small></div>
                @enderror
            </div>

            <div class="form-group w-100">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" autocomplete="current-password">
                @error('password')
                    <div class="error"><small id="error-password" class="form-text text-muted">{{ $message }}</small></div>
                @enderror
            </div>

            <div class="form-group w-100 mb-4">
                <label for="confirm-password">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" autocomplete="new-password">
                @error('confirm-password')
                    <div class="error"><small id="confirm-password" class="form-text text-muted">{{ $message }}</small></div>
                @enderror
            </div>

            <div class="w-100 mb-4">
                <button type="submit" class="btn w-100 soumettre" style="background-color: #9b59b6; color: white; border:#9b59b6">S'inscrire</button>
            </div>

            <div class="w-100">
                Dejà un compte? <a href="{{route('login')}}">Se connecter</a>
            </div>
        </form>

        {{-- style="background-color: #9b59b6; color: white; border:#9b59b6 !important; --}}

    </div>
@endsection
