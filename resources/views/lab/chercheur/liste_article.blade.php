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

    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Filtres -->
            <div class="col-md-9">
                <form action="{{ route('chercheur.listeArticles') }}" method="GET" class="row">
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

            <!-- Boutons d'actions -->
            <div class="d-flex justify-content-end">

                <button type="button" class="btn btn-primary btn-sm mr-3" data-toggle="modal" data-target="#addArticleModal">
                    <i class="fas fa-plus-circle"></i> Nouveau
                </button>

                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#coAuteurModal">
                    <i class="fas fa-user-plus"></i> Co-auteur
                </button>
            </div>
        </div>

        <div class="card shadow-sm mb-1">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-primary font-weight-bold">
                            <i class="fas fa-file-alt mr-2"></i>
                            {{ $articlesChercheur->total() }} article(s)
                        </span>
                        <span class="text-muted ml-2">
                            @if(isset($query) && trim($query) !== '')
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
                        <a href="{{ route('chercheur.listeArticles') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i> Rafraîchir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="p-5">
        @if ($articlesChercheur->isEmpty())
            <div class="alert alert-info" role="alert">
                Aucun article trouvé.
            </div>
            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
            </div>

        @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach ($articlesChercheur as $article)
                    <div class="col mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-dark text-white"">
                                <h6 class="card-title mb-0" style="line-height: 25px">{{ Str::limit($article->titreArticle, 100) }} </h6>
                            </div>
                            <div class="card-body">
                                <!-- Auteurs -->
                                <div class="mb-3">
                                    <p class="mb-1 font-weight-bold">
                                        <p>{{ $article->getAuthors() }}</p>
                                    </p>

                                    <!-- Informations de publication -->
                                    @if($article->publication)
                                        <p>
                                            <em>{{ $article->publication->titrePub }}</em>
                                            @if (!empty($article->datePubArt))
                                                , {{ \Carbon\Carbon::parse($article->datePubArt)->format('d M Y') }}
                                            @endif
                                            @if (!empty($article->volume))
                                                ; Vol.{{ $article->volume }}
                                            @endif
                                            @if (!empty($article->pageDebut) && !empty($article->pageFin))
                                                pp.{{ $article->pageDebut }}-{{ $article->pageFin }}
                                            @endif
                                            @if (!empty($article->numero))
                                                , N°: {{ $article->numero }}
                                            @endif
                                        </p>
                                    @endif

                                    <!-- DOI et Lien -->
                                    @if (!empty($article->doi))
                                        <p>DOI: {{ $article->doi }}</p>
                                    @endif
                                    @if (!empty($article->lienArticle))
                                        <p><a href="{{ $article->lienArticle }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i> Voir l'article
                                        </a></p>
                                    @endif

                                    <!-- Type d'article -->
                                    @if($article->typeArticle)
                                        <p class="text-muted">Type: {{ $article->typeArticle->nomTypeArticle }}</p>
                                    @endif

                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="card-footer bg-light text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <!-- Bouton Voir l'article (à gauche) -->
                                               <!-- Boutons d'action (à droite) -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary mr-2"
                                               data-toggle="modal"
                                               data-target="#detailsModal{{ $article->idArticle }}">
                                           <i class="fas fa-info-circle"></i> Détails
                                       </button>
                                    </div>

                                    <div class="d-flex gap-2">

                                        <a href="{{ route('chercheur.modifierArticle', $article->idArticle) }}"
                                           class="btn btn-outline-primary btn-sm mr-2">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>

                                        <form id="deleteArticleChercheurForm-{{ $article->idArticle }}" action="{{ route('chercheur.supprimerArticle', $article->idArticle) }}" method="POST" class="d-inline">
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
                                    <div class="modal-header bg-white">
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
                                        <p>{{ $article->getAuthors() }}</p>

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
                {{ $articlesChercheur->links('vendor.pagination.bootstrap-4') }}
            </div>
        @endif
    </div>


