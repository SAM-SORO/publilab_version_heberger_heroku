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
                                                    {{ $grade->sigleGrade }} (Depuis le {{ Carbon::parse($grade->pivot->dateGrade)->format('d-m-Y') }})
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
                                    data-nom="{{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}">
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
                        <select class="form-control @error('idLabo') is-invalid @enderror" id="idLabo" name="idLabo" required>
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



<!-- Modal unique pour ajouter un grade -->
<div class="modal fade" id="ajouterGradeModal" tabindex="-1" role="dialog" aria-labelledby="ajouterGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajouterGradeModalLabel">Ajouter un Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.ajouterGrade') }}" method="POST" id="gradeForm">
                    @csrf
                    <input type="hidden" id="chercheurId" name="chercheurId">

                    <div class="mb-3">
                        <label for="chercheurNom" class="form-label">Chercheur</label>
                        <input type="text" class="form-control mb-4" id="chercheurNom" name="chercheurNom" value="{{ old('chercheurNom') }}" readonly>
                    </div>

                    <div id="gradesContainer">
                        <div class="mb-3 grade-block">
                            <label for="sigleGrade" class="form-label">Sigle du grade</label>
                            <input type="text" class="form-control mb-4 @error('grades.0.sigleGrade') is-invalid @enderror" name="grades[0][sigleGrade]" value="{{ old('grades.0.sigleGrade') }}" placeholder="Sigle du grade">
                            @error('grades.0.sigleGrade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label for="nomGrade" class="form-label">Nom du grade</label>
                            <input type="text" class="form-control mb-4 @error('grades.0.nomGrade') is-invalid @enderror" name="grades[0][nomGrade]" value="{{ old('grades.0.nomGrade') }}" required>
                            @error('grades.0.nomGrade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label for="dateGrade" class="form-label">Date d'attribution</label>
                            <input type="date" class="form-control mb-4 @error('grades.0.dateGrade') is-invalid @enderror" name="grades[0][dateGrade]" value="{{ old('grades.0.dateGrade') }}">
                            @error('grades.0.dateGrade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" id="addGradeButton">Ajouter un autre grade</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>



@section('scripts')

<script>
    $(document).ready(function() {
        // Remplir le modal avec les données du chercheur
        $('#ajouterGradeModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var chercheurId = button.data('id');
            var chercheurNom = button.data('nom');

            $(this).find('#chercheurId').val(chercheurId);
            $(this).find('#chercheurNom').val(chercheurNom);
        });

        // Ajout dynamique des champs de grade
        var addGradeButton = document.getElementById('addGradeButton');
        var gradesContainer = document.getElementById('gradesContainer');

        addGradeButton.addEventListener('click', function() {
            var gradeIndex = gradesContainer.querySelectorAll('.grade-block').length;

            var gradeBlock = document.createElement('div');
            gradeBlock.classList.add('mb-3', 'grade-block');

            gradeBlock.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <label for="sigleGrade" class="form-label">Sigle du grade</label>
                        <input type="text" class="form-control mb-4 @error('grades.${gradeIndex}.sigleGrade') is-invalid @enderror" name="grades[${gradeIndex}][sigleGrade]" placeholder="Sigle du grade">
                        @error('grades.${gradeIndex}.sigleGrade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="nomGrade" class="form-label">Nom du grade</label>
                        <input type="text" class="form-control mb-4 @error('grades.${gradeIndex}.nomGrade') is-invalid @enderror" name="grades[${gradeIndex}][nomGrade]" required>
                        @error('grades.${gradeIndex}.nomGrade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="dateGrade" class="form-label">Date d'attribution</label>
                        <input type="date" class="form-control mb-4 @error('grades.${gradeIndex}.dateGrade') is-invalid @enderror" name="grades[${gradeIndex}][dateGrade]">
                        @error('grades.${gradeIndex}.dateGrade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-grade">Retirer</button>
                </div>
            `;

            gradesContainer.appendChild(gradeBlock);

            // Ajout d'un événement pour supprimer le champ
            gradeBlock.querySelector('.remove-grade').addEventListener('click', function() {
                gradesContainer.removeChild(gradeBlock);
            });
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

