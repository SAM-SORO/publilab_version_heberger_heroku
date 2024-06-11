@extends('baseVisite')

{{-- titre de la page --}}
@section('title', 'Publilab')



@section('contenue-main')

<!-- Conteneur principal avec une marge supérieure -->
<div class="container" style="margin-top: 60px">
    <!-- Section de présentation avec Bootstrap Jumbotron -->
    <div class=" shadow bg-white rounded text-center py-4">
        <!-- Titre principal de la page -->
          <!-- Image -->
        <h1 class="display-4"><strong>Bienvenue sur <span class="text-success">Publi</span><span class="text-dark">lab</span></strong></h1>
        <img src="{{asset('img/R.jpg')}}" class="img-fluid w-55" alt="Image de présentation">
        <!-- Description de la page -->
        <p class="lead mt-4">Découvrez notre plateforme de gestion de publications, votre alliée pour une diffusion efficace des travaux de recherche et une collaboration fructueuse au sein de notre laboratoire.</p>
        <hr class="my-4">
        <!-- Texte supplémentaire -->
        <p>Visitez, Explorez, Télèchargez, les articles Publiés par nos chercheurs.</p>
        <!-- Bouton d'action -->
        <button class="btn btn-primary btn-lg" href="{{route('visiteur.article')}}" role="button">Voir Les Publication</button>
    </div>

</div>

<script>

    // Sélectionne l'élément avec la classe .navbar puis Ajoute la classe "bg-light" à l'élément
    document.querySelector('.navbar').classList.add('bg-light');

    document.querySelector('.navbar').classList.add('custom-nav');

</script>
@endsection