<!-- Modal d'ajout d'article -->
<div class="modal fade" id="addArticleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-white">
                <h5 class="modal-title">Enregistrer un Article</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="articleForm" action="{{ route('chercheur.enregistrerArticle') }}" method="POST">
                    @csrf

                    <!-- Informations principales -->
                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white"">
                            <h6 class="mb-0">Informations principales</h6>
                        </div>
                        <div class="card-body">
                            <!-- Titre -->
                            <div class="form-group">
                                <label for="titreArticle">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="titreArticle" class="form-control @error('titreArticle') is-invalid @enderror"
                                       value="{{ old('titreArticle') }}" required>
                                @error('titreArticle')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Type d'article -->
                            <div class="form-group">
                                <label for="idTypeArticle">Type d'article</label>
                                <select name="type_article" id="idTypeArticle" class="form-control" multiple>
                                    @foreach($typeArticles as $type)
                                        <option value="{{ $type->idTypeArticle }}">{{ $type->nomTypeArticle }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="chercheurSelect">Sélectionner un chercheur</label>
                                <select id="chercheurSelect" class="form-control">
                                    <option value="">-- Choisir un chercheur --</option>
                                    @foreach($chercheurs as $chercheur)
                                        <option value="{{ $chercheur->idCherch }}">
                                            {{ strtoupper($chercheur->nomCherch) }} {{ $chercheur->prenomCherch }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <small><strong>Liste des chercheurs sélectionnés</strong></small>
                                <ul id="chercheurList" class="list-group"></ul>
                            </div>

                            <!-- Champs cachés pour envoyer les données au backend -->
                            <input type="hidden" name="chercheurs" id="chercheurs_input">
                            <input type="hidden" name="rangs" id="rangs_input">



                            <!-- Co-auteurs doctorants -->
                            <div class="form-group">
                                <label for="doctorants">doctorants</label>
                                <select name="doctorants[]" id="doctorants" class="form-control" multiple>
                                    @foreach($doctorants as $doctorant)
                                        <option value="{{ $doctorant->idDoc }}">
                                            {{ $doctorant->prenomDoc }} {{ $doctorant->nomDoc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Résumé -->
                            <div class="form-group">
                                <label for="resumeArticle">Résumé</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Publication -->
                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">Informations de publication</h6>
                        </div>
                        <div class="card-body">
                            <!-- Publication -->
                            <div class="form-group">
                                <label for="idPub">Publication </label>
                                <select name="idPub" id="idPub" class="form-control" multiple>

                                    @foreach($publications as $publication)
                                        <option value="{{ $publication->idPub }}">{{ $publication->titrePub }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Informations spécifiques -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date de publication </label>
                                        <input type="date" name="DatePublication" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Volume</label>
                                        <input type="number" name="Volume" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Numéro</label>
                                        <input type="number" name="Numero" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Page début </label>
                                        <input type="number" name="PageDebut" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Page fin</label>
                                        <input type="number" name="PageFin" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">Informations complémentaires</h6>
                        </div>
                        <div class="card-body">
                            <!-- DOI -->
                            <div class="form-group">
                                <label>DOI</label>
                                <input type="text" name="doi" class="form-control">
                            </div>

                            <!-- Lien -->
                            <div class="form-group">
                                <label>Lien de l'article</label>
                                <input type="url" name="lien" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal pour devenir co-auteur -->
<div class="modal fade" id="coAuteurModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Devenir Co-auteur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('chercheur.ajouterCoAuteur') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Sélectionner les articles dont vous êtes co-auteur</label>
                        <select class="form-control" name="articles[]" multiple>
                            @foreach($allArticles as $article)
                                {{-- exclure les articles pour les quelles il est chercheur --}}
                                @if(!$article->isCoAuthor)
                                    <option value="{{ $article->idArticle }}">
                                        {{ $article->titreArticle }}
                                        {{-- @if($article->publication)
                                            ({{ $article->publication->titrePub }})
                                        @endif --}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let chercheurSelect = document.getElementById("chercheurSelect");
            let chercheurList = document.getElementById("chercheurList");
            let chercheurInput = document.getElementById("chercheurs_input");
            let rangInput = document.getElementById("rangs_input");

            let selectedChercheurs = [];

            chercheurSelect.addEventListener("change", function () {
                let chercheurId = this.value;
                let chercheurName = this.options[this.selectedIndex].text;

                if (chercheurId && !selectedChercheurs.find(c => c.id === chercheurId)) {
                    let chercheurItem = document.createElement("li");
                    chercheurItem.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
                    chercheurItem.dataset.id = chercheurId;

                    let rang = selectedChercheurs.length + 1;

                    chercheurItem.innerHTML = `
                        <span>${chercheurName} (Rang: <span class="rang">${rang}</span>)</span>
                        <button type="button" class="btn btn-danger btn-sm remove-chercheur">X</button>
                    `;

                    chercheurList.appendChild(chercheurItem);
                    selectedChercheurs.push({ id: parseInt(chercheurId), rang: rang });

                    updateHiddenFields();
                    this.value = "";
                }
            });

            chercheurList.addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-chercheur")) {
                    let chercheurItem = e.target.closest("li");
                    let chercheurId = parseInt(chercheurItem.dataset.id);

                    selectedChercheurs = selectedChercheurs.filter(c => c.id !== chercheurId);
                    chercheurItem.remove();

                    updateRanks();
                    updateHiddenFields();
                }
            });

            function updateRanks() {
                let items = chercheurList.children;
                selectedChercheurs.forEach((c, index) => {
                    c.rang = index + 1;
                    items[index].querySelector(".rang").textContent = c.rang;
                });
            }

            function updateHiddenFields() {
                let chercheurIds = selectedChercheurs.map(c => c.id);
                let rangs = selectedChercheurs.map(c => c.rang);

                chercheurInput.value = chercheurIds.join(",");  // Stocke les IDs sous forme de chaîne "4,3,1"
                rangInput.value = rangs.join(",");  // Stocke les rangs sous forme de chaîne "1,2,3"
            }
        });

    </script>


    <script>

        $(document).ready(function() {
            // Initialisation de Select2 pour tous les sélecteurs
            $('#chercheurs').select2({
                width: '100%',
                placeholder: 'Sélectionner...',
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
                    document.getElementById(`deleteArticleChercheurForm-${articleId}`).submit();
                }
            });
        }


    </script>


    <script>
        $(document).ready(function() {
            $('select[name="articles[]"]').select2({
                width: '100%',
                placeholder: 'Sélectionner les articles...',
                allowClear: true
            });
        });
    </script>

@endsection
