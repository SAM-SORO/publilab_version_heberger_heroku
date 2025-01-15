@extends("baseChercheur")

@section('bg-content', 'bg-white')

@section('content')

    <div class="container mt-4">
        {{-- Erreur session --}}
        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" id="alert-danger-login">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Succès session --}}
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-login">
                {{ Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Erreurs de validation --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" id="alert-validation-errors">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>


    <div class="container mt-5">
        <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('chercheur.rechercherArticle') }}" method="GET">
            @csrf
            <input class="form-control col-lg-8 col-6 col-sm-8 py-4" onchange="this.form.submit()" type="search" name="query" placeholder="Rechercher un article" aria-label="Rechercher" value="{{ request('query') }}">
            <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
        </form>
    </div>

    <div class="container d-flex mt-5 align-items-center">
        <ul class="list-unstyled">
            <li class="text-secondary col-2 ml-5 mt-4">Année</li>
        </ul>

        <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
        <div class="d-flex justify-content-between w-100">
            <div class="col-9">
                <form action="{{ route('chercheur.filtreArticle') }}" method="GET">
                    @csrf
                    <input type="hidden" name="query" value="{{ request('query') }}">
                    <select title="filtre par année" class="custom-select col-4 col-lg-2 col-sm-6 col-md-3" name="annee" onchange="this.form.submit()">
                        <option value="Tous" {{ request('annee') === 'Tous' ? 'selected' : '' }}>Tous</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Bouton pour ouvrir le modal pour ajouter un article -->
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addArticleModal">
                    Ajouter un Article
                </button>
            </div>
        </div>
    </div>



    <div class="p-5">
        @if ($articles->isEmpty())
            <div class="alert alert-info" role="alert">
                Aucun article trouvé.
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
                                    <p class="mb-1 font-weight-bold">
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
                                        <span class="text-danger">{{ $article->titreArticle }}</span>
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
                                            @if (!empty($revue->pivot->numero))
                                                , N°: {{ $revue->pivot->numero }}
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

                                    <!-- Résumé de l'article -->
                                    @if (!empty($article->resumeArticle))
                                        <small><p>{{ $article->resumeArticle }}</p></small>
                                    @endif
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-auto">
                                <form action="{{ route('chercheur.modifierArticle', $article->idArticle) }}" method="GET">
                                    @csrf
                                    <button class="btn btn-primary mr-2" type="submit">Modifier</button>
                                </form>

                                <form id="deleteArticleChercheurForm" action="{{ route('chercheur.supprimerArticle', $article->idArticle) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('POST') <!-- Utilisation de la méthode POST -->
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $article->idArticle }})">
                                        Supprimer
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links('vendor.pagination.bootstrap-4') }}
            </div>

        @endif
    </div>


<!-- Modal pour enregistrer un article -->
<div class="modal fade" id="addArticleModal" tabindex="-1" role="dialog" aria-labelledby="addArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addArticleModalLabel">Enregistrer un Article publié</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('chercheur.enregistrerArticle') }}" method="POST">
                    @csrf

                    <!-- Titre de l'article -->
                    <div class="form-group">
                        <label for="titreArticle">Titre de l'article <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titreArticle') is-invalid @enderror"
                               id="titreArticle" name="titreArticle"
                               value="{{ old('titreArticle') }}" required>
                        @error('titreArticle')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Résumé -->
                    <div class="form-group">
                        <label for="resumeArticle">Résumé</label>
                        <textarea class="form-control @error('resumeArticle') is-invalid @enderror"
                                  id="resumeArticle" name="resumeArticle" rows="3"
                                  >{{ old('resumeArticle') }}</textarea>
                        @error('resumeArticle')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- DOI -->
                    <div class="form-group">
                        <label for="doi">DOI</label>
                        <input type="text" class="form-control @error('doi') is-invalid @enderror"
                               id="doi" name="doi" value="{{ old('doi') }}">
                        @error('doi')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Chercheurs contributeurs -->
                    <div class="form-group">
                        <label for="chercheurs">Chercheurs</label>
                        <select class="form-control @error('chercheurs') is-invalid @enderror"
                                id="chercheurs" name="chercheurs[]" multiple>
                            @foreach ($chercheurs as $chercheur)
                                <option value="{{ $chercheur->idCherch }}"
                                    {{ in_array($chercheur->idCherch, old('chercheurs', [])) ? 'selected' : '' }}>
                                    {{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}
                                </option>
                            @endforeach
                        </select>
                        @error('chercheurs')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Revue -->
                    <div class="form-group">
                        <label for="revue">Revue</label>
                        <select class="form-control @error('revue') is-invalid @enderror" id="revue" name="revue" multiple>
                            @foreach ($revues as $revue)
                                <option value="{{ $revue->idRevue }}" {{ old('revue') == $revue->idRevue ? 'selected' : '' }}>
                                    {{ $revue->nomRevue }}
                                </option>
                            @endforeach
                        </select>
                        @error('revue')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date de publication -->
                    <div class="form-group">
                        <label for="datePubArt">Date de publication</label>
                        <input type="date" class="form-control @error('datePubArt') is-invalid @enderror"
                               id="datePubArt" name="datePubArt"
                               value="{{ old('datePubArt') }}">
                        @error('datePubArt')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Volume -->
                    <div class="form-group">
                        <label for="volume">Volume</label>
                        <input type="number" class="form-control @error('volume') is-invalid @enderror"
                               id="volume" name="volume"
                               value="{{ old('volume') }}">
                        @error('volume')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Numéro -->
                    <div class="form-group">
                        <label for="numero">Numéro</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror"
                               id="numero" name="numero"
                               value="{{ old('numero') }}">
                        @error('numero')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Page de début -->
                    <div class="form-group">
                        <label for="pageDebut">Page de début</label>
                        <input type="number" class="form-control @error('pageDebut') is-invalid @enderror"
                               id="pageDebut" name="pageDebut"
                               value="{{ old('pageDebut') }}">
                        @error('pageDebut')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Page de fin -->
                    <div class="form-group">
                        <label for="pageFin">Page de fin</label>
                        <input type="number" class="form-control @error('pageFin') is-invalid @enderror"
                               id="pageFin" name="pageFin"
                               value="{{ old('pageFin') }}">
                        @error('pageFin')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Bouton d'envoi -->
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

    <script>

        $(document).ready(function() {
            // Initialisation existante pour revue
            $('#revue').select2({
                placeholder: 'Sélectionnez une revue',
                maximumSelectionLength: 1, // Limite à une seule option
                allowClear: true,
                width: '100%'
            });

            // Nouvelle initialisation pour chercheurs
            $('#chercheurs').select2({
                placeholder: 'Sélectionnez les chercheurs contributeurs',
                allowClear: true,
                width: '100%'
            });
        });



        function confirmDelete(articleId) {
            Swal.fire({
                title: "Êtes-vous sûr de vouloir supprimer cet article ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, Supprimer !",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Trouver le formulaire en fonction de l'ID de l'article
                    const form = document.getElementById('deleteArticleChercheurForm');
                    // Modifier l'action du formulaire pour inclure l'ID de l'article
                    form.submit();
                }
            });
        }

        document.getElementById('revue').addEventListener('change', function () {
            const revueSelected = this.value;
            document.getElementById('volume').disabled = !revueSelected;
            document.getElementById('pageDebut').disabled = !revueSelected;
            document.getElementById('pageFin').disabled = !revueSelected;
        });


    </script>

@endsection
