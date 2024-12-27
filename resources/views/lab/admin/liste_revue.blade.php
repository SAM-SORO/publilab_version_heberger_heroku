@extends("baseAdmin")

@section('bg-content', 'bg-white')

@section('content')

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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherRevue') }}" method="GET">
        @csrf
        <input
            class="form-control col-lg-8 col-6 col-sm-8 py-4"
            type="search"
            name="query"
            placeholder="Rechercher une revue"
            aria-label="Rechercher"
            value="{{ request('query') }}"
        >
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Bouton pour ajouter une revue -->
    <div class="d-flex justify-content-end w-100">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRevueModal">
            Ajouter une Revue
        </button>
    </div>
</div>

<div class="p-5">
    @if ($revues->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucune revue disponible.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($revues as $revue)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $revue->nomRevue }}</h5>
                            <p class="card-text">ISSN : {{ $revue->ISSN }}</p>

                            @if ($revue->typeRevue)
                                <p class="card-text">Type : {{ $revue->typeRevue }}</p>
                            @endif

                            @if ($revue->descRevue)
                                <p class="card-text">Description : {{ $revue->descRevue }}</p>
                            @endif

                            @if ($revue->bdIndexations->isNotEmpty())
                                <p class="card-text">
                                    Bases d'indexation :
                                    <ul class="list-unstyled">
                                        @foreach ($revue->bdIndexations as $bdIndexation)
                                            <li>
                                                {{ $bdIndexation->nomBDInd }}
                                                ( date de debut: {{ \Carbon\Carbon::parse
                                                ($bdIndexation->pivot->dateDebut)->format('d-m-y') }} /
                                                date de fin : {{ \Carbon\Carbon::parse
                                                ($bdIndexation->pivot->dateFin)->format('d-m-y') }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </p>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <form action="{{ route('admin.modifierRevue', $revue->idRevue) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2">Modifier</button>
                            </form>
                            
                            <form id="deleteRevueForm" action="{{ route('admin.supprimerRevue', $revue->idRevue) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')  <!-- Utilisation de la méthode POST -->
                                <button type="submit" id="submitDelete" style="display:none;"></button> <!-- Bouton caché -->
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $revue->idRevue }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $revues->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal pour enregistrer une revue -->
<!-- Modal pour enregistrer une revue -->
<div class="modal fade" id="addRevueModal" tabindex="-1" aria-labelledby="addRevueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.enregistrerRevue') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRevueModalLabel">Ajouter une Revue</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ISSN">ISSN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ISSN" name="ISSN" required>
                    </div>
                    <div class="form-group">
                        <label for="nomRevue">Nom de la Revue <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomRevue" name="nomRevue" required>
                    </div>
                    <div class="form-group">
                        <label for="descRevue">Description</label>
                        <textarea class="form-control" id="descRevue" name="descRevue"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="typeRevue">Type de la Revue</label>
                        <input type="text" class="form-control" id="typeRevue" name="typeRevue">
                    </div>

                    <!-- Sélection de la base d'indexation -->
                    <div class="form-group">
                        <label for="bdIndexation">Base d'Indexation</label>
                        <select class="form-control" id="bdIndexation" name="bdIndexation[]" multiple>
                            @foreach ($bdIndexations as $bdIndexation)
                                <option value="{{ $bdIndexation->idBDIndex }}">{{ $bdIndexation->nomBDInd }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dates de début et de fin -->
                    <div class="form-group">
                        <label for="dateDebut">Date de début</label>
                        <input type="date" class="form-control" id="dateDebut" name="dateDebut">
                    </div>
                    <div class="form-group">
                        <label for="dateFin">Date de fin</label>
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

        $('#bdIndexation').select2({
            placeholder: 'Sélectionnez une base d\'indexation',
            allowClear: true,
            maximumSelectionLength: 1, // Limite la sélection à une seule option
            width: '100%' // Ajuste la largeur pour un affichage responsive
        });

    });

    function confirmDelete() {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cette revue ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Soumettre le formulaire après confirmation
                // Trouver le formulaire en fonction de l'ID de la revue
                const form = document.getElementById('deleteRevueForm');
                // Soumettre le formulaire
                form.submit();
            }
        });
    }

</script>


@endsection
