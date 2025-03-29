@extends("baseAdmin")

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

    <div class="card shadow-sm mt-5">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-user-circle"></i> Mon Profil</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.updateProfil') }}" method="POST">
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
                                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                       name="nom" value="{{ old('nom', $admin->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email', $admin->email) }}" required>
                                @error('email')
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
