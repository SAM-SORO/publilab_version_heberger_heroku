@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeEdp') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un EDP</h2>
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

    <form action="{{ route('admin.updateEdp', $edp->idEDP) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Nom de l'EDP -->
        <div class="form-group mb-4">
            <label for="nomEDP">Nom de l'EDP <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nomEDP') is-invalid @enderror"
                   id="nomEDP" name="nomEDP"
                   value="{{ old('nomEDP', $edp->nomEDP) }}" required>
            @error('nomEDP')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Localisation -->
        <div class="form-group mb-4">
            <label for="localisationEDP">Localisation</label>
            <input type="text" class="form-control @error('localisationEDP') is-invalid @enderror"
                   id="localisationEDP" name="localisationEDP"
                   value="{{ old('localisationEDP', $edp->localisationEDP) }}">
            @error('localisationEDP')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- WhatsApp -->
        <div class="form-group mb-4">
            <label for="WhatsAppUMI">WhatsApp</label>
            <input type="text" class="form-control @error('WhatsAppUMI') is-invalid @enderror"
                   id="WhatsAppUMI" name="WhatsAppUMI"
                   value="{{ old('WhatsAppUMI', $edp->WhatsAppUMI) }}">
            @error('WhatsAppUMI')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group mb-4">
            <label for="emailUMI">Email</label>
            <input type="email" class="form-control @error('emailUMI') is-invalid @enderror"
                   id="emailUMI" name="emailUMI"
                   value="{{ old('emailUMI', $edp->emailUMI) }}">
            @error('emailUMI')
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
