@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeChercheurs') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un chercheur</h2>
    </div>

    <div class="mb-5">
        {{-- Gestion des messages d'erreurs et de succès --}}
        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <form action="{{ route('admin.updateLaboChercheur', $chercheur->idCherch) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Nom -->
        <div class="form-group mb-4">
            <label for="nomCherch">Nom</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control @error('nomCherch') is-invalid @enderror"
                       id="nomCherch" name="nomCherch"
                       value="{{ old('nomCherch', $chercheur->nomCherch) }}" required>
            </div>
            @error('nomCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Prénom -->
        <div class="form-group mb-4">
            <label for="prenomCherch">Prénom</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                <input type="text" class="form-control @error('prenomCherch') is-invalid @enderror"
                       id="prenomCherch" name="prenomCherch"
                       value="{{ old('prenomCherch', $chercheur->prenomCherch) }}" required>
            </div>
            @error('prenomCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Adresse -->
        <div class="form-group mb-4">
            <label for="adresse">Adresse</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                       id="adresse" name="adresse"
                       value="{{ old('adresse', $chercheur->adresse) }}">
            </div>
            @error('adresse')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Téléphone -->
        <div class="form-group mb-4">
            <label for="telCherch">Téléphone</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                <input type="text" class="form-control @error('telCherch') is-invalid @enderror"
                       id="telCherch" name="telCherch"
                       value="{{ old('telCherch', $chercheur->telCherch) }}">
            </div>
            @error('telCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group mb-4">
            <label for="emailCherch">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control @error('emailCherch') is-invalid @enderror"
                       id="emailCherch" name="emailCherch"
                       value="{{ old('emailCherch', $chercheur->emailCherch) }}" required>
            </div>
            @error('emailCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nouveau Mot de Passe -->
        <div class="form-group mb-4">
            <label for="password">Nouveau Mot de Passe (laisser vide pour ne pas modifier)</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password">
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirmation du mot de passe -->
        <div class="form-group mb-4">
            <label for="password_confirmation">Confirmer le Nouveau Mot de Passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                       id="password_confirmation" name="password_confirmation">
            </div>
            @error('password_confirmation')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Spécialité -->
        <div class="form-group mb-4">
            <label for="specialite">Spécialité</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-book"></i></span>
                <input type="text" class="form-control @error('specialite') is-invalid @enderror"
                       id="specialite" name="specialite"
                       value="{{ old('specialite', $chercheur->specialite) }}">
            </div>
            @error('specialite')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Laboratoire -->
        <div class="form-group mb-4">
            <label for="idLabo">Laboratoire</label>
            <select class="form-control @error('idLabo') is-invalid @enderror" id="idLabo" name="idLabo" required>
                @foreach ($laboratoires as $labo)
                    <option value="{{ $labo->idLabo }}"
                        {{ old('idLabo', $chercheur->idLabo) == $labo->idLabo ? 'selected' : '' }}>
                        {{ $labo->nomLabo }}
                    </option>
                @endforeach
            </select>
            @error('idLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Bouton Soumettre -->
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Enregistrer les modifications
        </button>
    </form>
</div>
@endsection



@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de select2 pour le laboratoire si nécessaire
        $('#idLabo').select2({
            placeholder: 'Sélectionnez un laboratoire',
            width: '100%'
        });
    });
</script>
@endsection

