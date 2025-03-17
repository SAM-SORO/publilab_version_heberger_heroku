@extends('baseVisite')

{{-- titre de la page --}}
@section('title', 'Publilab')
@section('bg-color', 'bg-light')


@section('contenue-main')


<!-- Main landing page content -->
<section class="mt-5 mb-5">
    <div class="container custom-height mt-md-5 d-flex flex-column flex-md-row align-items-center">
        <!-- Texte à gauche -->
        <div class="text-section w-100 w-md-50 pr-md-5 mb-4 mb-md-0 mt-md-5">
            <h1 class="landing-header">PubliLab : Découvrez les articles des chercheurs de l'INP</h1>
            <p class="landing-description">
                PubliLab est une plateforme de l'INPHB dédiée au référencement des articles de recherche publiés dans les revues scientifiques et rédigés par ses chercheurs.
            </p>
            <!-- Bouton "Voir Les Publications" -->
            <a href="{{ route('visiteur.article') }}" class="btn btn-primary mt-3 text-center">Voir les publications</a>
        </div>


        <!-- Image à droite -->
        <div class="image-section w-100 w-md-50 text-center mb-md-0">
            <img src="{{ asset('assets/img/R.jpg') }}" alt="Image description" class="img-fluid animated-image">
        </div>
    </div>

</section>

<div style="margin-bottom: 130px;"></div>

<!-- Section des articles récents -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-4">Publications récentes</h2>

        @if($articles->isEmpty())
            <div class="alert alert-info text-center">
                Aucune publication récente disponible.
            </div>
        @else

            <div class="row">
                @foreach($articles as $article)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 w-100 shadow-sm bg-white">
                            <div class="card-body">
                                <h5 class="card-title">{{ $article->titreArticle }}</h5>
                                <p class="card-text text-muted mb-2">
                                    <!-- Auteurs -->
                                    <small>
                                        @foreach($article->chercheurs as $chercheur)
                                            {{ $chercheur->prenomCherch }} {{ strtoupper($chercheur->nomCherch) }}@if(!$loop->last),@endif
                                        @endforeach

                                        @if($article->chercheurs->isNotEmpty() && $article->doctorants->isNotEmpty())
                                            ,
                                        @endif

                                        @foreach($article->doctorants as $doctorant)
                                            {{ $doctorant->prenomDoc }} {{ strtoupper($doctorant->nomDoc) }}@if(!$loop->last),@endif
                                        @endforeach
                                    </small>
                                </p>

                                <!-- Publication -->
                                @if($article->publication)
                                    <p class="card-text">
                                        <em>{{ $article->publication->titrePub }}</em>
                                        @if($article->datePubArt)
                                            <br><small>{{ \Carbon\Carbon::parse($article->datePubArt)->format('d M Y') }}</small>
                                        @endif
                                    </p>
                                @endif

                                <!-- Résumé (si disponible) -->
                                @if($article->resumeArticle)
                                    <p class="card-text">
                                        {{ Str::limit($article->resumeArticle, 150) }}
                                    </p>
                                @endif
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                @if($article->lienArticle)
                                    <a href="{{ $article->lienArticle }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Lire l'article
                                    </a>
                                @endif
                                <a href="{{ route('visiteur.article') }}" class="btn btn-sm btn-link btn-light text-muted">
                                    Voir plus d'articles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>



<script>

    // Sélectionne l'élément avec la classe .navbar puis Ajoute la classe "bg-light" à l'élément
    document.querySelector('.navbar').classList.add('bg-light');

    document.querySelector('.navbar').classList.add('custom-nav');

</script>
@endsection

