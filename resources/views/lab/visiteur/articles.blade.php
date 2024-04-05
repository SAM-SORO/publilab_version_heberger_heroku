@extends('baseVisite')

{{-- titre de la page --}}
@section('title', 'Publilab-Articles')

{{-- Articles --}}

@section('contenue-main')
    <div class="custom-article-height">
        <div class="container mt-3">
            <form class="form-inline justify-content-center my-2" action="{{ route('recherche.article') }}" method="POST">
                <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" placeholder="Rechercher un article" aria-label="Rechercher">
                <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
            </form>
        </div>

        <div class="container d-flex mt-5 align-items-center">
            <ul><li class="text-secondary col-5 ml-5">Annee</li></ul>
            <div class="d-flex col-10">
                <select title=" filtre par annee" class="custom-select col-4 col-lg-2 col-sm-6 col-md-3" id="inputGroupSelect01">
                    <option selected>Tous</option>
                    @foreach ($annees as $annee)
                        <option value="{{$annee}}">{{$annee}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="container mb-5">
            @foreach($articles as $article)
                <div class="row flex-column mt-5 mb-4 article mx-5 border p-3">
                    <div class="text-primary"> <h2>{{ $article->titre_art }}</h2></div>
                    <div class="mt-2" >{{ Str::limit($article->desc_art, 100) }}</div>

                    <div class="mt-2" >Publié le : {{ $article->date_publication }}</div>

                    {{-- Afficher les informations sur le chercheur --}}
                    <div class="mt-2">par : {{$article->chercheur_nom }} {{$article->chercheur_prenom}}</div>

                    <div class="row justify-content-end mt-2 pr-4 py-3">
                        <button type="button" class="btn btn-danger">Telecharger</button>
                        <button type="button" class="btn btn-info ml-4">Voir plus</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        document.querySelector('.navbar').classList.add('bg-light');
        document.querySelector('.navbar').classList.add('custom-nav');

    </script>
@endsection

{{-- Script pour ajouter la classe "active" et le font bold au menu actuelle  --}}

