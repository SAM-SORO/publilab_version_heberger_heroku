@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeTypePublications') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification du type de publication
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateTypePublication', $typePublication->idTypePub) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations principales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations du type</h5>
            </div>
            <div class="card-body">
                <!-- Libellé -->
                <div class="form-group mb-4">
                    <label for="libeleTypePub" class="font-weight-bold">
                        Libellé du type de publication <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control form-control-lg @error('libeleTypePub') is-invalid @enderror"
                           id="libeleTypePub" name="libeleTypePub"
                           value="{{ old('libeleTypePub', $typePublication->libeleTypePub) }}" required>
                    @error('libeleTypePub')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="descTypePub" class="font-weight-bold">Description</label>
                    <textarea class="form-control @error('descTypePub') is-invalid @enderror"
                              id="descTypePub" name="descTypePub" rows="4">{{ old('descTypePub', $typePublication->descTypePub) }}</textarea>
                    @error('descTypePub')
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
