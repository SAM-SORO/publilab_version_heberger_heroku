@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeDoctorant') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un doctorant</h2>
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

    <form action="{{ route('admin.updateDoctorant', $doctorant->idDoc) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Nom -->
        <div class="form-group mb-4">
            <label for="nomDoc">Nom <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nomDoc') is-invalid @enderror"
                   id="nomDoc" name="nomDoc"
                   value="{{ old('nomDoc', $doctorant->nomDoc) }}" required>
            @error('nomDoc')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Prénom -->
        <div class="form-group mb-4">
            <label for="prenomDoc">Prénom</label>
            <input type="text" class="form-control @error('prenomDoc') is-invalid @enderror"
                   id="prenomDoc" name="prenomDoc"
                   value="{{ old('prenomDoc', $doctorant->prenomDoc) }}">
            @error('prenomDoc')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Thème de recherche -->
        <div class="form-group mb-4">
            <label for="idTheme">Thème de Recherche <span class="text-danger">*</span></label>
            <select class="form-control @error('idTheme') is-invalid @enderror"
                    id="idTheme" name="idTheme" required>
                <option value="">-- Sélectionnez un Thème --</option>
                @foreach ($themes as $theme)
                    <option value="{{ $theme->idTheme }}"
                        {{ old('idTheme', $doctorant->theme ? $doctorant->theme->idTheme : '') == $theme->idTheme ? 'selected' : '' }}>
                        {{ $theme->descTheme }}
                    </option>
                @endforeach
            </select>
            @error('idTheme')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Chercheurs Encadrants -->
        <div class="form-group mb-4">
            <label for="idCherch">Chercheurs Encadrants <span class="text-danger">*</span></label>
            <select class="form-control @error('idCherch') is-invalid @enderror"
                    id="encadreur" name="idCherch[]" multiple required>
                @foreach ($chercheurs as $chercheur)
                    <option value="{{ $chercheur->idCherch }}"
                        {{ in_array($chercheur->idCherch, old('idCherch', $doctorant->encadrants->pluck('idCherch')->toArray())) ? 'selected' : '' }}>
                        {{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}
                    </option>
                @endforeach
            </select>
            @error('idCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Date de début -->
        <div class="form-group mb-4">
            <label for="dateDebut">Date de Début <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('dateDebut') is-invalid @enderror"
                   id="dateDebut" name="dateDebut"
                   value="{{ old('dateDebut', $doctorant->encadrants->first() ? $doctorant->encadrants->first()->pivot->dateDebut : '') }}" required>
            @error('dateDebut')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Date de fin -->
        <div class="form-group mb-4">
            <label for="dateFin">Date de Fin</label>
            <input type="date" class="form-control @error('dateFin') is-invalid @enderror"
                   id="dateFin" name="dateFin"
                   value="{{ old('dateFin', $doctorant->encadrants->first() ? $doctorant->encadrants->first()->pivot->dateFin : '') }}">
            @error('dateFin')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Bouton de soumission -->
        <div class="form-group mb-4 text-center">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Sauvegarder les modifications
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de select2 pour les encadrants
        $('#encadreur').select2({
            placeholder: 'Sélectionnez un ou plusieurs Encadrants',
            allowClear: true,
            width: '100%'
        });

        // Initialisation de select2 pour le thème
        $('#idTheme').select2({
            placeholder: 'Sélectionnez un thème',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
