@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeDoctorants') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification du doctorant
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateDoctorant', $doctorant->idDoc) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations personnelles -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user"></i> Informations personnelles</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomDoc" class="font-weight-bold">
                                Nom <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nomDoc') is-invalid @enderror"
                                   id="nomDoc" name="nomDoc" value="{{ old('nomDoc', $doctorant->nomDoc) }}" required>
                            @error('nomDoc')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prenomDoc" class="font-weight-bold">
                                Prénom <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('prenomDoc') is-invalid @enderror"
                                   id="prenomDoc" name="prenomDoc" value="{{ old('prenomDoc', $doctorant->prenomDoc) }}" required>
                            @error('prenomDoc')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="matriculeDoc" class="font-weight-bold">
                                Matricule <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('matriculeDoc') is-invalid @enderror"
                                   id="matriculeDoc" name="matriculeDoc" value="{{ old('matriculeDoc', $doctorant->matriculeDoc) }}" required>
                            @error('matriculeDoc')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="genreDoc" class="font-weight-bold">Genre</label>
                            <select class="form-control @error('genreDoc') is-invalid @enderror"
                                    id="genreDoc" name="genreDoc">
                                <option value="">Sélectionner...</option>
                                <option value="M" {{ old('genreDoc', $doctorant->genreDoc) == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('genreDoc', $doctorant->genreDoc) == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('genreDoc')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="emailDoc" class="font-weight-bold">Email</label>
                            <input type="email" class="form-control @error('emailDoc') is-invalid @enderror"
                                   id="emailDoc" name="emailDoc" value="{{ old('emailDoc', $doctorant->emailDoc) }}">
                            @error('emailDoc')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telDoc" class="font-weight-bold">Téléphone</label>
                            <input type="text" class="form-control @error('telDoc') is-invalid @enderror"
                                   id="telDoc" name="telDoc" value="{{ old('telDoc', $doctorant->telDoc) }}">
                            @error('telDoc')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thème et encadrants -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-bookmark"></i> Thème et encadrants</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-4">
                    <label for="idTheme" class="font-weight-bold">Thème de recherche</label>
                    <select class="form-control select2 @error('idTheme') is-invalid @enderror"
                            id="idTheme" name="idTheme">
                        <option value="">Sélectionner un thème</option>
                        @foreach($themes as $theme)
                            <option value="{{ $theme->idTheme }}"
                                {{ old('idTheme', $doctorant->idTheme) == $theme->idTheme ? 'selected' : '' }}>
                                {{ $theme->intituleTheme }}
                            </option>
                        @endforeach
                    </select>
                    @error('idTheme')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="encadrants" class="font-weight-bold">Encadrants</label>
                    <select class="form-control select2 @error('encadrants') is-invalid @enderror"
                            id="encadrants" name="encadrants[]" multiple>
                        @foreach($chercheurs as $chercheur)
                            <option value="{{ $chercheur->idCherch }}"
                                {{ in_array($chercheur->idCherch, $encadrantsIds) ? 'selected' : '' }}>
                                {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                @if(isset($chercheur->grade))
                                    ({{ $chercheur->grade->sigleGrade }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('encadrants')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Après les informations de contact -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-key"></i> Modification du mot de passe</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="password" class="font-weight-bold">Nouveau mot de passe</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password">
                    <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="font-weight-bold">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation">
                    @error('password_confirmation')
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
        // Initialisation de select2 pour les encadrants
        $('#encadrants').select2({
            placeholder: 'Sélectionnez un ou plusieurs encadrants',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Aucune base trouvée";
                },
                searching: function() {
                    return "Recherche...";
                },

            },
        });

        // Initialisation de select2 pour le thème
        $('#idTheme').select2({
            placeholder: 'Sélectionnez un thème',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Aucune base trouvée";
                },
                searching: function() {
                    return "Recherche...";
                },

            },
        });

        $('.select2-selection').css('min-height', '40px'); // Applique la hauteur après initialisation
    });
</script>
@endsection
