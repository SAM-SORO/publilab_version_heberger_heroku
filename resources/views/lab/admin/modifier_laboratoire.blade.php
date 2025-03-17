@extends('baseAdmin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('admin.listeLaboratoires') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <h3 class="mb-0">Modifier le laboratoire</h3>
                <div style="width: 40px"></div>
            </div>

            <form action="{{ route('admin.updateLaboratoire', $laboratoire->idLabo) }}" method="POST">
                @csrf
                @method('POST')

                <!-- Informations principales -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="fas fa-building"></i> Informations principales</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sigleLabo">Sigle <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sigleLabo') is-invalid @enderror"
                                           id="sigleLabo" name="sigleLabo"
                                           value="{{ old('sigleLabo', $laboratoire->sigleLabo) }}" required>
                                    @error('sigleLabo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomLabo">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomLabo') is-invalid @enderror"
                                           id="nomLabo" name="nomLabo"
                                           value="{{ old('nomLabo', $laboratoire->nomLabo) }}" required>
                                    @error('nomLabo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Année et Description -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="anneeCreation">Année de création</label>
                                    <input type="number" min="1900" max="{{ date('Y') }}"
                                           class="form-control @error('anneeCreation') is-invalid @enderror"
                                           id="anneeCreation" name="anneeCreation"
                                           value="{{ old('anneeCreation', $laboratoire->anneeCreation) }}">
                                    @error('anneeCreation')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="descLabo">Description</label>
                                    <textarea class="form-control @error('descLabo') is-invalid @enderror"
                                              id="descLabo" name="descLabo" rows="3"
                                              style="resize: none;">{{ old('descLabo', $laboratoire->descLabo) }}</textarea>
                                    @error('descLabo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Localisation</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="localisationLabo">Localisation</label>
                                    <input type="text" class="form-control" id="localisationLabo" name="localisationLabo"
                                           value="{{ old('localisationLabo', $laboratoire->localisationLabo) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adresseLabo">Adresse complète</label>
                                    <input type="text" class="form-control" id="adresseLabo" name="adresseLabo"
                                           value="{{ old('adresseLabo', $laboratoire->adresseLabo) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="fas fa-address-card"></i> Contact</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telLabo">Téléphone</label>
                                    <input type="tel" class="form-control" id="telLabo" name="telLabo"
                                           value="{{ old('telLabo', $laboratoire->telLabo) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="faxLabo">Fax</label>
                                    <input type="tel" class="form-control" id="faxLabo" name="faxLabo"
                                           value="{{ old('faxLabo', $laboratoire->faxLabo) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="emailLabo">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="emailLabo" name="emailLabo"
                                   value="{{ old('emailLabo', $laboratoire->emailLabo) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Rattachement -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="fas fa-sitemap"></i> Rattachement</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="idUMRI">UMRI</label>
                                    <select class="form-control select2" id="idUMRI" name="idUMRI">
                                        <option value="">Sélectionnez un UMRI</option>
                                        @foreach ($umris as $umri)
                                            <option value="{{ $umri->idUMRI }}"
                                                {{ old('idUMRI', $laboratoire->idUMRI) == $umri->idUMRI ? 'selected' : '' }}>
                                                {{ $umri->sigleUMRI }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="axesRecherche">Axes de recherche</label>
                            <select class="form-control select2" id="axesRecherche" name="axesRecherche[]" multiple>
                                @foreach ($axesRecherches as $axe)
                                    <option value="{{ $axe->idAxeRech }}"
                                        {{ in_array($axe->idAxeRech, old('axesRecherche', $laboratoire->axesRecherches->pluck('idAxeRech')->toArray())) ? 'selected' : '' }}>
                                        {{ $axe->titreAxeRech }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="idDirecteurLabo">Directeur du laboratoire</label>
                            <select class="form-control select2" id="idDirecteurLabo" name="idDirecteurLabo">
                                <option value="">Sélectionnez un directeur</option>
                                @foreach ($chercheurs as $chercheur)
                                    <option value="{{ $chercheur->idCherch }}"
                                        {{ old('idDirecteurLabo', $laboratoire->idDirecteurLabo) == $chercheur->idCherch ? 'selected' : '' }}>
                                        {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 mb-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="fas fa-save mr-2"></i> Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            allowClear: true
        });

        $('#axesRecherche').select2({
            placeholder: 'Sélectionnez les axes de recherche',
            allowClear: true,
            maximumSelectionLength: 5
        });
    });
</script>
@endsection
