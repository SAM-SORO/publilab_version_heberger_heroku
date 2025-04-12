@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listeChercheurs') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification du chercheur
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updateLaboChercheur', $chercheur->idCherch) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations personnelles -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Informations personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Nom -->
                    <div class="col-md-6 form-group">
                        <label for="nomCherch" class="font-weight-bold">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomCherch') is-invalid @enderror"
                               id="nomCherch" name="nomCherch" value="{{ old('nomCherch', $chercheur->nomCherch) }}" required>
                        @error('nomCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div class="col-md-6 form-group">
                        <label for="prenomCherch" class="font-weight-bold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('prenomCherch') is-invalid @enderror"
                               id="prenomCherch" name="prenomCherch" value="{{ old('prenomCherch', $chercheur->prenomCherch) }}" required>
                        @error('prenomCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Genre -->
                    <div class="col-md-6 form-group">
                        <label for="genreCherch" class="font-weight-bold">Genre</label>
                        <select class="form-control @error('genreCherch') is-invalid @enderror"
                                id="genreCherch" name="genreCherch">
                            <option value="">Sélectionner...</option>
                            <option value="M" {{ old('genreCherch', $chercheur->genreCherch) == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('genreCherch', $chercheur->genreCherch) == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('genreCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Matricule -->
                    <div class="col-md-6 form-group">
                        <label for="matriculeCherch" class="font-weight-bold">Matricule</label>
                        <input type="text" class="form-control @error('matriculeCherch') is-invalid @enderror"
                               id="matriculeCherch" name="matriculeCherch"
                               value="{{ old('matriculeCherch', $chercheur->matriculeCherch) }}">
                        @error('matriculeCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Email -->
                    <div class="col-md-6 form-group">
                        <label for="emailCherch" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('emailCherch') is-invalid @enderror"
                               id="emailCherch" name="emailCherch" value="{{ old('emailCherch', $chercheur->emailCherch) }}" required>
                        @error('emailCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div class="col-md-6 form-group">
                        <label for="telCherch" class="font-weight-bold">Téléphone</label>
                        <input type="tel" class="form-control @error('telCherch') is-invalid @enderror"
                               id="telCherch" name="telCherch" value="{{ old('telCherch', $chercheur->telCherch) }}">
                        @error('telCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Date de naissance -->
                    <div class="col-md-6 form-group">
                        <label for="dateNaissCherch" class="font-weight-bold">Date de naissance</label>
                        <input type="date" class="form-control @error('dateNaissCherch') is-invalid @enderror"
                               id="dateNaissCherch" name="dateNaissCherch"
                               value="{{ old('dateNaissCherch', $chercheur->dateNaissCherch) }}">
                        @error('dateNaissCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date d'arrivée -->
                    <div class="col-md-6 form-group">
                        <label for="dateArriveeCherch" class="font-weight-bold">Date d'arrivée</label>
                        <input type="date" class="form-control @error('dateArriveeCherch') is-invalid @enderror"
                               id="dateArriveeCherch" name="dateArriveeCherch"
                               value="{{ old('dateArriveeCherch', $chercheur->dateArriveeCherch) }}">
                        @error('dateArriveeCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sécurité - NOUVELLE SECTION -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-lock"></i> Sécurité</h5>
            </div>
            <div class="card-body">

                <div class="row">
                    <!-- Mot de passe -->
                    <div class="col-md-6 form-group">
                        <label for="password" class="font-weight-bold">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="col-md-6 form-group">
                        <label for="password_confirmation" class="font-weight-bold">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                <span class="text-danger">*</span><small>Ne saisissez le mot de passe que si vous souhaitez modifier le mot de passe actuel.</small>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-briefcase"></i> Informations professionnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Fonction administrative -->
                    <div class="col-md-6 form-group">
                        <label for="fonctionAdministrativeCherch" class="font-weight-bold">Fonction administrative</label>
                        <input type="text" class="form-control @error('fonctionAdministrativeCherch') is-invalid @enderror"
                               id="fonctionAdministrativeCherch" name="fonctionAdministrativeCherch"
                               value="{{ old('fonctionAdministrativeCherch', $chercheur->fonctionAdministrativeCherch) }}">
                        @error('fonctionAdministrativeCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Spécialité -->
                    <div class="col-md-6 form-group">
                        <label for="specialiteCherch" class="font-weight-bold">Spécialité</label>
                        <input type="text" class="form-control @error('specialiteCherch') is-invalid @enderror"
                               id="specialiteCherch" name="specialiteCherch"
                               value="{{ old('specialiteCherch', $chercheur->specialiteCherch) }}">
                        @error('specialiteCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Emploi -->
                    <div class="col-md-6 form-group">
                        <label for="emploiCherch" class="font-weight-bold">Emploi</label>
                        <input type="text" class="form-control @error('emploiCherch') is-invalid @enderror"
                               id="emploiCherch" name="emploiCherch"
                               value="{{ old('emploiCherch', $chercheur->emploiCherch) }}">
                        @error('emploiCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Département -->
                    <div class="col-md-6 form-group">
                        <label for="departementCherch" class="font-weight-bold">Département</label>
                        <input type="text" class="form-control @error('departementCherch') is-invalid @enderror"
                               id="departementCherch" name="departementCherch"
                               value="{{ old('departementCherch', $chercheur->departementCherch) }}">
                        @error('departementCherch')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Affiliations -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-building"></i> Affiliations</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- UMRI -->
                    <div class="col-md-6 form-group">
                        <label for="idUMRI" class="font-weight-bold">UMRI</label>
                        <select class="form-control select2 @error('idUMRI') is-invalid @enderror"
                                id="idUMRI" name="idUMRI">
                            <option value="">Sélectionner...</option>
                            @foreach($umris as $umri)
                                <option value="{{ $umri->idUMRI }}"
                                    {{ old('idUMRI', $chercheur->idUMRI) == $umri->idUMRI ? 'selected' : '' }}>
                                    {{ $umri->sigleUMRI }}
                                </option>
                            @endforeach
                        </select>
                        @error('idUMRI')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Laboratoire -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="idLabo" class="font-weight-bold">Laboratoire</label>
                            <select class="form-control select2 @error('idLabo') is-invalid @enderror"
                                    id="idLabo" name="idLabo" multiple>
                                {{-- <option value="">Sélectionner un laboratoire</option> --}}
                                @foreach($laboratoires as $labo)
                                    <option value="{{ $labo->idLabo }}"
                                        {{ old('idLabo', $chercheur->idLabo) == $labo->idLabo ? 'selected' : '' }}>
                                        {{ $labo->sigleLabo }} - {{ $labo->nomLabo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idLabo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Date d'affectation au laboratoire -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dateAffectationLabo" class="font-weight-bold">Date d'affectation au laboratoire</label>
                            <input type="date" class="form-control @error('dateAffectationLabo') is-invalid @enderror"
                                   id="dateAffectationLabo" name="dateAffectationLabo"
                                   value="{{ old('dateAffectationLabo', $chercheur->dateAffectationLabo?->format('Y-m-d')) }}">
                            @error('dateAffectationLabo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grades -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Grades</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="grades" class="font-weight-bold">Sélectionner les grades</label>
                    <select class="form-control select2 @error('grades') is-invalid @enderror"
                            id="grades" name="grades[]" multiple>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->idGrade }}"
                                {{ (old('grades') && in_array($grade->idGrade, old('grades'))) ||
                                   (!old('grades') && $chercheur->grades->contains('idGrade', $grade->idGrade)) ? 'selected' : '' }}>
                                   ({{ $grade->sigleGrade }})
                            </option>
                        @endforeach
                    </select>
                    @error('grades')
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
        $('#idLabo').select2({
            placeholder: 'Sélectionner un laboratoire',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
            language: {
                noResults: function() {
                    return "Aucun laboratoire trouvé";
                },
                searching: function() {
                    return "Recherche...";
                },
                maximumSelectionLength: function() {
                    return "Vous ne pouvez sélectionner qu'un seul laboratoire";
                }
            }
        });


        $('#grades').select2({
            width: '100%',
            placeholder: 'Sélectionner...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Aucune base trouvée";
                },
                searching: function() {
                    return "Recherche...";
                },

            },
        });

        $('.select2-selection').css('min-height', '40px');
    });
</script>
@endsection

