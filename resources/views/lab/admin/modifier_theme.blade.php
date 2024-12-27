@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeTheme') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un thème</h2>
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
    <form action="{{ route('admin.updateTheme', $theme->idTheme) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Intitulé du thème -->
        <div class="form-group mb-4">
            <label for="intituleTheme">Intitulé du Thème <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('intituleTheme') is-invalid @enderror"
                   id="intituleTheme" name="intituleTheme"
                   value="{{ old('intituleTheme', $theme->intituleTheme) }}" required>
            @error('intituleTheme')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description du thème -->
        <div class="form-group mb-4">
            <label for="descTheme">Description du Thème</label>
            <textarea class="form-control @error('descTheme') is-invalid @enderror"
                      id="descTheme" name="descTheme" rows="3">{{ old('descTheme', $theme->descTheme) }}</textarea>
            @error('descTheme')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Axe de recherche -->
        <div class="form-group mb-4">
            <label for="idAxeRech">Axe de Recherche <span class="text-danger">*</span></label>
            <select class="form-control @error('idAxeRech') is-invalid @enderror"
                    id="idAxeRech" name="idAxeRech" required>
                <option value="">-- Sélectionnez un Axe de Recherche --</option>
                @foreach ($axesRecherches as $axe)
                    <option value="{{ $axe->idAxeRech }}"
                        {{ old('idAxeRech', $theme->idAxeRech) == $axe->idAxeRech ? 'selected' : '' }}>
                        {{ $axe->titreAxeRech }}
                    </option>
                @endforeach
            </select>
            @error('idAxeRech')
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
        // Initialisation de Select2 pour l'axe de recherche
        $('#idAxeRech').select2({
            placeholder: '-- Sélectionnez un Axe de Recherche --',
            maximumSelectionLength: 1,
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
