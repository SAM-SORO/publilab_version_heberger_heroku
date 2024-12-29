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

<section class="container-fluid mt-5 mb-5 bg-white">
    <div class="container">
        <h3 class="row justify-content-center pt-5 pb-5">Articles publiés récemment</h3>
        <div class="row justify-content-center">
            @foreach($articles as $article)
                <div class="col-md-6 mb-4 d-flex justify-content-center">
                    <div class="card bg-white" style="width: 600px; height: 400px;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $article->titreArticle }}</h5>
                            <p class="card-text">{{ Str::limit($article->resumeArticle, 50) }}</p>
                            <a href="{{ route('visiteur.article', $article->idArticle) }}" class="btn btn-primary mt-auto">Lire la suite</a>
                            {{-- {{ route('visiteur.article.show', $article->idArticle) }} --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>



<script>

    // Sélectionne l'élément avec la classe .navbar puis Ajoute la classe "bg-light" à l'élément
    document.querySelector('.navbar').classList.add('bg-light');

    document.querySelector('.navbar').classList.add('custom-nav');

</script>
@endsection

