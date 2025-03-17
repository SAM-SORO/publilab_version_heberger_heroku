@extends("baseChercheur")

@section('content')
<div class="container mt-4">
    <!-- Notifications d'erreur ou de succès -->
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

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-user-circle"></i> Mon Profil</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('chercheur.updateProfil') }}" method="POST">
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
                                <input type="text" class="form-control @error('nomCherch') is-invalid @enderror"
                                       name="nomCherch" value="{{ old('nomCherch', $chercheur->nomCherch) }}" required>
                                @error('nomCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('prenomCherch') is-invalid @enderror"
                                       name="prenomCherch" value="{{ old('prenomCherch', $chercheur->prenomCherch) }}" required>
                                @error('prenomCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Genre -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Genre</label>
                                <select class="form-control @error('genreCherch') is-invalid @enderror" name="genreCherch">
                                    <option value="">Sélectionner...</option>
                                    <option value="M" {{ old('genreCherch', $chercheur->genreCherch) == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('genreCherch', $chercheur->genreCherch) == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('genreCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Matricule -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Matricule <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('matriculeCherch') is-invalid @enderror"
                                       name="matriculeCherch" value="{{ old('matriculeCherch', $chercheur->matriculeCherch) }}" required>
                                @error('matriculeCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-briefcase"></i> Informations professionnelles</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Emploi -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Emploi</label>
                                <input type="text" class="form-control @error('emploiCherch') is-invalid @enderror"
                                       name="emploiCherch" value="{{ old('emploiCherch', $chercheur->emploiCherch) }}">
                                @error('emploiCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Département -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Département</label>
                                <input type="text" class="form-control @error('departementCherch') is-invalid @enderror"
                                       name="departementCherch" value="{{ old('departementCherch', $chercheur->departementCherch) }}">
                                @error('departementCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fonction Administrative -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Fonction Administrative</label>
                                <input type="text" class="form-control @error('fonctionAdministrativeCherch') is-invalid @enderror"
                                       name="fonctionAdministrativeCherch" value="{{ old('fonctionAdministrativeCherch', $chercheur->fonctionAdministrativeCherch) }}">
                                @error('fonctionAdministrativeCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Spécialité -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Spécialité</label>
                                <input type="text" class="form-control @error('specialiteCherch') is-invalid @enderror"
                                       name="specialiteCherch" value="{{ old('specialiteCherch', $chercheur->specialiteCherch) }}">
                                @error('specialiteCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-address-card"></i> Contact</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('emailCherch') is-invalid @enderror"
                                       name="emailCherch" value="{{ old('emailCherch', $chercheur->emailCherch) }}" required>
                                @error('emailCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Téléphone</label>
                                <input type="text" class="form-control @error('telCherch') is-invalid @enderror"
                                       name="telCherch" value="{{ old('telCherch', $chercheur->telCherch) }}">
                                @error('telCherch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sécurité -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-lock"></i> Sécurité</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Mot de passe actuel -->
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Mot de passe actuel</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                       name="new_password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmation du nouveau mot de passe -->
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" name="new_password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
