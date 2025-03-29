@extends('baseChercheur')

@section('content')
<div class="container mt-4">
    @include('lab.partials.alerts')

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier l'article</h5>
            <a href="{{ route('chercheur.listeArticles') }}" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('chercheur.updateArticle', $article->idArticle) }}" method="POST">
                @csrf

                <!-- Informations de base -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Informations de base</h6>
                    </div>
                    <div class="card-body">
                        <!-- Titre -->
                        <div class="form-group">
                            <label for="titreArticle">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="titreArticle" id="titreArticle"
                                   class="form-control @error('titreArticle') is-invalid @enderror"
                                   value="{{ old('titreArticle', $article->titreArticle) }}" required>
                            @error('titreArticle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type d'article -->
                        <div class="form-group">
                            <label for="idTypeArticle">Type d'article</label>
                            <select name="idTypeArticle" id="idTypeArticle" class="form-control">
                                <option value="">Sélectionner un type</option>
                                @foreach($typeArticles as $type)
                                    <option value="{{ $type->idTypeArticle }}"
                                        {{ old('idTypeArticle', $article->idTypeArticle) == $type->idTypeArticle ? 'selected' : '' }}>
                                        {{ $type->nomTypeArticle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idTypeArticle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Co-auteurs chercheurs -->
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
                            <small><strong>Liste des chercheurs co-auteurs</strong></small>
                            <ul id="chercheurList" class="list-group">
                                @foreach($article->chercheurs as $chercheur)
                                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $chercheur->idCherch }}">
                                        <span>
                                            {{ strtoupper($chercheur->nomCherch) }} {{ $chercheur->prenomCherch }}
                                            (Rang: <span class="rang">{{ $chercheur->pivot->rang }}</span>)
                                        </span>
                                        <button type="button" class="btn btn-danger btn-sm remove-chercheur">X</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Champs cachés pour les données des chercheurs -->
                        <input type="hidden" name="chercheurs" id="chercheurs_input" value="{{ $article->chercheurs->pluck('idCherch')->join(',') }}">
                        <input type="hidden" name="rangs" id="rangs_input" value="{{ $article->chercheurs->pluck('pivot.rang')->join(',') }}">

                        <!-- Co-auteurs doctorants -->
                        <div class="form-group">
                            <label for="doctorants">doctorants</label>
                            <select name="doctorants[]" id="doctorants" class="form-control" multiple>
                                @foreach($doctorants as $doctorant)
                                    <option value="{{ $doctorant->idDoc }}"
                                        {{ in_array($doctorant->idDoc, $doctorantIds) ? 'selected' : '' }}>
                                        {{ $doctorant->prenomDoc }} {{ $doctorant->nomDoc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctorants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Résumé -->
                        <div class="form-group">
                            <label for="resumeArticle">Résumé</label>
                            <textarea name="resumeArticle" id="resumeArticle" class="form-control @error('resumeArticle') is-invalid @enderror"
                                      rows="4">{{ old('resumeArticle', $article->resumeArticle) }}</textarea>
                            @error('resumeArticle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <select name="idPub" id="idPub" class="form-control">
                                <option value="">Sélectionner une publication</option>
                                @foreach($publications as $publication)
                                    <option value="{{ $publication->idPub }}"
                                        {{ old('idPub', $article->idPub) == $publication->idPub ? 'selected' : '' }}>
                                        {{ $publication->titrePub }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idPub')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Date de publication -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="datePubArt">Date de publication</label>
                                    <input type="date" name="datePubArt" id="datePubArt"
                                           class="form-control @error('datePubArt') is-invalid @enderror"
                                           value="{{ old('datePubArt', $article->datePubArt ? date('Y-m-d', strtotime($article->datePubArt)) : '') }}">
                                    @error('datePubArt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Volume -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="volume">Volume</label>
                                    <input type="number" name="volume" id="volume"
                                           class="form-control @error('volume') is-invalid @enderror"
                                           value="{{ old('volume', $article->volume) }}">
                                    @error('volume')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Numéro -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numero">Numéro</label>
                                    <input type="number" name="numero" id="numero"
                                           class="form-control @error('numero') is-invalid @enderror"
                                           value="{{ old('numero', $article->numero) }}">
                                    @error('numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Page début -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pageDebut">Page début</label>
                                    <input type="number" name="pageDebut" id="pageDebut"
                                           class="form-control @error('pageDebut') is-invalid @enderror"
                                           value="{{ old('pageDebut', $article->pageDebut) }}">
                                    @error('pageDebut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Page fin -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pageFin">Page fin</label>
                                    <input type="number" name="pageFin" id="pageFin"
                                           class="form-control @error('pageFin') is-invalid @enderror"
                                           value="{{ old('pageFin', $article->pageFin) }}">
                                    @error('pageFin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Informations complémentaires</h6>
                    </div>
                    <div class="card-body">
                        <!-- DOI -->
                        <div class="form-group">
                            <label for="doi">DOI</label>
                            <input type="text" name="doi" id="doi"
                                   class="form-control @error('doi') is-invalid @enderror"
                                   value="{{ old('doi', $article->doi) }}">
                            @error('doi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lien -->
                        <div class="form-group">
                            <label for="lienArticle">Lien de l'article</label>
                            <input type="url" name="lienArticle" id="lienArticle"
                                   class="form-control @error('lienArticle') is-invalid @enderror"
                                   value="{{ old('lienArticle', $article->lienArticle) }}">
                            @error('lienArticle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bouton d'action -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
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
        // Initialisation de Select2 pour les sélecteurs multiples
        $('#chercheurs').select2({
            width: '100%',
            placeholder: 'Sélectionner des chercheurs...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucun chercheur trouvé";
                },
                searching: function() {
                    return "Recherche...";
                }
            }
        });

        $('#doctorants').select2({
            width: '100%',
            placeholder: 'Sélectionner des doctorants...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucun doctorant trouvé";
                },
                searching: function() {
                    return "Recherche...";
                }
            }
        });

        $('#idTypeArticle').select2({
            width: '100%',
            placeholder: 'Sélectionner un type...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucun type trouvé";
                },
                searching: function() {
                    return "Recherche...";
                }
            }
        });

        $('#idPub').select2({
            width: '100%',
            placeholder: 'Sélectionner une publication...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucune publication trouvée";
                },
                searching: function() {
                    return "Recherche...";
                }
            }
        });

        // Ajuster la hauteur des sélecteurs Select2
        $('.select2-selection').css('min-height', '40px');
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let chercheurSelect = document.getElementById("chercheurSelect");
        let chercheurList = document.getElementById("chercheurList");
        let chercheurInput = document.getElementById("chercheurs_input");
        let rangInput = document.getElementById("rangs_input");

        // Initialiser le tableau des chercheurs sélectionnés avec les données existantes
        let selectedChercheurs = Array.from(chercheurList.children).map(item => ({
            id: parseInt(item.dataset.id),
            rang: parseInt(item.querySelector('.rang').textContent)
        }));

        chercheurSelect.addEventListener("change", function () {
            let chercheurId = this.value;
            let chercheurName = this.options[this.selectedIndex].text;

            if (chercheurId && !selectedChercheurs.find(c => c.id === parseInt(chercheurId))) {
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

            chercheurInput.value = chercheurIds.join(",");
            rangInput.value = rangs.join(",");
        }
    });
</script>
@endsection
