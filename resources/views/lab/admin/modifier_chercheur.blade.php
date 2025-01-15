@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeChercheurs') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un chercheur</h2>
    </div>

    <div class="mb-5">
        {{-- Gestion des messages d'erreurs et de succès --}}
        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <form action="{{ route('admin.updateLaboChercheur', $chercheur->idCherch) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Nom -->
        <div class="form-group mb-4">
            <label for="nomCherch">Nom</label>
            <div class="input-group">
                <input type="text" class="form-control @error('nomCherch') is-invalid @enderror"
                       id="nomCherch" name="nomCherch"
                       value="{{ old('nomCherch', $chercheur->nomCherch) }}" required>
            </div>
            @error('nomCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Prénom -->
        <div class="form-group mb-4">
            <label for="prenomCherch">Prénom</label>
            <div class="input-group">
                <input type="text" class="form-control @error('prenomCherch') is-invalid @enderror"
                       id="prenomCherch" name="prenomCherch"
                       value="{{ old('prenomCherch', $chercheur->prenomCherch) }}" required>
            </div>
            @error('prenomCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Adresse -->
        <div class="form-group mb-4">
            <label for="adresse">Adresse</label>
            <div class="input-group">
                <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                       id="adresse" name="adresse"
                       value="{{ old('adresse', $chercheur->adresse) }}">
            </div>
            @error('adresse')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Téléphone -->
        <div class="form-group mb-4">
            <label for="telCherch">Téléphone</label>
            <div class="input-group">
                <input type="text" class="form-control @error('telCherch') is-invalid @enderror"
                       id="telCherch" name="telCherch"
                       value="{{ old('telCherch', $chercheur->telCherch) }}">
            </div>
            @error('telCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group mb-4">
            <label for="emailCherch">Email</label>
            <div class="input-group">
                <input type="email" class="form-control @error('emailCherch') is-invalid @enderror"
                       id="emailCherch" name="emailCherch"
                       value="{{ old('emailCherch', $chercheur->emailCherch) }}" required>
            </div>
            @error('emailCherch')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nouveau Mot de Passe -->
        <div class="form-group mb-4">
            <label for="password">Nouveau Mot de Passe (laisser vide pour ne pas modifier)</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password">
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirmation du mot de passe -->
        <div class="form-group mb-4">
            <label for="password_confirmation">Confirmer le Nouveau Mot de Passe</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                       id="password_confirmation" name="password_confirmation">
            </div>
            @error('password_confirmation')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Spécialité -->
        <div class="form-group mb-4">
            <label for="specialite">Spécialité</label>
            <div class="input-group">
                <input type="text" class="form-control @error('specialite') is-invalid @enderror"
                       id="specialite" name="specialite"
                       value="{{ old('specialite', $chercheur->specialite) }}">
            </div>
            @error('specialite')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Laboratoire -->
        <div class="form-group mb-4">
            <label for="idLabo">Laboratoire</label>
            <select class="form-control @error('idLabo') is-invalid @enderror" id="idLabo" name="idLabo" multiple>
                @foreach ($laboratoires as $labo)
                    <option value="{{ $labo->idLabo }}"
                        {{ old('idLabo', $chercheur->idLabo) == $labo->idLabo ? 'selected' : '' }}>
                        {{ $labo->nomLabo }}
                    </option>
                @endforeach
            </select>
            @error('idLabo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Grades et Dates d'Obtention -->
        <div class="form-group mb-4">
            <label for="grades">Grades et Dates d'Obtention</label>
            <select class="form-control select2" id="grades" name="grades[]" multiple>
                @foreach ($grades as $grade)
                    <option value="{{ $grade->idGrade }}"
                        {{ old('grades', $chercheur->grades->pluck('idGrade')->toArray()) && in_array($grade->idGrade, old('grades', $chercheur->grades->pluck('idGrade')->toArray())) ? 'selected' : '' }}>
                        {{ $grade->nomGrade }} ({{ $grade->sigleGrade }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Sélectionnez un ou plusieurs grades</small>
        </div>

        <div id="dates-container" class="mt-4">
            <!-- Les champs de date s'ajoutent ici dynamiquement -->
            @foreach ($chercheur->grades as $grade)
                <div class="form-group mb-3 date-group" data-grade-id="{{ $grade->idGrade }}">
                    <label>Date d'obtention du grade {{ $grade->nomGrade }}</label>
                    <input type="date" class="form-control" name="dates[{{ $grade->idGrade }}]"
                           value="{{ old('dates['.$grade->idGrade.']', $grade->pivot->dateGrade) }}">
                </div>
            @endforeach
        </div>

        <!-- Bouton Soumettre -->
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Enregistrer les modifications
        </button>
    </form>


</div>


@section('scripts')
    <script>

        $(document).ready(function () {
            // Initialisation du plugin Select2
            $('.select2').select2({
                placeholder: "Sélectionnez les grades",
                allowClear: true,
                width: '100%' // Ajuste la largeur pour un affichage responsive
            });

            // Mise à jour dynamique des dates d'obtention
            $('.select2').on('change', function () {
                const selectedGrades = $(this).val(); // Récupérer les grades sélectionnés
                const datesContainer = $('#dates-container');

                // Stocker les valeurs existantes
                const existingDates = {};
                datesContainer.find('.date-group').each(function () {
                    const gradeId = $(this).data('grade-id');
                    const dateValue = $(this).find('input[type="date"]').val();
                    if (dateValue) {
                        existingDates[gradeId] = dateValue; // Sauvegarder la date pour ce grade
                    }
                });

                datesContainer.empty(); // Supprimer les champs existants

                // Générer les champs pour les grades sélectionnés
                if (selectedGrades) {
                    selectedGrades.forEach(function (gradeId) {
                        const gradeName = $(`option[value="${gradeId}"]`).text();
                        const existingDate = existingDates[gradeId] || ''; // Préserver la valeur existante si elle existe
                        datesContainer.append(`
                            <div class="form-group mb-3 date-group" data-grade-id="${gradeId}">
                                <label>Date d'obtention pour ${gradeName}</label>
                                <input type="date" class="form-control" name="dates[${gradeId}]" value="${existingDate}">
                            </div>
                        `);
                    });
                }
            });


            $('#idLabo').select2({
                placeholder: "Sélectionnez le labo",
                allowClear: true,
                maximumSelectionLength: 1, // Limite la sélection à une seule option
            });

        });


    </script>
@endsection

@endsection

