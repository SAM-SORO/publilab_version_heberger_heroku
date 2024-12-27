@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.liste-articles') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un article</h2>
    </div>

    <div class="mb-5">
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

    <form action="{{ route('admin.updateArticle', $article->idArticle) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Titre de l'article -->
        <div class="form-group mb-4">
            <label for="titreArticle">Titre de l'article</label>
            <input type="text" class="form-control @error('titreArticle') is-invalid @enderror"
                   id="titreArticle" name="titreArticle" value="{{ old('titreArticle', $article->titreArticle) }}" required>
            @error('titreArticle')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Résumé de l'article -->
        <div class="form-group mb-4">
            <label for="resumeArticle">Résumé</label>
            <textarea class="form-control @error('resumeArticle') is-invalid @enderror"
                      id="resumeArticle" name="resumeArticle" rows="3">{{ old('resumeArticle', $article->resumeArticle) }}</textarea>
            @error('resumeArticle')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- DOI de l'article -->
        <div class="form-group mb-4">
            <label for="doi">DOI</label>
            <input type="text" class="form-control @error('doi') is-invalid @enderror"
                   id="doi" name="doi" value="{{ old('doi', $article->doi) }}">
            @error('doi')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Date de publication -->
        <div class="form-group mb-4">
            <label for="datePubArt">Date de publication</label>
            <input type="date" class="form-control @error('datePubArt') is-invalid @enderror"
                   id="datePubArt" name="datePubArt" value="{{ old('datePubArt', $article->revues->first()->pivot->datePubArt ?? '') }}">
            @error('datePubArt')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Chercheurs -->
        <div class="form-group mb-4">
            <label for="chercheurs">Chercheurs</label>
            <select class="form-control @error('chercheurs') is-invalid @enderror" id="chercheurs" name="chercheurs[]" multiple>
                @foreach ($chercheurs as $chercheur)
                    <option value="{{ $chercheur->idCherch }}"
                        {{ in_array($chercheur->idCherch, old('chercheurs', $article->chercheurs->pluck('idCherch')->toArray())) ? 'selected' : '' }}>
                        {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                    </option>
                @endforeach
            </select>
            @error('chercheurs')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Revue -->
        <div class="form-group mb-4">
            <label for="revue">Revue</label>
            <select class="form-control @error('revue') is-invalid @enderror" id="revue" name="revue" multiple>
                @foreach ($revues as $revue)
                    <option value="{{ $revue->idRevue }}" {{ old('revue', $article->revues->first()->idRevue ?? '') == $revue->idRevue ? 'selected' : '' }}>
                        {{ $revue->nomRevue }}
                    </option>
                @endforeach
            </select>
            @error('revue')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Volume -->
        <div class="form-group mb-4">
            <label for="volume">Volume</label>
            <input type="number" class="form-control @error('volume') is-invalid @enderror"
                   id="volume" name="volume" value="{{ old('volume', $article->revues->first()->pivot->volume ?? '') }}">
            @error('volume')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Numéro -->
        <div class="form-group mb-4">
            <label for="numero">Numéro</label>
            <input type="text" class="form-control @error('numero') is-invalid @enderror"
                   id="numero" name="numero" value="{{ old('numero', $article->revues->first()->pivot->numero ?? '') }}">
            @error('numero')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Pages -->
        <div class="form-group mb-4">
            <label for="pageDebut">Page de début</label>
            <input type="number" class="form-control @error('pageDebut') is-invalid @enderror"
                   id="pageDebut" name="pageDebut" value="{{ old('pageDebut', $article->revues->first()->pivot->pageDebut ?? '') }}">
            @error('pageDebut')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="pageFin">Page de fin</label>
            <input type="number" class="form-control @error('pageFin') is-invalid @enderror"
                   id="pageFin" name="pageFin" value="{{ old('pageFin', $article->revues->first()->pivot->pageFin ?? '') }}">
            @error('pageFin')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>


</div>
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de Select2 pour chercheurs
        $('#chercheurs').select2({
            placeholder: 'Sélectionnez les chercheurs',
            allowClear: true,
            width: '100%'
        });

        // Initialisation de Select2 pour revue
        $('#revue').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            width: '100%'
        });

        // Activation/Désactivation des champs dépendants de la revue
        $('#revue').on('change', function() {
            const revueSelected = $(this).val();
            $('#volume, #numero, #pageDebut, #pageFin').prop('disabled', !revueSelected);
        });

        // Initialisation de l'état des champs
        const revueSelected = $('#revue').val();
        $('#volume, #numero ,#pageDebut, #pageFin').prop('disabled', !revueSelected);
    });

</script>
@endsection
