@extends('baseVisite')

{{-- Titre de la page --}}
@section('title', 'Publilab')
@section('bg-color', 'bg-white')

@php
    $excludeFooter = true;
@endphp

{{-- Articles --}}
@section('contenue-main')
<div class="custom-article-height mx-4">
    <div class="container mt-5">
        <div class="row mb-4">
            <!-- Filtre par année (passe en pleine largeur sur mobile) -->
            <div class="col-12 col-md-4 mb-3 mb-md-0">
                <form action="{{ route('rechercherArticle') }}" method="GET">
                    <div class="mb-3">
                        <label for="annee" class="text-secondary small mb-1">Année</label>
                        <select class="custom-select" id="annee" name="annee" onchange="this.form.submit()">
                            <option value="Tous">Toutes les années</option>
                            @foreach ($annees as $anneeOption)
                                <option value="{{ $anneeOption }}" {{ isset($annee) && $annee == $anneeOption ? 'selected' : '' }}>
                                    {{ $anneeOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <!-- Recherche (passe en pleine largeur sur mobile) -->
            <div class="col-12 col-md-8">
                <form action="{{ route('rechercherArticle') }}" method="GET">
                    <div class="mb-3">
                        <label class="text-secondary small mb-1">Rechercher</label>
                        <div class="input-group">
                            <input class="form-control" type="search" name="query"
                                placeholder="Rechercher un article" value="{{ $query ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Affichage du nombre d'articles -->
        <div class="mb-2 mt-3">
            <div class="d-flex align-items-center shadow-sm p-4">
                <span class="badge badge-primary badge-pill mr-2">{{ $articles->total() }}</span>
                <span class="text-muted">
                    @if($articles->total() > 1)
                        Articles trouvés
                    @else
                        Article trouvé
                    @endif
                    @if(isset($query) && !empty($query))
                        pour "<span class="font-weight-medium text-primary">{{ $query }}</span>"
                    @endif
                    @if(isset($annee) && !empty($annee) && $annee !== 'Tous')
                        en <span class="font-weight-medium text-primary">{{ $annee }}</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="p-5">
        @if ($articles->isEmpty())
            <div class="alert alert-info" role="alert">
                Aucun article.
            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach ($articles as $article)
                    <div class="col mb-4">
                        <div class="d-flex flex-column rounded shadow bg-white p-3 h-100">
                            <div class="d-flex">
                                <div class="ml-3">
                                    <!-- Titre de l'article avec les auteurs -->
                                    <p class="mb-1">
                                        @foreach ($article->chercheurs as $chercheur)
                                            {{ $chercheur->prenomCherch }} {{ strtoupper($chercheur->nomCherch) }}@if (!$loop->last),@endif
                                        @endforeach

                                        @if($article->chercheurs->isNotEmpty() && $article->doctorants->isNotEmpty())
                                            ,
                                        @endif

                                        @foreach ($article->doctorants as $doctorant)
                                            {{ $doctorant->prenomDoc }} {{ strtoupper($doctorant->nomDoc) }}
                                            @if (!$loop->last),@endif
                                        @endforeach

                                        <span class="font-weight-bold">{{ $article->titreArticle }}</span>
                                    </p>

                                    <!-- Informations de publication -->
                                    @if($article->publication)
                                        <p>
                                            <em>{{ $article->publication->titrePub }}</em>
                                            @if($article->datePubArt)
                                                , {{ \Carbon\Carbon::parse($article->datePubArt)->format('d M Y') }}
                                            @endif
                                            @if($article->volume)
                                                ; Vol.{{ $article->volume }}
                                            @endif
                                            @if($article->numero)
                                                , N°{{ $article->numero }}
                                            @endif
                                            @if($article->pageDebut && $article->pageFin)
                                                , pp.{{ $article->pageDebut }}-{{ $article->pageFin }}
                                            @endif
                                            @if($article->doi)
                                                , DOI: {{ $article->doi }}
                                            @endif
                                        </p>

                                        <!-- Éditeur de la publication -->
                                        @if($article->publication->editeurPub)
                                            <p class="text-muted">
                                                Éditeur: {{ $article->publication->editeurPub }}
                                            </p>
                                        @endif

                                        <!-- Indexation de la publication -->
                                        @if($article->publication->bdIndexations && $article->publication->bdIndexations->isNotEmpty())
                                            <p class="text-muted">
                                                Indexé dans:
                                                @foreach($article->publication->bdIndexations as $bdIndexation)
                                                    {{ $bdIndexation->nomBDIndex }}@if(!$loop->last), @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    @endif

                                    @if($article->resumeArticle)
                                        <div class="mt-2">
                                            <p class="mb-1"><strong>Résumé :</strong></p>
                                            <p class="text-muted">
                                                {{ Str::words($article->resumeArticle, 15, '...') }}
                                                <a href="#" data-toggle="modal" data-target="#detailsArticleModal-{{ $article->idArticle }}" class="text-primary">Lire plus</a>
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Lien vers l'article si disponible -->
                                    @if($article->lienArticle)
                                        <p>
                                            <a href="{{ $article->lienArticle }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> Accéder à l'article
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links('vendor.pagination.bootstrap-4') }}
            </div>

        @endif
    </div>


</div>

<script>
    document.querySelector('.navbar').classList.add('bg-light');
    document.querySelector('.navbar').classList.add('custom-nav');
</script>
@endsection



