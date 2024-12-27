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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherDoctorant') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un doctorant" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>


<div class="container d-flex mt-5 align-items-center">
    <!-- Bouton pour ajouter un doctorant -->
    <div class="d-flex justify-content-end w-100">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDoctorantModal">
            Ajouter un Doctorant
        </button>
    </div>
</div>

<div class="p-5">
    @if ($doctorants->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun doctorant disponible.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($doctorants as $doctorant)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $doctorant->prenomDoc ?? '' }} {{ $doctorant->nomDoc }}
                            </h5>
                            <p class="card-text">
                                @if($doctorant->theme)
                                    Thème : {{ $doctorant->theme->descTheme }}
                                @endif
                            </p>
                            <p>
                                @if($doctorant->encadrants->isNotEmpty())
                                    @php
                                        // On récupère la première date de début et de fin parmi les encadrants
                                        $dateDebut = $doctorant->encadrants->first()->pivot->dateDebut;
                                        $dateFin = $doctorant->encadrants->first()->pivot->dateFin;
                                    @endphp


                                    @if($dateFin)
                                    Date début : {{ Carbon::parse($dateDebut)->format('d-m-Y') }}<br>
                                    Date fin : {{ Carbon::parse($dateFin)->format('d-m-Y') }}
                                    @else
                                    Date début : {{ Carbon::parse($dateDebut)->format('d-m-Y') }}
                                    @endif

                                @endif
                            </p>

                            @if ($doctorant->encadrants->isNotEmpty())
                                @if ($doctorant->encadrants->count() == 1)
                                    <!-- Si un seul encadrant -->
                                    <strong>Encadrant :</strong> {{ $doctorant->encadrants->first()->prenomCherch }} {{ $doctorant->encadrants->first()->nomCherch }}
                                @else
                                    <!-- Si plusieurs encadrants -->
                                    <strong>Encadrants :</strong>
                                    <ul class="list-unstyled">
                                        @foreach ($doctorant->encadrants as $encadrant)
                                            <li>- {{ $encadrant->prenomCherch }} {{ $encadrant->nomCherch }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <form action="{{ route('admin.modifierDoctorant', $doctorant->idDoc) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2">Modifier</button>
                            </form>

                            <form id="deleteDoctorantForm" action="{{ route('admin.supprimerDoctorant', $doctorant->idDoc) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $doctorant->idDoc }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="d-flex justify-content-center mt-4">
            {{ $doctorants->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal pour enregistrer un doctorant -->
<div class="modal fade" id="addDoctorantModal" tabindex="-1" aria-labelledby="addDoctorantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.enregistrerDoctorant') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorantModalLabel">Ajouter un Doctorant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomDoc">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomDoc" name="nomDoc" required>
                    </div>
                    <div class="form-group">
                        <label for="prenomDoc">Prénom</label>
                        <input type="text" class="form-control" id="prenomDoc" name="prenomDoc">
                    </div>

                    <div class="form-group">
                        <label for="idTheme">Thème de Recherche <span class="text-danger">*</span></label>
                        <select class="form-control" id="idTheme" name="idTheme" multiple required>
                            <option value="" disabled>-- Sélectionnez un Thème --</option>
                            @foreach ($themes as $theme)
                                <option value="{{ $theme->idTheme }}">{{ $theme->descTheme }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="idCherch">Chercheurs Encadrants <span class="text-danger">*</span></label>
                        <select class="form-control" id="encadreur" name="idCherch[]" multiple required>
                            <option value="" disabled>-- Sélectionnez des Chercheurs --</option>
                            @foreach ($chercheurs as $chercheur)
                                <option value="{{ $chercheur->idCherch }}">{{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dateDebut">Date de Début</label>
                        <input type="date" class="form-control" id="dateDebut" name="dateDebut">
                    </div>
                    <div class="form-group">
                        <label for="dateFin">Date de Fin</label>
                        <input type="date" class="form-control" id="dateFin" name="dateFin">
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


@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('#encadreur').select2({
            placeholder: 'Sélectionnez un ou plusieurs Encadreurs',
            allowClear: true,
            width: '100%'  // Assurez-vous que le champ utilise toute la largeur disponible
        });


        $('#idTheme').select2({
            allowClear: true,
            maximumSelectionLength: 1, // Limite la sélection à une seule option
            width: '100%' // Ajuste la largeur pour un affichage responsive
        });

    });


    function confirmDelete(doctorantId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce doctorant ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire avec l'ID générique
                const form = document.getElementById('deleteDoctorantForm');
                // Modifier l'action du formulaire pour inclure l'ID du doctorant
                form.action = '/admin/supprimer-doctorant/' + doctorantId;
                // Soumettre le formulaire
                form.submit();
            }
        });
    }


</script>


@endsection
