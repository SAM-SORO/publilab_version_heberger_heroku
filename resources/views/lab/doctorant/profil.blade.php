@extends("baseDoctorant")

@section('content')
<div class="container mt-4">
    <!-- Notifications -->
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm mt-5">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-user-circle"></i> Mon Profil</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('doctorant.updateProfil') }}" method="POST">
                @csrf
                @method('POST')

                <!-- Informations personnelles -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations personnelles</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Nom -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nomDoc') is-invalid @enderror"
                                       name="nomDoc" value="{{ old('nomDoc', $doctorant->nomDoc) }}" required>
                                @error('nomDoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('prenomDoc') is-invalid @enderror"
                                       name="prenomDoc" value="{{ old('prenomDoc', $doctorant->prenomDoc) }}" required>
                                @error('prenomDoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Genre -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Genre</label>
                                <select class="form-control @error('genreDoc') is-invalid @enderror" name="genreDoc">
                                    <option value="">Sélectionner...</option>
                                    <option value="M" {{ old('genreDoc', $doctorant->genreDoc) == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('genreDoc', $doctorant->genreDoc) == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('genreDoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Matricule -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Matricule <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('matriculeDoc') is-invalid @enderror"
                                       name="matriculeDoc" value="{{ old('matriculeDoc', $doctorant->matriculeDoc) }}" required>
                                @error('matriculeDoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('emailDoc') is-invalid @enderror"
                                       name="emailDoc" value="{{ old('emailDoc', $doctorant->emailDoc) }}" required>
                                @error('emailDoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Téléphone</label>
                                <input type="text" class="form-control @error('telDoc') is-invalid @enderror"
                                       name="telDoc" value="{{ old('telDoc', $doctorant->telDoc) }}">
                                @error('telDoc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modification du mot de passe -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-lock"></i> Modifier le mot de passe</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Mot de passe actuel -->
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Mot de passe actuel</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       name="current_password">
                                <small class="form-text text-muted">Requis uniquement si vous souhaitez changer de mot de passe</small>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                       name="new_password">
                                <small class="form-text text-muted">Minimum 8 caractères</small>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmation du nouveau mot de passe -->
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Confirmer le mot de passe</label>
                                <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                       name="new_password_confirmation">
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
