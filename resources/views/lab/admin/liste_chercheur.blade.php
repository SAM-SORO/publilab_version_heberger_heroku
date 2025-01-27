@extends("baseAdmin")

@section('bg-content', 'bg-white')

@section('content')

@php
    use Carbon\Carbon;
@endphp


<div class="container mt-4">
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

<div class="container mt-5">
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherChercheur') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un chercheur" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Bouton pour ajouter un chercheur -->
    <div class="d-flex justify-content-end w-100">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addChercheurModal">
            Ajouter un Chercheur
        </button>
    </div>
</div>

<div class="p-5">
    @if ($chercheurs->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun chercheur disponible.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($chercheurs as $chercheur)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}</h5>
                            @if($chercheur->adresse)
                                <p class="card-text"><strong>Adresse :</strong> {{ $chercheur->adresse }}</p>
                            @endif
                            @if($chercheur->telCherch)
                                <p class="card-text"><strong>Téléphone :</strong> {{ $chercheur->telCherch }}</p>
                            @else
                                <p class="card-text"><strong>Téléphone :</strong> Non spécifié</p>
                            @endif
                            @if($chercheur->emailCherch)
                                <p class="card-text"><strong>Email :</strong> {{ $chercheur->emailCherch }}</p>
                            @endif
                            @if($chercheur->specialite)
                                <p class="card-text"><strong>Spécialité :</strong> {{ $chercheur->specialite }}</p>
                            @endif
                            @if($chercheur->dateArrivee)
                                <p class="card-text">
                                    <strong>Date d'arrivée :</strong>
                                    {{ Carbon::parse($chercheur->dateArrivee)->format('d-m-Y') }}
                                </p>
                            @endif

                            <p class="card-text"><strong>Laboratoire :</strong> {{ $chercheur->laboratoire->nomLabo }}</p>

                            <ul class="list-unstyled">
                                @if ($chercheur->grades->isNotEmpty())
                                    <li><strong>Grades :</strong>
                                        <ul class="list-unstyled">
                                            @foreach ($chercheur->grades as $grade)
                                                <li>
                                                    -{{ $grade->sigleGrade }}
                                                    @if ($grade->pivot->dateGrade)
                                                        (Depuis le {{ \Carbon\Carbon::parse($grade->pivot->dateGrade)->format('d-m-Y') }})
                                                    @endif
                                                </li>
                                            @endforeach

                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <!-- Bouton pour ouvrir le modal d'ajout de grade -->
                            <div class="mx-2">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajouterGradeModal"
                                    data-id="{{ $chercheur->idCherch }}"
                                    data-nom="{{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}">
                                    <i class="fas fa-plus"></i> Grade
                                </button>
                            </div>

                            <div class="mx-2">
                                <form action="{{ route('admin.modifierLaboChercheur', $chercheur->idCherch) }}" method="GET">
                                    @csrf
                                    <button class="btn btn-primary">
                                        <i class="fas fa-edit"></i>Modifier
                                    </button>
                                </form>
                            </div>
                            <div class="mx-2">
                                <form id="deleteChercheurForm" method="POST" style="display: none;">
                                    @csrf
                                    @method('POST')
                                </form>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $chercheur->idCherch }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $chercheurs->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>


