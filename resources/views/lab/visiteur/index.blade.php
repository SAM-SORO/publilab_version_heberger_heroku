@extends('baseVisite')

{{-- titre de la page --}}
@section('title', 'Publilab')

@section('contenue-main')
{{-- donner le contenue de la page d'acceuil --}}

<script>

    // Sélectionne l'élément avec la classe .navbar puis Ajoute la classe "bg-light" à l'élément
    document.querySelector('.navbar').classList.add('bg-light');

    document.querySelector('.navbar').classList.add('custom-nav');

</script>
@endsection

