@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeEdp') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification de l'EDP - {{ $edp->sigleEDP }}
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateEdp', $edp->idEDP) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations principales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations principales</h5>
            </div>
            <div class="card-body">
                <!-- Sigle de l'EDP -->
                <div class="form-group mb-4">
                    <label for="sigleEDP" class="font-weight-bold">
                        Sigle de l'EDP <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control form-control-lg @error('sigleEDP') is-invalid @enderror"
                           id="sigleEDP" name="sigleEDP"
                           value="{{ old('sigleEDP', $edp->sigleEDP) }}" required>
                    @error('sigleEDP')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nom de l'EDP -->
                <div class="form-group mb-4">
                    <label for="nomEDP" class="font-weight-bold">
                        Nom de l'EDP <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('nomEDP') is-invalid @enderror"
                           id="nomEDP" name="nomEDP"
                           value="{{ old('nomEDP', $edp->nomEDP) }}" required>
                    @error('nomEDP')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Localisation -->
                <div class="form-group">
                    <label for="localisationEDP" class="font-weight-bold">
                        <i class="fas fa-map-marker-alt"></i> Localisation
                    </label>
                    <input type="text" class="form-control @error('localisationEDP') is-invalid @enderror"
                           id="localisationEDP" name="localisationEDP"
                           value="{{ old('localisationEDP', $edp->localisationEDP) }}">
                    @error('localisationEDP')
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
                    <label for="idDirecteurEDP" class="font-weight-bold">Directeur</label>
                    <select class="form-control @error('idDirecteurEDP') is-invalid @enderror"
                            id="idDirecteurEDP" name="idDirecteurEDP" multiple>
                        {{-- <option value="">Sélectionner un directeur</option> --}}
                        @foreach($chercheurs as $chercheur)
                            <option value="{{ $chercheur->idCherch }}"
                                {{ old('idDirecteurEDP', $edp->idDirecteurEDP) == $chercheur->idCherch ? 'selected' : '' }}>
                                {{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}
                            </option>
                        @endforeach
                    </select>
                    @error('idDirecteurEDP')
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
                    <label for="secretaireEDP" class="font-weight-bold">Nom du secrétaire</label>
                    <input type="text" class="form-control @error('secretaireEDP') is-invalid @enderror"
                           id="secretaireEDP" name="secretaireEDP"
                           value="{{ old('secretaireEDP', $edp->secretaireEDP) }}">
                    @error('secretaireEDP')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact -->
                <div class="form-group mb-4">
                    <label for="contactSecretariatEDP" class="font-weight-bold">
                        <i class="fas fa-phone"></i> Contact
                    </label>
                    <input type="text" class="form-control @error('contactSecretariatEDP') is-invalid @enderror"
                           id="contactSecretariatEDP" name="contactSecretariatEDP"
                           value="{{ old('contactSecretariatEDP', $edp->contactSecretariatEDP) }}">
                    @error('contactSecretariatEDP')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="emailSecretariatEDP" class="font-weight-bold">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" class="form-control @error('emailSecretariatEDP') is-invalid @enderror"
                           id="emailSecretariatEDP" name="emailSecretariatEDP"
                           value="{{ old('emailSecretariatEDP', $edp->emailSecretariatEDP) }}">
                    @error('emailSecretariatEDP')
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
        $('#idDirecteurEDP').select2({
            placeholder: 'Sélectionner un directeur',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
            language: {
                noResults: function() {
                    return "Aucune base trouvée";
                },
                searching: function() {
                    return "Recherche...";
                },
                maximumSelectionLength: function() {
                    return "Vous ne pouvez sélectionner qu'un seul élément";  // Message personnalisé en français
                }
            },

        });

        $('.select2-selection').css('min-height', '40px'); // Applique la hauteur après initialisation
    });
</script>
@endsection
