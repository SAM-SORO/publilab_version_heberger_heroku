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

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex align-items-center">
            <a href="{{ route('doctorant.listeArticles') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            <h5 class="mb-0 ml-5">
                </i>Modifier l'article
            </h5>

        </div>
        <div class="card-body">
            <form action="{{ route('doctorant.updateArticle', $article->idArticle) }}" method="POST">
                @csrf
                @method('POST')

                <!-- Informations de base -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Informations de base</h6>
                    </div>
                    <div class="card-body">
                        <!-- Titre -->
                        <div class="form-group">
                            <label for="titreArticle">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="titreArticle" id="titreArticle" class="form-control" required value="{{ old('titreArticle', $article->titreArticle) }}">
                        </div>

                        <!-- Type d'article -->
                        <div class="form-group">
                            <label for="idTypeArticle">Type d'article</label>
                            <select name="idTypeArticle" id="idTypeArticle" class="form-control">
                                <option value="">Sélectionner un type</option>
                                @foreach($typeArticles as $type)
                                    <option value="{{ $type->idTypeArticle }}" {{ old('idTypeArticle', $article->idTypeArticle) == $type->idTypeArticle ? 'selected' : '' }}>
                                        {{ $type->nomTypeArticle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Co-auteurs chercheurs -->
                        <div class="form-group">
                            <label for="chercheurs">Co-auteurs chercheurs <span class="text-danger">*</span></label>
                            <select name="chercheurs[]" id="chercheurs" class="form-control" multiple>
                                @foreach($chercheurs as $chercheur)
                                    <option value="{{ $chercheur->idCherch }}"
                                        {{ in_array($chercheur->idCherch, old('chercheurs', $articleChercheurs)) ? 'selected' : '' }}>
                                        {{ $chercheur->prenomCherch }} {{ strtoupper($chercheur->nomCherch) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Résumé -->
                        <div class="form-group">
                            <label for="resumeArticle">Résumé</label>
                            <textarea name="resumeArticle" id="resumeArticle" class="form-control" rows="3">{{ old('resumeArticle', $article->resumeArticle) }}</textarea>
                        </div>

                        <!-- DOI -->
                        <div class="form-group">
                            <label for="doi">DOI</label>
                            <input type="text" name="doi" id="doi" class="form-control" value="{{ old('doi', $article->doi) }}">
                        </div>

                        <!-- Lien -->
                        <div class="form-group">
                            <label for="lienArticle">Lien</label>
                            <input type="url" name="lienArticle" id="lienArticle" class="form-control" placeholder="https://..." value="{{ old('lienArticle', $article->lienArticle) }}">
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
                                    <option value="{{ $publication->idPub }}" {{ old('idPub', $article->idPub) == $publication->idPub ? 'selected' : '' }}>
                                        {{ $publication->titrePub }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date de publication -->
                        <div class="form-group">
                            <label for="datePubArt">Date de publication</label>
                            <input type="date" name="datePubArt" id="datePubArt" class="form-control" value="{{ old('datePubArt', $article->datePubArt ? date('Y-m-d', strtotime($article->datePubArt)) : '') }}">
                        </div>

                        <div class="row">
                            <!-- Volume -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="volume">Volume</label>
                                    <input type="number" name="volume" id="volume" class="form-control" min="1" value="{{ old('volume', $article->volume) }}">
                                </div>
                            </div>

                            <!-- Numéro -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero">Numéro</label>
                                    <input type="number" name="numero" id="numero" class="form-control" min="1" value="{{ old('numero', $article->numero) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Page début -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pageDebut">Page de début</label>
                                    <input type="number" name="pageDebut" id="pageDebut" class="form-control" min="1" value="{{ old('pageDebut', $article->pageDebut) }}">
                                </div>
                            </div>

                            <!-- Page fin -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pageFin">Page de fin</label>
                                    <input type="number" name="pageFin" id="pageFin" class="form-control" min="1" value="{{ old('pageFin', $article->pageFin) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bouton d'enregistrement -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Enregistrer les modifications
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
                    return "Aucun type trouvé";
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
                    return "Aucune publication trouvée";
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
</script>
@endsection
