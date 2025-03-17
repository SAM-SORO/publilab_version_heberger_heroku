@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-lg rounded-lg mt-5 py-5 px-5" style="max-width: 90%">
    <!-- En-tête avec effet de profondeur -->
    <div class="border-bottom pb-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.listePublications') }}" class="btn btn-outline-primary rounded-circle shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 class="text-center text-primary font-weight-bold mb-0">
                Modification de la publication
            </h2>
            <div style="width: 40px"></div>
        </div>
    </div>

    <div class="mb-5">
        @include("lab.partials.alerts")
    </div>

    <form action="{{ route('admin.updatePublication', $publication->idPub) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Informations principales -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0"><i class="fas fa-book"></i> Informations principales</h6>
            </div>
            <div class="card-body">
                <!-- Titre -->
                <div class="form-group mb-4">
                    <label for="titrePub" class="font-weight-bold">
                        Titre <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('titrePub') is-invalid @enderror"
                           id="titrePub" name="titrePub"
                           value="{{ old('titrePub', $publication->titrePub) }}" required>
                    @error('titrePub')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="descPub" class="font-weight-bold">Description</label>
                    <textarea class="form-control @error('descPub') is-invalid @enderror"
                              id="descPub" name="descPub" rows="4">{{ old('descPub', $publication->descPub) }}</textarea>
                    @error('descPub')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Type et Indexation -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0"><i class="fas fa-tags"></i> Classification</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="idTypePub" class="font-weight-bold">
                        Type de publication <span class="text-danger">*</span>
                    </label>
                    <select class="form-control select2 @error('idTypePub') is-invalid @enderror"
                            id="idTypePub" name="idTypePub" required>
                        <option value="">Sélectionnez un type</option>
                        @foreach($typesPublications as $type)
                            <option value="{{ $type->idTypePub }}"
                                {{ old('idTypePub', $publication->idTypePub) == $type->idTypePub ? 'selected' : '' }}>
                                {{ $type->libeleTypePub }}
                            </option>
                        @endforeach
                    </select>
                    @error('idTypePub')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark  text-white">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations complémentaires</h6>
            </div>
            <div class="card-body">
                <!-- ISSN -->
                <div class="form-group">
                    <label for="ISSN" class="font-weight-bold">ISSN</label>
                    <input type="text" class="form-control @error('ISSN') is-invalid @enderror"
                           id="ISSN" name="ISSN" value="{{ old('ISSN', $publication->ISSN) }}">
                    @error('ISSN')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Éditeur -->
                <div class="form-group">
                    <label for="editeurPub" class="font-weight-bold">Éditeur</label>
                    <input type="text" class="form-control @error('editeurPub') is-invalid @enderror"
                           id="editeurPub" name="editeurPub"
                           value="{{ old('editeurPub', $publication->editeurPub) }}">
                    @error('editeurPub')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Après la section des informations complémentaires -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark g- text-white">
                <h6 class="mb-0"><i class="fas fa-database"></i> Bases d'indexation</h6>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Ajouter une base d'indexation</label>
                    <select class="form-control select2-bd" id="bdIndexation">
                        <option value="">Rechercher et sélectionner une base...</option>
                        @foreach($bdIndexations as $bd)
                            <option value="{{ $bd->idBDIndex }}">{{ $bd->nomBDInd }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Liste des bases sélectionnées -->
                <div id="selectedBdList">
                    @foreach($publication->bdIndexations as $bd)
                        <div id="bd_item_{{ $bd->idBDIndex }}" class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $bd->nomBDInd }}</strong>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeBd({{ $bd->idBDIndex }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="hidden" name="bdIndexations[]" value="{{ $bd->idBDIndex }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="small">Date début</label>
                                        <input type="date" class="form-control form-control-sm"
                                               name="dateDebut[{{ $bd->idBDIndex }}]"
                                               value="{{ $bd->pivot->dateDebut }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="small">Date fin</label>
                                        <input type="date" class="form-control form-control-sm"
                                               name="dateFin[{{ $bd->idBDIndex }}]"
                                               value="{{ $bd->pivot->dateFin }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
        $('.select2').select2({
            width: '100%',
            placeholder: 'Sélectionnez une option'
        });

        $('.select2-selection').css('min-height', '40px'); // Applique la hauteur après initialisation


        // Initialisation de Select2 avec recherche
        $('.select2-bd').select2({
            placeholder: 'Rechercher une base d\'indexation...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Aucune base trouvée";
                },
                searching: function() {
                    return "Recherche...";
                }
            }
        });

        // Gestion de la sélection d'une base
        $('#bdIndexation').on('change', function() {
            const bdId = $(this).val();
            if (!bdId) return;

            const bdNom = $(this).find('option:selected').text();

            // Vérifier si la base n'est pas déjà ajoutée
            if ($(`#bd_item_${bdId}`).length === 0) {
                const newItem = `
                    <div id="bd_item_${bdId}" class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>${bdNom}</strong>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeBd(${bdId})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" name="bdIndexations[]" value="${bdId}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="small">Date début</label>
                                    <input type="date" class="form-control form-control-sm"
                                           name="dateDebut[${bdId}]" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="small">Date fin</label>
                                    <input type="date" class="form-control form-control-sm"
                                           name="dateFin[${bdId}]">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#selectedBdList').append(newItem);
            }

            // Réinitialiser la sélection
            $(this).val('').trigger('change');
        });
    });

    function removeBd(bdId) {
        $(`#bd_item_${bdId}`).remove();
    }
</script>
@endsection
