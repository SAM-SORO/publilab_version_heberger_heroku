@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeAxeRecherche') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification de l'axe de recherche
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateAxeRecherche', $axeRecherche->idAxeRech) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations de l'axe -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle"></i> Informations de l'axe
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="titreAxeRech" class="font-weight-bold">
                                Titre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('titreAxeRech') is-invalid @enderror"
                                   id="titreAxeRech" name="titreAxeRech" 
                                   value="{{ old('titreAxeRech', $axeRecherche->titreAxeRech) }}" required>
                            @error('titreAxeRech')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label for="descAxeRech" class="font-weight-bold">Description</label>
                            <textarea class="form-control @error('descAxeRech') is-invalid @enderror"
                                      id="descAxeRech" name="descAxeRech" rows="4"
                                      style="resize: none;">{{ old('descAxeRech', $axeRecherche->descAxeRech) }}</textarea>
                            @error('descAxeRech')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1">
                            <i class="fas fa-bookmark text-secondary"></i>
                            <span class="font-weight-bold">Thèmes associés :</span>
                            <span class="badge badge-info">{{ $axeRecherche->themes->count() }}</span>
                        </p>
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

@section('styles')
<style>
    .form-control {
        height: calc(2.25rem + 2px);
    }

    textarea.form-control {
        height: auto;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
</style>
@endsection