<!-- Modal pour enregistrer un chercheur -->
<div class="modal fade" id="addChercheurModal" tabindex="-1" aria-labelledby="addChercheurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.enregistrerChercheur') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addChercheurModalLabel">Ajouter un Chercheur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Nom -->
                    <div class="form-group mb-4">
                        <label for="nomCherch">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomCherch') is-invalid @enderror"
                               id="nomCherch" name="nomCherch" value="{{ old('nomCherch') }}" required>
                        @error('nomCherch')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div class="form-group mb-4">
                        <label for="prenomCherch">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('prenomCherch') is-invalid @enderror"
                               id="prenomCherch" name="prenomCherch" value="{{ old('prenomCherch') }}" required>
                        @error('prenomCherch')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div class="form-group mb-4">
                        <label for="adresse">Adresse</label>
                        <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                               id="adresse" name="adresse" value="{{ old('adresse') }}">
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group mb-4">
                        <label for="telCherch">Téléphone</label>
                        <input type="text" class="form-control @error('telCherch') is-invalid @enderror"
                               id="telCherch" name="telCherch" value="{{ old('telCherch') }}">
                        @error('telCherch')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-4">
                        <label for="emailCherch">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('emailCherch') is-invalid @enderror"
                               id="emailCherch" name="emailCherch" value="{{ old('emailCherch') }}" required>
                        @error('emailCherch')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-group mb-4">
                        <label for="password">Mot de Passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmation du mot de passe -->
                    <div class="form-group mb-4">
                        <label for="password_confirmation">Confirmer le Mot de Passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Spécialité -->
                    <div class="form-group mb-4">
                        <label for="specialite">Spécialité</label>
                        <input type="text" class="form-control @error('specialite') is-invalid @enderror"
                               id="specialite" name="specialite" value="{{ old('specialite') }}">
                        @error('specialite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Laboratoire -->
                    <div class="form-group mb-4">
                        <label for="idLabo">Laboratoire <span class="text-danger">*</span></label>
                        <select class="form-control @error('idLabo') is-invalid @enderror" id="idLabo" name="idLabo" multiple required>
                            <option value="" disabled {{ old('idLabo') ? '' : 'selected' }}>-- Sélectionnez un Laboratoire --</option>
                            @foreach ($laboratoires as $labo)
                                <option value="{{ $labo->idLabo }}" {{ old('idLabo') == $labo->idLabo ? 'selected' : '' }}>
                                    {{ $labo->nomLabo }}
                                </option>
                            @endforeach
                        </select>
                        @error('idLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date d'Arrivée -->
                    <div class="form-group mb-4">
                        <label for="dateArrivee">Date d'Arrivée</label>
                        <input type="date" class="form-control @error('dateArrivee') is-invalid @enderror"
                               id="dateArrivee" name="dateArrivee" value="{{ old('dateArrivee') }}">
                        @error('dateArrivee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour Attribuer les Grades -->
<div class="modal fade" id="ajouterGradeModal" tabindex="-1" role="dialog" aria-labelledby="ajouterGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajouterGradeModalLabel">Attribuer des Grades</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.ajouterGrade') }}" method="POST" id="gradeForm">
                    @csrf
                    <input type="hidden" id="chercheurId" name="chercheurId">

                    <!-- Affichage du nom du chercheur -->
                    <div class="mb-3">
                        <label for="chercheurNom" class="form-label">Chercheur</label>
                        <input type="text" class="form-control" id="chercheurNom" readonly>
                    </div>

                    <!-- Sélection des grades avec Select2 -->
                    <div class="form-group mb-4">
                        <label for="grades">Sélectionner les Grades</label>
                        <select class="form-control select2" id="grades" name="grades[]" multiple>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->idGrade }}">{{ $grade->nomGrade }} ({{ $grade->sigleGrade }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Vous pouvez sélectionner plusieurs grades</small>
                    </div>

                    <!-- Container pour les dates des grades -->
                    <div id="dates-container" class="mt-4">
                        <!-- Les champs de date sont générés dynamiquement ici -->
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>


@section('scripts')

<script>
    $(document).ready(function () {
        // Initialisation de Select2
        $('.select2').select2({
            placeholder: "Sélectionnez les grades",
            allowClear: true,
            width: '100%' // Adaptation responsive
        });

        $('#idLabo').select2({
            allowClear: true,
            maximumSelectionLength: 1, // Limite la sélection à une seule option
            width: '100%' // Ajuste la largeur pour un affichage responsive
        });

        // Dynamiser l'ajout des champs de date pour chaque grade sélectionné
        $('#grades').on('change', function () {
            const selectedGrades = $(this).val(); // Récupérer les grades sélectionnés
            const datesContainer = $('#dates-container');

            // Vider le conteneur des dates
            datesContainer.empty();

            // Générer un champ pour chaque grade sélectionné
            selectedGrades.forEach(function (gradeId) {
                const gradeName = $('#grades option[value="' + gradeId + '"]').text();
                const dateField = `
                    <div class="form-group mb-3 date-group" data-grade-id="${gradeId}">
                        <label>Date d'obtention pour le grade : ${gradeName}</label>
                        <input type="date" class="form-control" name="dates[${gradeId}]">
                    </div>
                `;
                datesContainer.append(dateField);
            });
        });

        // Gérer les données dans le modal
        $('#ajouterGradeModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const chercheurId = button.data('id');
            const chercheurNom = button.data('nom');

            // Remplir les champs cachés et affichés
            $('#chercheurId').val(chercheurId);
            $('#chercheurNom').val(chercheurNom);

            // Réinitialiser Select2 et le conteneur des dates
            $('#grades').val(null).trigger('change');
            $('#dates-container').empty();
        });
    });


    function confirmDelete(chercheurId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce chercheur ?",
            text: "Cette action est irréversible et pourrait affecter les données associées.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire et mettre à jour l'URL de l'action
                const form = document.getElementById('deleteChercheurForm');
                form.action = '/admin/supprimer-chercheur/' + chercheurId;

                // Soumettre le formulaire
                form.submit();
            }
        });
    }


</script>

@endsection

@endsection

