@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeRevue') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier une revue</h2>
    </div>

    <div class="mb-5">
        {{-- Erreur session --}}
        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" id="alert-danger-login">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Succès session --}}
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-login">
                {{ Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Erreurs de validation --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" id="alert-validation-errors">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    <form action="{{ route('admin.updateRevue', $revue->idRevue) }}" method="POST">
        @csrf
        @method('POST')

        <!-- ISSN -->
        <div class="form-group mb-4">
            <label for="ISSN">ISSN</label>
            <input type="text" class="form-control @error('ISSN') is-invalid @enderror"
                   id="ISSN" name="ISSN" value="{{ old('ISSN', $revue->ISSN) }}" required>
            @error('ISSN')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nom de la revue -->
        <div class="form-group mb-4">
            <label for="nomRevue">Nom de la Revue</label>
            <input type="text" class="form-control @error('nomRevue') is-invalid @enderror"
                   id="nomRevue" name="nomRevue" value="{{ old('nomRevue', $revue->nomRevue) }}" required>
            @error('nomRevue')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-4">
            <label for="descRevue">Description</label>
            <textarea class="form-control @error('descRevue') is-invalid @enderror"
                      id="descRevue" name="descRevue" rows="3">{{ old('descRevue', $revue->descRevue) }}</textarea>
            @error('descRevue')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Type de revue -->
        <div class="form-group mb-4">
            <label for="typeRevue">Type de la Revue</label>
            <input type="text" class="form-control @error('typeRevue') is-invalid @enderror"
                   id="typeRevue" name="typeRevue" value="{{ old('typeRevue', $revue->typeRevue) }}">
            @error('typeRevue')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Base d'Indexation -->
        <div class="form-group mb-4">
            <label for="bdIndexation">Base d'Indexation</label>
            <select id="bdIndexation" name="bdIndexation[]" class="form-control @error('bdIndexation') is-invalid @enderror" multiple>
                @foreach ($bdIndexations as $bdIndexation)
                    <option value="{{ $bdIndexation->idBDIndex }}"
                        {{ in_array($bdIndexation->idBDIndex, old('bdIndexation', $revue->bdIndexations->pluck('idBDIndex')->toArray())) ? 'selected' : '' }}>
                        {{ $bdIndexation->nomBDInd }}
                    </option>
                @endforeach
            </select>
            @error('bdIndexation')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Container pour les dates -->
        <div id="dates-container" class="mt-4">
            @foreach ($revue->bdIndexations as $bdIndexation)
                <div class="form-group mb-3 date-group" data-bdindex-id="{{ $bdIndexation->idBDIndex }}">

                    <label for="dateDebut_{{ $bdIndexation->idBDIndex }}">Date de début pour {{ $bdIndexation->nomBDInd }}</label>
                    <input type="date" class="form-control" id="dateDebut_{{ $bdIndexation->idBDIndex }}" name="dateDebut[{{ $bdIndexation->idBDIndex }}]"
                           value="{{ old('dateDebut.' . $bdIndexation->idBDIndex, $bdIndexation->pivot->dateDebut) }}">

                    <label for="dateFin_{{ $bdIndexation->idBDIndex }}">Date de fin pour {{ $bdIndexation->nomBDInd }}</label>
                    <input type="date" class="form-control" id="dateFin_{{ $bdIndexation->idBDIndex }}" name="dateFin[{{ $bdIndexation->idBDIndex }}]"
                           value="{{ old('dateFin.' . $bdIndexation->idBDIndex, $bdIndexation->pivot->dateFin) }}">
                </div>

            @endforeach
        </div>

        <!-- Bouton de soumission -->
        <div class="form-group mb-4 text-center">
            <button type="submit" class="btn btn-primary mt-5">
                <i class="fas fa-save"></i> Sauvegarder les modifications
            </button>
        </div>
    </form>

    @section('scripts')
        <script>
            $(document).ready(function() {
                // Initialisation de Select2
                $('#bdIndexation').select2({
                    placeholder: "Sélectionnez une ou plusieurs bases d'indexation",
                    width: '100%'
                });

                // Dynamiser l'ajout des champs de date pour chaque base d'indexation sélectionnée
                $('#bdIndexation').on('change', function () {
                    const selectedIndexations = $(this).val(); // Récupérer les bases d'indexation sélectionnées
                    const datesContainer = $('#dates-container');

                    // Parcourir les bases sélectionnées et ajouter les champs si nécessaires
                    selectedIndexations.forEach(function (bdIndexId) {
                        const bdIndexationName = $('#bdIndexation option[value="' + bdIndexId + '"]').text();
                        const existingDateGroup = datesContainer.find(`.date-group[data-bdindex-id="${bdIndexId}"]`);

                        // Si ce groupe de date n'existe pas déjà, en créer un nouveau
                        if (existingDateGroup.length === 0) {
                            const dateDebutValue = existingDateGroup.find(`input[name="dateDebut[${bdIndexId}]"]`).val() || '';
                            const dateFinValue = existingDateGroup.find(`input[name="dateFin[${bdIndexId}]"]`).val() || '';

                            const dateFields = `
                                <div class="form-group mb-3 date-group" data-bdindex-id="${bdIndexId}">
                                    <label>Date de début pour ${bdIndexationName}</label>
                                    <input type="date" class="form-control" name="dateDebut[${bdIndexId}]" value="${dateDebutValue}">

                                    <label>Date de fin pour ${bdIndexationName}</label>
                                    <input type="date" class="form-control" name="dateFin[${bdIndexId}]" value="${dateFinValue}">
                                </div>
                            `;
                            datesContainer.append(dateFields);
                        }
                    });

                    // Supprimer les groupes de date pour les bases non sélectionnées
                    datesContainer.find('.date-group').each(function() {
                        const groupId = $(this).data('bdindex-id');
                        if (!selectedIndexations.includes(String(groupId))) {
                            $(this).remove();
                        }
                    });
                });

                // Si des bases sont déjà sélectionnées au chargement, afficher les dates
                const selectedIndexations = $('#bdIndexation').val();
                if (selectedIndexations.length > 0) {
                    $('#bdIndexation').trigger('change');
                }
            });

        </script>
    @endsection

@endsection
