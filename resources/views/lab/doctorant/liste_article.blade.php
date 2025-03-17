@extends("baseDoctorant")

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
        <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('doctorant.rechercherArticle') }}" method="GET">
            @csrf
            <input class="form-control col-lg-8 col-6 col-sm-8 py-4" onchange="this.form.submit()" type="search" name="query" placeholder="Rechercher un article" aria-label="Rechercher" value="{{ request('query') }}">
            <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
        </form>
    </div>

    <div class="container mt-5">

        <div class="row align-items-center">
            <!-- Filtres -->
            <div class="col-md-9">
                <form action="{{ route('doctorant.listeArticles') }}" method="GET" class="row">

                    <!-- Filtre par année -->
                    <div class="col-md-4 mb-3">
                        <label for="annee" class="text-secondary small mb-1">Année</label>
                        <select class="custom-select" id="annee" name="annee" onchange="this.form.submit()">
                            <option value="Tous">Toutes les années</option>
                            @foreach ($annees as $anneeOption)
                                <option value="{{ $anneeOption }}" {{ $annee == $anneeOption ? 'selected' : '' }}>
                                    {{ $anneeOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par type d'article -->
                    <div class="col-md-4 mb-3">
                        <label for="typeArticle" class="text-secondary small mb-1">Type d'article</label>
                        <select class="custom-select" id="typeArticle" name="typeArticle" onchange="this.form.submit()">
                            <option value="Tous">Tous les types</option>
                            @foreach ($typeArticles as $type)
                                <option value="{{ $type->idTypeArticle }}" {{ $typeArticleId == $type->idTypeArticle ? 'selected' : '' }}>
                                    {{ $type->nomTypeArticle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <!-- Bouton d'ajout d'article -->
            <div class="col-md-3 text-right mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ajouterArticleModal">
                    <i class="fas fa-plus-circle mr-1"></i> Ajouter un article
                </button>
            </div>
        </div>

        <!-- Affichage du nombre d'articles - Version améliorée -->
        <div class="card shadow-sm mb-4 mt-2">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-primary font-weight-bold">
                            <i class="fas fa-file-alt mr-2"></i>
                            {{ $articles->total() }} article(s)
                        </span>
                        <span class="text-muted ml-2">
                            @if(isset($query))
                                trouvé(s) pour "<strong>{{ $query }}</strong>"
                            @else
                                @if($annee && $annee != 'Tous')
                                    <span class="badge badge-light border mr-1">Année: {{ $annee }}</span>
                                @endif
                                @if($typeArticleId && $typeArticleId != 'Tous')
                                    <span class="badge badge-light border mr-1">Type: {{ $typeArticles->where('idTypeArticle', $typeArticleId)->first()->nomTypeArticle }}</span>
                                @endif
                            @endif
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('doctorant.listeArticles') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i> Réinitialiser les filtres
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des articles -->
        <div class="row mt-4">
            @if($articles->isEmpty())
                <div class="col-12 text-center py-5">
                    <div class="card shadow-sm">
                        <div class="card-body py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucun article trouvé</h4>
                            <p class="text-muted">Essayez de modifier vos critères de recherche ou ajoutez un nouvel article.</p>
                            <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#ajouterArticleModal">
                                <i class="fas fa-plus-circle mr-1"></i> Ajouter un article
                            </button>
                        </div>
                    </div>
                </div>
            @else
                @foreach($articles as $article)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-truncate" title="{{ $article->titreArticle }}">
                                    {{ Str::limit($article->titreArticle, 40) }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    @if($article->datePubArt)
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ date('d/m/Y', strtotime($article->datePubArt)) }}
                                    </small>
                                    @endif

                                    @if($article->typeArticle)
                                    <small class="text-muted">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $article->typeArticle->nomTypeArticle }}
                                    </small>
                                    @endif
                                </div>

                                @if($article->resumeArticle)
                                <p class="card-text text-truncate mb-2" title="{{ $article->resumeArticle }}">
                                    {{ Str::limit($article->resumeArticle, 100) }}
                                </p>
                                @endif

                                @if($article->chercheurs->isNotEmpty() || $article->doctorants->isNotEmpty())
                                <div class="mt-3">
                                    <p class="mb-1"><strong>Auteurs :</strong></p>
                                    <p>
                                        @if($article->chercheurs->isNotEmpty())
                                            @foreach ($article->chercheurs as $chercheur)
                                                {{ $chercheur->prenomCherch }} {{ strtoupper($chercheur->nomCherch) }}
                                                @if(!$loop->last || $article->doctorants->isNotEmpty()), @endif
                                            @endforeach
                                        @endif

                                        @if($article->doctorants->isNotEmpty())
                                            @foreach ($article->doctorants as $doctorant)
                                                {{ $doctorant->prenomDoc }} {{ strtoupper($doctorant->nomDoc) }}
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        @endif
                                    </p>
                                </div>
                                @endif

                                @if($article->publication)
                                <div class="mt-2">
                                    <p class="mb-1"><strong>Publication :</strong></p>
                                    <p class="text-truncate" title="{{ $article->publication->titrePub }}">
                                        {{ $article->publication->titrePub }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#detailsArticleModal-{{ $article->idArticle }}">
                                        <i class="fas fa-eye mr-1"></i> Détails
                                    </button>
                                    <div>
                                        <a href="{{ route('doctorant.modifierArticle', $article->idArticle) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>

                                        <form id="deleteArticleDoctorantForm-{{ $article->idArticle }}" action="{{ route('doctorant.supprimerArticle', $article->idArticle) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDelete({{ $article->idArticle }})">
                                                <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Détails Article -->
                    <div class="modal fade" id="detailsArticleModal-{{ $article->idArticle }}" tabindex="-1" role="dialog" aria-labelledby="detailsArticleModalLabel-{{ $article->idArticle }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title" id="detailsArticleModalLabel-{{ $article->idArticle }}">
                                        <i class="fas fa-file-alt mr-2"></i>Détails de l'article
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="font-weight-bold">{{ $article->titreArticle }}</h5>

                                            <div class="d-flex flex-wrap mt-3 mb-4">
                                                @if($article->datePubArt)
                                                <span class="badge badge-light border mr-2 mb-2">
                                                    <i class="far fa-calendar-alt mr-1"></i>
                                                    {{ date('d/m/Y', strtotime($article->datePubArt)) }}
                                                </span>
                                                @endif

                                                @if($article->typeArticle)
                                                <span class="badge badge-light border mr-2 mb-2">
                                                    <i class="fas fa-tag mr-1"></i>
                                                    {{ $article->typeArticle->nomTypeArticle }}
                                                </span>
                                                @endif

                                                @if($article->doi)
                                                <span class="badge badge-light border mr-2 mb-2">
                                                    <i class="fas fa-fingerprint mr-1"></i>
                                                    DOI: {{ $article->doi }}
                                                </span>
                                                @endif
                                            </div>

                                            @if($article->resumeArticle)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p class="mb-1"><strong><i class="fas fa-align-left mr-2"></i>Résumé :</strong></p>
                                                    <p>{{ $article->resumeArticle }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($article->chercheurs->isNotEmpty() || $article->doctorants->isNotEmpty())
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p class="mb-1"><strong><i class="fas fa-users mr-2"></i>Auteurs :</strong></p>
                                                    <p>
                                                        @if($article->chercheurs->isNotEmpty())
                                                            @foreach ($article->chercheurs as $chercheur)
                                                                {{ $chercheur->prenomCherch }} {{ strtoupper($chercheur->nomCherch) }}
                                                                @if(!$loop->last || $article->doctorants->isNotEmpty()), @endif
                                                            @endforeach
                                                        @endif

                                                        @if($article->doctorants->isNotEmpty())
                                                            @foreach ($article->doctorants as $doctorant)
                                                                {{ $doctorant->prenomDoc }} {{ strtoupper($doctorant->nomDoc) }}
                                                                @if(!$loop->last), @endif
                                                            @endforeach
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($article->publication)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p class="mb-1"><strong><i class="fas fa-book mr-2"></i>Publication :</strong></p>
                                                    <p>{{ $article->publication->titrePub }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($article->volume || $article->numero)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p class="mb-1"><strong><i class="fas fa-bookmark mr-2"></i>Référence :</strong></p>
                                                    <p>
                                                        @if($article->volume) Volume {{ $article->volume }}@if($article->numero), @endif @endif
                                                        @if($article->numero) Numéro {{ $article->numero }} @endif
                                                        @if($article->pageDebut && $article->pageFin), pp. {{ $article->pageDebut }}-{{ $article->pageFin }}@endif
                                                    </p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($article->lienArticle)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p class="mb-1"><strong><i class="fas fa-link mr-2"></i>Lien :</strong></p>
                                                    <a href="{{ $article->lienArticle }}" target="_blank" rel="noopener noreferrer">
                                                        {{ $article->lienArticle }}
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <a href="{{ route('doctorant.modifierArticle', $article->idArticle) }}" class="btn btn-primary">
                                        <i class="fas fa-edit mr-1"></i> Modifier
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    </div>

    <!-- Modal Ajouter Article -->
    <div class="modal fade" id="ajouterArticleModal" tabindex="-1" role="dialog" aria-labelledby="ajouterArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="ajouterArticleModalLabel">
                        <i class="fas fa-plus-circle mr-2"></i>Ajouter un article
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('doctorant.enregistrerArticle') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Informations de base -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informations de base</h6>
                            </div>
                            <div class="card-body">
                                <!-- Titre -->
                                <div class="form-group">
                                    <label for="titreArticle">Titre <span class="text-danger">*</span></label>
                                    <input type="text" name="titreArticle" id="titreArticle" class="form-control" required>
                                </div>

                                <!-- Type d'article -->
                                <div class="form-group">
                                    <label for="idTypeArticle">Type d'article</label>
                                    <select name="idTypeArticle" id="idTypeArticle" class="form-control" multiple>
                                        @foreach($typeArticles as $type)
                                            <option value="{{ $type->idTypeArticle }}">{{ $type->nomTypeArticle }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Co-auteurs chercheurs -->
                                <div class="form-group">
                                    <label for="chercheurs">Co-auteurs chercheurs <span class="text-danger">*</span></label>
                                    <select name="chercheurs[]" id="chercheurs" class="form-control" multiple>
                                        @foreach($chercheurs as $chercheur)
                                            <option value="{{ $chercheur->idCherch }}">
                                                {{ $chercheur->prenomCherch }} {{ strtoupper($chercheur->nomCherch) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Résumé -->
                                <div class="form-group">
                                    <label for="resumeArticle">Résumé</label>
                                    <textarea name="resumeArticle" id="resumeArticle" class="form-control" rows="3"></textarea>
                                </div>

                                <!-- DOI -->
                                <div class="form-group">
                                    <label for="doi">DOI</label>
                                    <input type="text" name="doi" id="doi" class="form-control">
                                </div>

                                <!-- Lien -->
                                <div class="form-group">
                                    <label for="lienArticle">Lien</label>
                                    <input type="url" name="lienArticle" id="lienArticle" class="form-control" placeholder="https://...">
                                </div>
                            </div>
                        </div>

                        <!-- Informations de publication -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informations de publication</h6>
                            </div>
                            <div class="card-body">
                                <!-- Publication -->
                                <div class="form-group">
                                    <label for="idPub">Publication</label>
                                    <select name="idPub" id="idPub" class="form-control" multiple>
                                        <option value="">Sélectionner une publication</option>
                                        @foreach($publications as $publication)
                                            <option value="{{ $publication->idPub }}">{{ $publication->titrePub }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Date de publication -->
                                <div class="form-group">
                                    <label for="datePubArt">Date de publication</label>
                                    <input type="date" name="datePubArt" id="datePubArt" class="form-control">
                                </div>

                                <div class="row">
                                    <!-- Volume -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="volume">Volume</label>
                                            <input type="number" name="volume" id="volume" class="form-control" min="1">
                                        </div>
                                    </div>

                                    <!-- Numéro -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="numero">Numéro</label>
                                            <input type="number" name="numero" id="numero" class="form-control" min="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Page début -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pageDebut">Page de début</label>
                                            <input type="number" name="pageDebut" id="pageDebut" class="form-control" min="1">
                                        </div>
                                    </div>

                                    <!-- Page fin -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pageFin">Page de fin</label>
                                            <input type="number" name="pageFin" id="pageFin" class="form-control" min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function() {
            // Initialisation de Select2 pour tous les sélecteurs
            $('#chercheurs').select2({
                width: '100%',
                placeholder: 'Sélectionner...',
                allowClear: true
            });

            $('#idTypeArticle').select2({
                width: '100%',
                placeholder: 'Sélectionner...',
                allowClear: true,
                maximumSelectionLength: 1,
                language: {
                    noResults: function() {
                        return "Aucune base trouvée";
                    },
                    searching: function() {
                        return "Recherche...";
                    },
                    maximumSelected: function(args) {
                        return "Vous ne pouvez sélectionner qu'un seul élément";
                    }
                },

            });

            $('#idPub').select2({
                width: '100%',
                placeholder: 'Sélectionner...',
                allowClear: true,
                maximumSelectionLength: 1,
                language: {
                    noResults: function() {
                        return "Aucune base trouvée";
                    },
                    searching: function() {
                        return "Recherche...";
                    },
                    maximumSelected: function(args) {
                        return "Vous ne pouvez sélectionner qu'un seul élément";
                    }
                },

            });

            $('.select2-selection').css('min-height', '40px'); // Applique la hauteur après initialisation
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
                    document.getElementById(`deleteArticleDoctorantForm-${articleId}`).submit();
                }
            });
        }


    </script>

@endsection
