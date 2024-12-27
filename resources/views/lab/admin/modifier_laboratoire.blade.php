@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeLaboratoires') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un laboratoire</h2>
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

    <form action="{{ route('admin.updateLaboratoire', $laboratoire->idLabo) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Nom du laboratoire -->
        <div class="form-group mb-4">
            <label for="nomLabo">Nom du Laboratoire <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nomLabo') is-invalid @enderror"
                   id="nomLabo" name="nomLabo"
                   value="{{ old('nomLabo', $laboratoire->nomLabo) }}" required>
            @error('nomLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Année de création -->
        <div class="form-group mb-4">
            <label for="anneeCreation">Année de création</label>
            <input type="text" class="form-control @error('anneeCreation') is-invalid @enderror"
                   id="anneeCreation" name="anneeCreation"
                   value="{{ old('anneeCreation', $laboratoire->anneeCreation) }}">
            @error('anneeCreation')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Localisation -->
        <div class="form-group mb-4">
            <label for="localisationLabo">Localisation</label>
            <input type="text" class="form-control @error('localisationLabo') is-invalid @enderror"
                   id="localisationLabo" name="localisationLabo"
                   value="{{ old('localisationLabo', $laboratoire->localisationLabo) }}">
            @error('localisationLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Adresse -->
        <div class="form-group mb-4">
            <label for="adresseLabo">Adresse</label>
            <input type="text" class="form-control @error('adresseLabo') is-invalid @enderror"
                   id="adresseLabo" name="adresseLabo"
                   value="{{ old('adresseLabo', $laboratoire->adresseLabo) }}">
            @error('adresseLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Téléphone -->
        <div class="form-group mb-4">
            <label for="telLabo">Téléphone</label>
            <input type="text" class="form-control @error('telLabo') is-invalid @enderror"
                   id="telLabo" name="telLabo"
                   value="{{ old('telLabo', $laboratoire->telLabo) }}">
            @error('telLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Fax -->
        <div class="form-group mb-4">
            <label for="faxLabo">Fax</label>
            <input type="text" class="form-control @error('faxLabo') is-invalid @enderror"
                   id="faxLabo" name="faxLabo"
                   value="{{ old('faxLabo', $laboratoire->faxLabo) }}">
            @error('faxLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group mb-4">
            <label for="emailLabo">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('emailLabo') is-invalid @enderror"
                   id="emailLabo" name="emailLabo"
                   value="{{ old('emailLabo', $laboratoire->emailLabo) }}" required>
            @error('emailLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-4">
            <label for="descLabo">Description</label>
            <textarea class="form-control @error('descLabo') is-invalid @enderror"
                      id="descLabo" name="descLabo" rows="3">{{ old('descLabo', $laboratoire->descLabo) }}</textarea>
            @error('descLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- UMRI -->
        <div class="form-group mb-4">
            <label for="idUMRI">UMRI <span class="text-danger">*</span></label>
            <select class="form-control @error('idUMRI') is-invalid @enderror"
                    id="idUMRI" name="idUMRI" required>
                <option value="">-- Sélectionnez un UMRI --</option>
                @foreach ($umris as $umri)
                    <option value="{{ $umri->idUMRI }}"
                        {{ old('idUMRI', $laboratoire->idUMRI) == $umri->idUMRI ? 'selected' : '' }}>
                        {{ $umri->nomUMRI }}
                    </option>
                @endforeach
            </select>
            @error('idUMRI')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Axes de recherche -->
        <div class="form-group mb-4">
            <label for="axesRecherche">Axes de recherche</label>
            <select class="form-control @error('axesRecherche') is-invalid @enderror"
                    id="axesRecherche" name="axesRecherche[]" multiple>
                @foreach ($axesRecherches as $axe)
                    <option value="{{ $axe->idAxeRech }}"
                        {{ in_array($axe->idAxeRech, old('axesRecherche', $laboratoire->axesRecherches->pluck('idAxeRech')->toArray() ?? [])) ? 'selected' : '' }}>
                        {{ $axe->titreAxeRech }}
                    </option>
                @endforeach

            </select>
            @error('axesRecherche')
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
        // Initialisation de Select2 pour l'UMRI
        $('#idUMRI').select2({
            placeholder: '-- Sélectionnez un UMRI --',
            allowClear: true,
            width: '100%'
        });

        // Initialisation de Select2 pour les axes de recherche
        $('#axesRecherche').select2({
            placeholder: 'Sélectionnez les axes de recherche',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 5
        });
    });
</script>
@endsection
