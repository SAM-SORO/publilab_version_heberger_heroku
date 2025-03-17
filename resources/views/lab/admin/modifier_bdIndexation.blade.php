@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeBaseIndexation') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification de la base d'indexation
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include('lab.partials.alerts')
    </div>

    <form action="{{ route('admin.updateBaseIndexation', $bdIndexation->idBDIndex) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations de la base d'indexation -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-database"></i> Informations de la base d'indexation</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="nomBDInd" class="font-weight-bold">
                        Nom de la Base d'Indexation <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('nomBDInd') is-invalid @enderror"
                           id="nomBDInd" name="nomBDInd"
                           value="{{ old('nomBDInd', $bdIndexation->nomBDInd) }}" required>
                    @error('nomBDInd')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
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
