@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeUmris') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification de l'UMRI - {{ $umri->sigleUMRI }}
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateUmris', $umri->idUMRI) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Informations principales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations principales</h5>
            </div>
            <div class="card-body">
                <!-- Sigle de l'UMRI -->
                <div class="form-group mb-4">
                    <label for="sigleUMRI" class="font-weight-bold">
                        Sigle de l'UMRI <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control form-control-lg @error('sigleUMRI') is-invalid @enderror"
                           id="sigleUMRI" name="sigleUMRI"
                           value="{{ old('sigleUMRI', $umri->sigleUMRI) }}" required>
                    @error('sigleUMRI')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nom de l'UMRI -->
                <div class="form-group mb-4">
                    <label for="nomUMRI" class="font-weight-bold">
                        Nom de l'UMRI <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('nomUMRI') is-invalid @enderror"
                           id="nomUMRI" name="nomUMRI"
                           value="{{ old('nomUMRI', $umri->nomUMRI) }}" required>
                    @error('nomUMRI')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Localisation -->
                <div class="form-group">
                    <label for="localisationUMRI" class="font-weight-bold">
                        <i class="fas fa-map-marker-alt"></i> Localisation
                    </label>
                    <input type="text" class="form-control @error('localisationUMRI') is-invalid @enderror"
                           id="localisationUMRI" name="localisationUMRI"
                           value="{{ old('localisationUMRI', $umri->localisationUMRI) }}">
                    @error('localisationUMRI')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Direction -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-user-tie"></i> Direction</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="idDirecteurUMRI" class="font-weight-bold">Directeur</label>
                    <select class="form-control @error('idDirecteurUMRI') is-invalid @enderror"
                            id="idDirecteurUMRI" name="idDirecteurUMRI">
                        <option value="">Sélectionner un directeur</option>
                        @foreach($chercheurs as $chercheur)
                            <option value="{{ $chercheur->idCherch }}"
                                {{ old('idDirecteurUMRI', $umri->idDirecteurUMRI) == $chercheur->idCherch ? 'selected' : '' }}>
                                {{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}
                            </option>
                        @endforeach
                    </select>
                    @error('idDirecteurUMRI')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Secrétariat -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-user-friends"></i> Secrétariat</h5>
            </div>
            <div class="card-body">
                <!-- Secrétaire -->
                <div class="form-group mb-4">
                    <label for="secretaireUMRI" class="font-weight-bold">Nom du secrétaire</label>
                    <input type="text" class="form-control @error('secretaireUMRI') is-invalid @enderror"
                           id="secretaireUMRI" name="secretaireUMRI"
                           value="{{ old('secretaireUMRI', $umri->secretaireUMRI) }}">
                    @error('secretaireUMRI')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact -->
                <div class="form-group mb-4">
                    <label for="contactSecretariatUMRI" class="font-weight-bold">
                        <i class="fas fa-phone"></i> Contact
                    </label>
                    <input type="number" class="form-control @error('contactSecretariatUMRI') is-invalid @enderror"
                           id="contactSecretariatUMRI" name="contactSecretariatUMRI"
                           value="{{ old('contactSecretariatUMRI', $umri->contactSecretariatUMRI) }}">
                    @error('contactSecretariatUMRI')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="emailSecretariatUMRI" class="font-weight-bold">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" class="form-control @error('emailSecretariatUMRI') is-invalid @enderror"
                           id="emailSecretariatUMRI" name="emailSecretariatUMRI"
                           value="{{ old('emailSecretariatUMRI', $umri->emailSecretariatUMRI) }}">
                    @error('emailSecretariatUMRI')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- EDP -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-building"></i> Rattachement</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="idEDP" class="font-weight-bold">
                        EDP de rattachement <span class="text-danger">*</span>
                    </label>
                    <select class="form-control @error('idEDP') is-invalid @enderror"
                            id="idEDP" name="idEDP" multiple  required>
                        <option value="">Sélectionner un EDP</option>
                        @foreach($edps as $edp)
                            <option value="{{ $edp->idEDP }}"
                                {{ old('idEDP', $umri->idEDP) == $edp->idEDP ? 'selected' : '' }}>
                                {{ $edp->sigleEDP }} - {{ $edp->nomEDP }}
                            </option>
                        @endforeach
                    </select>
                    @error('idEDP')
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#idDirecteurUMRI').select2({
            placeholder: 'Sélectionner un directeur',
            allowClear: true,
            width: '100%',
            //theme: 'bootstrap4'
        });

        $('#idEDP').select2({
            placeholder: 'Sélectionner un EDP',
            allowClear: true,
            width: '100%',
            //theme: 'bootstrap4'
        });
    });
</script>
@endsection
