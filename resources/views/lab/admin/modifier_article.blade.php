@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeArticles') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification de l'article
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateArticle', $article->idArticle) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations principales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Informations générales</h5>
            </div>
            <div class="card-body">
                <!-- Titre de l'article -->
                <div class="form-group">
                    <label for="titreArticle" class="font-weight-bold">
                        Titre de l'article <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('titreArticle') is-invalid @enderror"
                        id="titreArticle" name="titreArticle" value="{{ old('titreArticle', $article->titreArticle) }}" required>
                    @error('titreArticle')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Résumé -->
                <div class="form-group">
                    <label for="resumeArticle" class="font-weight-bold">Résumé</label>
                    <textarea class="form-control @error('resumeArticle') is-invalid @enderror"
                        id="resumeArticle" name="resumeArticle" rows="4">{{ old('resumeArticle', $article->resumeArticle) }}</textarea>
                    @error('resumeArticle')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- DOI -->
                <div class="form-group">
                    <label for="doi" class="font-weight-bold">DOI</label>
                    <input type="text" class="form-control @error('doi') is-invalid @enderror"
                        id="doi" name="doi" value="{{ old('doi', $article->doi) }}">
                    @error('doi')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Lien de l'article -->
                <div class="form-group">
                    <label for="lienArticle" class="font-weight-bold">Lien de l'article</label>
                    <input type="url" class="form-control @error('lienArticle') is-invalid @enderror"
                        id="lienArticle" name="lienArticle" value="{{ old('lienArticle', $article->lienArticle) }}">
                    @error('lienArticle')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date de publication -->
                <div class="form-group">
                    <input type="date" class="form-control @error('datePubArt') is-invalid @enderror"
                        id="datePubArt" name="datePubArt" value="{{ old('datePubArt', $article->datePubArt ? $article->datePubArt->format('Y-m-d') : '') }}">
                    @error('datePubArt')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Classification -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-tags mr-2"></i> Classification</h5>
            </div>
            <div class="card-body">
                <!-- Type d'article -->
                <div class="form-group">
                    <label for="idTypeArticle" class="font-weight-bold">Type d'article</label>
                    <select class="form-control select2 @error('idTypeArticle') is-invalid @enderror"
                        id="idTypeArticle" name="idTypeArticle">
                        <option value="">Sélectionner un type</option>
                        @foreach($typeArticles as $type)
                            <option value="{{ $type->idTypeArticle }}" {{ old('idTypeArticle', $article->idTypeArticle) == $type->idTypeArticle ? 'selected' : '' }}>
                                {{ $type->nomTypeArticle }}
                            </option>
                        @endforeach
                    </select>
                    @error('idTypeArticle')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Publication -->
                <div class="form-group">
                    <label for="idPub" class="font-weight-bold">Publication</label>
                    <select class="form-control select2 @error('idPub') is-invalid @enderror"
                        id="idPub" name="idPub">
                        <option value="">Sélectionner une publication</option>
                        @foreach($publications as $publication)
                            <option value="{{ $publication->idPub }}" {{ old('idPub', $article->idPub) == $publication->idPub ? 'selected' : '' }}>
                                {{ $publication->titrePub }}
                            </option>
                        @endforeach
                    </select>
                    @error('idPub')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Détails de publication -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-book mr-2"></i> Détails de publication</h5>
            </div>
            <div class="card-body">
                <!-- Volume et Numéro sur la même ligne -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="volume" class="font-weight-bold">Volume</label>
                            <input type="number" class="form-control @error('volume') is-invalid @enderror"
                                id="volume" name="volume" value="{{ old('volume', $article->volume) }}">
                            @error('volume')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero" class="font-weight-bold">Numéro</label>
                            <input type="number" class="form-control @error('numero') is-invalid @enderror"
                                id="numero" name="numero" value="{{ old('numero', $article->numero) }}">
                            @error('numero')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pages de début et fin sur la même ligne -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pageDebut" class="font-weight-bold">Page de début</label>
                            <input type="number" class="form-control @error('pageDebut') is-invalid @enderror"
                                id="pageDebut" name="pageDebut" value="{{ old('pageDebut', $article->pageDebut) }}">
                            @error('pageDebut')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pageFin" class="font-weight-bold">Page de fin</label>
                            <input type="number" class="form-control @error('pageFin') is-invalid @enderror"
                                id="pageFin" name="pageFin" value="{{ old('pageFin', $article->pageFin) }}">
                            @error('pageFin')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auteurs -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-users mr-2"></i> Auteurs</h5>
            </div>
            <div class="card-body">
                <!-- Chercheurs -->
                <div class="form-group">
                    <label for="chercheurs" class="font-weight-bold">
                        Chercheurs <span class="text-danger">*</span>
                    </label>
                    <small class="form-text text-muted mb-2">L'ordre de sélection détermine l'ordre des auteurs</small>
                    <select class="form-control select2 @error('chercheurs') is-invalid @enderror"
                        id="chercheurs" name="chercheurs[]" multiple="multiple" required>
                        @foreach($chercheurs as $chercheur)
                            <option value="{{ $chercheur->idCherch }}"
                                {{ in_array($chercheur->idCherch, $chercheurIds) ? 'selected' : '' }}>
                                {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                            </option>
                        @endforeach
                    </select>
                    @error('chercheurs')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Doctorants -->
                <div class="form-group">
                    <label for="doctorants" class="font-weight-bold">Doctorants</label>
                    <small class="form-text text-muted mb-2">doctorants ayant rediger l'article</small>
                    <select class="form-control select2 @error('doctorants') is-invalid @enderror"
                        id="doctorants" name="doctorants[]" multiple>
                        @foreach($doctorants as $doctorant)
                            <option value="{{ $doctorant->idDoc }}"
                                {{ in_array($doctorant->idDoc, $doctorantIds) ? 'selected' : '' }}>
                                {{ $doctorant->nomDoc }} {{ $doctorant->prenomDoc }}
                                @if(count($doctorant->encadrants) > 0)
                                    (Encadré par:
                                    @foreach($doctorant->encadrants as $key => $encadrant)
                                        {{ $encadrant->nomCherch }} {{ $encadrant->prenomCherch }}
                                        @if($key < count($doctorant->encadrants) - 1), @endif
                                    @endforeach)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('doctorants')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Bouton de soumission -->
        <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de Select2 pour tous les sélecteurs
        $('.select2').select2({
            width: '100%',
            placeholder: 'Sélectionner...',
            allowClear: true
        });

        $('.select2-selection').css('min-height', '38px'); // Applique la hauteur après initialisation
    });
</script>
@endsection
