@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeTheme') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification du thème
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateTheme', $theme->idTheme) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations principales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations du thème</h5>
            </div>
            <div class="card-body">
                <!-- Intitulé du thème -->
                <div class="form-group mb-4">
                    <label for="intituleTheme" class="font-weight-bold">
                        Intitulé du thème <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control form-control-lg @error('intituleTheme') is-invalid @enderror"
                           id="intituleTheme" name="intituleTheme"
                           value="{{ old('intituleTheme', $theme->intituleTheme) }}" required>
                    @error('intituleTheme')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="descTheme" class="font-weight-bold">Description</label>
                    <textarea class="form-control @error('descTheme') is-invalid @enderror"
                              id="descTheme" name="descTheme" rows="4">{{ old('descTheme', $theme->descTheme) }}</textarea>
                    @error('descTheme')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Axe de recherche -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-compass"></i> Rattachement</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="idAxeRech" class="font-weight-bold">
                        Axe de recherche <span class="text-danger">*</span>
                    </label>
                    <select class="form-control @error('idAxeRech') is-invalid @enderror"
                            id="idAxeRech" name="idAxeRech" required>
                        <option value="">Sélectionner un axe de recherche</option>
                        @foreach($axesRecherches as $axe)
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
            </div>
        </div>

        <!-- État d'attribution -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle"></i> État d'attribution</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input"
                               id="etatAttribution" name="etatAttribution" value="1"
                               {{ old('etatAttribution', $theme->etatAttribution) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="etatAttribution">
                            Thème attribué
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton de soumission -->
        <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                <i class="fas fa-save mr-2"></i> Sauvegarder les modifications
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#idAxeRech').select2({
            placeholder: 'Sélectionner un axe de recherche',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
        });


        $('.select2-selection').css('height', '40px'); // Applique la hauteur après initialisation

    });
</script>
@endsection
