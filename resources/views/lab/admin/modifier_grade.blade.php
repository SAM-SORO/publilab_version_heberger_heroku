@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeGrade') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un grade</h2>
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

    <form action="{{ route('admin.updateGrade', $grade->idGrade) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Nom du grade -->
        <div class="form-group mb-4">
            <label for="nomGrade">Nom du Grade <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nomGrade') is-invalid @enderror"
                   id="nomGrade" name="nomGrade"
                   value="{{ old('nomGrade', $grade->nomGrade) }}" required>
            @error('nomGrade')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Sigle du grade -->
        <div class="form-group mb-4">
            <label for="sigleGrade">Sigle</label>
            <input type="text" class="form-control @error('sigleGrade') is-invalid @enderror"
                   id="sigleGrade" name="sigleGrade"
                   value="{{ old('sigleGrade', $grade->sigleGrade) }}">
            @error('sigleGrade')
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
