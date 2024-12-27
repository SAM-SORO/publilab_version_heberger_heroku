@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeAxeRecherche') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un axe de recherche</h2>
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

    <form action="{{ route('admin.updateAxeRecherche', $axeRecherche->idAxeRech) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Titre de l'axe -->
        <div class="form-group mb-4">
            <label for="titreAxeRech">Titre <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('titreAxeRech') is-invalid @enderror"
                   id="titreAxeRech" name="titreAxeRech"
                   value="{{ old('titreAxeRech', $axeRecherche->titreAxeRech) }}" required>
            @error('titreAxeRech')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description de l'axe -->
        <div class="form-group mb-4">
            <label for="descAxeRech">Description</label>
            <textarea class="form-control @error('descAxeRech') is-invalid @enderror"
                      id="descAxeRech" name="descAxeRech" rows="3">{{ old('descAxeRech', $axeRecherche->descAxeRech) }}</textarea>
            @error('descAxeRech')
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
