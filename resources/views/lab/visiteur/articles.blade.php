@extends('baseVisite')

{{-- Titre de la page --}}
@section('title', 'Publilab-Articles')
@section('bg-color', 'bg-white')

@php
    $excludeFooter = true;
@endphp

{{-- Articles --}}
@section('contenue-main')
<div class="custom-article-height mx-4">
    <div class="container mt-5">
        <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('rechercherArticle')}}" method="GET">
            @csrf
            <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un article" aria-label="Rechercher" value="{{ old('query', $query ?? '') }}">
            <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
        </form>
    </div>

    <div class="container d-flex mt-5 align-items-center">
        {{-- <div><ul><li class="text-secondary col-5 ml-5">Année</li></ul></div> --}}
        <div class="col-7">
            <form action="{{ route('rechercherArticle') }}" method="GET" class="w-100">
                @csrf
                <select
                    title="Filtrer par année"
                    class="custom-select col-4 col-lg-2 col-sm-6 col-md-3"
                    name="annee"
                    onchange="this.form.submit()"
                >
                    <option value="Tous" {{ old('annee', $annee ?? 'Tous') === 'Tous' ? 'selected' : '' }}>Tous</option>
                    @foreach ($annees as $anneeOption)
                        <option value="{{ $anneeOption }}" {{ old('annee', $annee ?? 'Tous') == $anneeOption ? 'selected' : '' }}>
                            {{ $anneeOption }}
                        </option>
                    @endforeach
                </select>

            </form>

        </div>
    </div>

    <div class="p-5">
        @if ($articles->isEmpty())
            <div class="alert alert-info" role="alert">
                Aucun article n'a été publié.
            </div>
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
                                    <p class="mb-1 ">
                                        @foreach ($article->chercheurs as $chercheur)
                                            {{ $chercheur->prenomCherch }} {{ strtoupper($chercheur->nomCherch) }},
                                        @endforeach
                                        @foreach ($article->doctorants as $doctorant)
                                            {{ $doctorant->prenomDoc }} {{ strtoupper($doctorant->nomDoc) }}
                                            @if ($doctorant->encadrants->isNotEmpty())
                                                (encadré par
                                                @foreach ($doctorant->encadrants as $encadrant)
                                                    {{ $encadrant->prenomCherch }} {{ strtoupper($encadrant->nomCherch) }}
                                                @endforeach)
                                            @endif
                                        @endforeach
                                        <span class="font-weight-bold">{{ $article->titreArticle }}</span>
                                    </p>

                                    <!-- Informations de publication et DOI -->
                                    @foreach ($article->revues as $revue)
                                        <p>
                                            <em>{{ $revue->nomRevue }}</em>
                                            @if (!empty($revue->pivot->datePubArt))
                                                , {{ \Carbon\Carbon::parse($revue->pivot->datePubArt)->format('d M Y') }}
                                            @endif
                                            @if (!empty($revue->pivot->volume))
                                                ; Vol.{{ $revue->pivot->volume }}
                                            @endif
                                            @if (!empty($revue->pivot->pageDebut) && !empty($revue->pivot->pageFin))
                                                pp.{{ $revue->pivot->pageDebut }}-{{ $revue->pivot->pageFin }}
                                            @endif
                                            @if (!empty($article->doi))
                                                , DOI: {{ $article->doi }}
                                            @endif
                                        </p>
                                    @endforeach

                                    <!-- Indexation de la revue -->
                                    @foreach ($article->revues as $revue)
                                        @if ($revue->bdIndexations->isNotEmpty())
                                            @foreach ($revue->bdIndexations as $bdIndexation)
                                                <p class="text-muted">Indexé {{ $bdIndexation->nomBDInd }}</p>
                                            @endforeach
                                        @endif
                                    @endforeach
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
