@extends('baseAdmin')

@section('bg-content', 'bg-white')

@section('content')

    <div class="container mt-4">
        @include('lab.partials.alerts')
    </div>

    <div class="container mt-5">
        <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherArticle') }}" method="GET">
            @csrf
            <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un article" aria-label="Rechercher" value="{{ request('query') }}">
            <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
        </form>
    </div>

    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Filtres -->
            <div class="col-md-9">
                <form action="{{ route('admin.listeArticles') }}" method="GET" class="row">

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

                    <!-- Filtre par type d'auteur -->
                    <div class="col-md-4 mb-3">
                        <label for="typeAuteur" class="text-secondary small mb-1">Type d'auteur</label>
                        <select class="custom-select" id="typeAuteur" name="typeAuteur" onchange="this.form.submit()">
                            <option value="Tous">Tous les auteurs</option>
                            <option value="chercheur" {{ $typeAuteur == 'chercheur' ? 'selected' : '' }}>Chercheurs</option>
                            <option value="doctorant" {{ $typeAuteur == 'doctorant' ? 'selected' : '' }}>doctorants</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Bouton d'ajout -->
            <div class="col-md-3 text-right mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addArticleModal">
                    <i class="fas fa-plus-circle"></i> Nouvel article
                </button>
            </div>
        </div>

        <!-- Affichage du nombre d'articles - Version améliorée -->
        <div class="card shadow-sm mb-1">
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
                                @if($typeAuteur && $typeAuteur != 'Tous')
                                    <span class="badge badge-light border">
                                        {{ $typeAuteur == 'chercheur' ? 'Chercheurs' : 'de doctorants' }}
                                    </span>
                                @endif
                            @endif
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('admin.listeArticles') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i> Réinitialiser les filtres
                        </a>
                    </div>
                </div>
            </div>
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
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light text-dark">
                                <h5 class="card-title mb-0">{{ $article->titreArticle }}</h5>
                            </div>
                            <div class="card-body">
                                <!-- Auteurs -->
                                <div class="mb-3">
                                    <strong>Auteurs:</strong>
                                    <p class="mb-1">
                                        {{ $article->getFormattedAuthors() }}

                                        @if($article->doctorants->isNotEmpty())
                                            <p>
                                                <span class="badge badge-info">Chercheur/Doctorant</span>
                                            </p>
                                        @else
                                            <p>
                                                <span class="badge badge-info">Chercheur(s)</span>
                                            </p>
                                        @endif
                                    </p>
                                </div>

                                <!-- Informations de publication -->
                                @if($article->publication)
                                    <div class="mb-3">
                                        <strong>Publication:</strong>
                                        <p class="mb-1">
                                            <em>{{ $article->publication->titrePub }}</em>
                                            @if (!empty($article->datePubArt))
                                                , {{ \Carbon\Carbon::parse($article->datePubArt)->format('d M Y') }}
                                            @endif
                                            @if (!empty($article->volume))
                                                , Vol.{{ $article->volume }}
                                            @endif
                                            @if (!empty($article->pageDebut) && !empty($article->pageFin))
                                                , pp.{{ $article->pageDebut }}-{{ $article->pageFin }}
                                            @endif
                                            @if (!empty($article->numero))
                                                , N°: {{ $article->numero }}
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                <!-- Type d'article -->
                                @if($article->typeArticle)
                                    <div class="mb-3">
                                        <strong>Type:</strong>
                                        <span class="badge badge-secondary">{{ $article->typeArticle->nomTypeArticle }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <!-- Bouton Voir l'article (à gauche) -->
                                        @if (!empty($article->lienArticle))
                                            <a href="{{ $article->lienArticle }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> Voir l'article
                                            </a>
                                        @endif
                                    </div>

                                    <div class="d-flex gap-2">
                                        <!-- Boutons d'action (à droite) -->
                                        <button type="button" class="btn btn-sm btn-outline-primary mr-2"
                                                data-toggle="modal"
                                                data-target="#detailsModal{{ $article->idArticle }}">
                                            <i class="fas fa-info-circle"></i> Détails
                                        </button>

                                        <a href="{{ route('admin.modifierArticle', $article->idArticle) }}"
                                           class="btn btn-outline-primary btn-sm mr-2">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>

                                        <form id="deleteArticleForm-{{ $article->idArticle }}" action="{{ route('admin.supprimerArticle', $article->idArticle) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="confirmDelete({{ $article->idArticle }})">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="detailsModal{{ $article->idArticle }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Détails de l'article</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Titre -->
                                        <h6 class="font-weight-bold">Titre</h6>
                                        <p>{{ $article->titreArticle }}</p>

                                        <!-- Auteurs -->
                                        <h6 class="font-weight-bold mt-3">Auteurs</h6>
                                        <p>{{ $article->getFormattedAuthors() }}</p>

                                        <!-- Publication -->
                                        @if($article->publication)
                                        <h6 class="font-weight-bold mt-3">Publication</h6>
                                        <p>{{ $article->publication->titrePub }}</p>
                                        @endif

                                        <!-- Date de publication -->
                                        @if($article->datePubArt)
                                        <h6 class="font-weight-bold mt-3">Date de publication</h6>
                                        <p>{{ \Carbon\Carbon::parse($article->datePubArt)->format('d/m/Y') }}</p>
                                        @endif

                                        <!-- Résumé -->
                                        @if($article->resumeArticle)
                                        <h6 class="font-weight-bold mt-3">Résumé</h6>
                                        <p>{{ $article->resumeArticle }}</p>
                                        @endif

                                        <!-- DOI -->
                                        @if($article->doi)
                                        <h6 class="font-weight-bold mt-3">DOI</h6>
                                        <p>{{ $article->doi }}</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </div>
        @endif
    </div>

    <!-- Modal pour enregistrer un article -->
    <div class="modal fade" id="addArticleModal" tabindex="-1" role="dialog" aria-labelledby="addArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addArticleModalLabel">Enregistrer un Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.enregistrerArticle') }}" method="POST">
                        @csrf

                        <!-- Informations de base -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Informations de base</h6>
                            </div>
                            <div class="card-body">
                                <!-- Titre de l'article -->
                                <div class="form-group">
                                    <label for="titreArticle">Titre de l'article <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titreArticle') is-invalid @enderror"
                                           id="titreArticle" name="titreArticle" value="{{ old('titreArticle') }}" required>
                                    @error('titreArticle')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Type d'article -->
                                <div class="form-group">
                                    <label for="idTypeArticle">Type d'article</label>
                                    <select class="form-control select2 @error('idTypeArticle') is-invalid @enderror"
                                            id="idTypeArticle" name="idTypeArticle" multiple>
                                        @foreach ($typeArticles as $type)
                                            <option value="{{ $type->idTypeArticle }}"
                                                    {{ old('idTypeArticle') == $type->idTypeArticle ? 'selected' : '' }}>
                                                {{ $type->nomTypeArticle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idTypeArticle')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Résumé -->
                                <div class="form-group">
                                    <label for="resumeArticle">Résumé</label>
                                    <textarea class="form-control @error('resumeArticle') is-invalid @enderror"
                                              id="resumeArticle" name="resumeArticle" rows="3">{{ old('resumeArticle') }}</textarea>
                                    @error('resumeArticle')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Auteurs -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Auteurs</h6>
                            </div>
                            <div class="card-body">
                                <!-- Chercheurs -->
                                <div class="form-group">
                                    <label for="chercheurs">Chercheurs</label>
                                    <select class="form-control select2 @error('chercheurs') is-invalid @enderror"
                                            id="chercheurs" name="chercheurs[]" multiple="multiple">
                                        @foreach ($chercheurs as $chercheur)
                                            <option value="{{ $chercheur->idCherch }}"
                                                    {{ in_array($chercheur->idCherch, old('chercheurs', [])) ? 'selected' : '' }}>
                                                {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Sélectionnez les chercheurs qui ont contribué à cet article.
                                    </small>
                                    @error('chercheurs')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Doctorants -->
                                <div class="form-group">
                                    <label for="doctorants">Doctorants</label>
                                    <select class="form-control select2 @error('doctorants') is-invalid @enderror"
                                            id="doctorants" name="doctorants[]" multiple="multiple">
                                        @foreach ($doctorants as $doctorant)
                                            <option value="{{ $doctorant->idDoc }}"
                                                    {{ in_array($doctorant->idDoc, old('doctorants', [])) ? 'selected' : '' }}>
                                                {{ $doctorant->nomDoc }} {{ $doctorant->prenomDoc }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Sélectionnez les doctorants qui ont contribué à cet article.
                                    </small>
                                    @error('doctorants')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <!-- Publication -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Informations de publication</h6>
                            </div>
                            <div class="card-body">
                                <!-- Publication -->
                                <div class="form-group">
                                    <label for="idPub">Publication</label>
                                    <select class="form-control select2 @error('idPub') is-invalid @enderror"
                                            id="idPub" name="idPub" multiple>
                                        @foreach ($publications as $publication)
                                            <option value="{{ $publication->idPub }}"
                                                    {{ old('idPub') == $publication->idPub ? 'selected' : '' }}>
                                                {{ $publication->titrePub }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idPub')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Date de publication -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="datePubArt">Date de publication</label>
                                            <input type="date" class="form-control @error('datePubArt') is-invalid @enderror"
                                                   id="datePubArt" name="datePubArt"
                                                   value="{{ old('datePubArt') }}">
                                            @error('datePubArt')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Volume -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="volume">Volume</label>
                                            <input type="number" class="form-control @error('volume') is-invalid @enderror"
                                                   id="volume" name="volume"
                                                   value="{{ old('volume') }}">
                                            @error('volume')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Numéro -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="numero">Numéro</label>
                                            <input type="text" class="form-control @error('numero') is-invalid @enderror"
                                                   id="numero" name="numero"
                                                   value="{{ old('numero') }}">
                                            @error('numero')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Pages -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pageDebut">Page de début</label>
                                            <input type="number" class="form-control @error('pageDebut') is-invalid @enderror"
                                                   id="pageDebut" name="pageDebut"
                                                   value="{{ old('pageDebut') }}">
                                            @error('pageDebut')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pageFin">Page de fin</label>
                                            <input type="number" class="form-control @error('pageFin') is-invalid @enderror"
                                                   id="pageFin" name="pageFin"
                                                   value="{{ old('pageFin') }}">
                                            @error('pageFin')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Liens et identifiants -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Liens et identifiants</h6>
                            </div>
                            <div class="card-body">
                                <!-- DOI -->
                                <div class="form-group">
                                    <label for="doi">DOI</label>
                                    <input type="text" class="form-control @error('doi') is-invalid @enderror"
                                           id="doi" name="doi"
                                           value="{{ old('doi') }}">
                                    @error('doi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Lien de l'article -->
                                <div class="form-group">
                                    <label for="lienArticle">Lien de l'article</label>
                                    <input type="url" class="form-control @error('lienArticle') is-invalid @enderror"
                                           id="lienArticle" name="lienArticle" value="{{ old('lienArticle') }}">
                                    @error('lienArticle')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save mr-2"></i> Enregistrer l'article
                            </button>
                        </div>
                    </form>
                </div>
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

        $('#doctorants').select2({
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
                maximumSelectionLength: function() {
                    return "Vous ne pouvez sélectionner qu'un seul élément";  // Message personnalisé en français
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
                maximumSelectionLength: function() {
                    return "Vous ne pouvez sélectionner qu'un seul élément";  // Message personnalisé en français
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
                document.getElementById(`deleteArticleForm-${articleId}`).submit();
            }
        });
    }
</script>
@endsection
