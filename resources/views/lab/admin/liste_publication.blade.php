@extends("baseAdmin")

@section('bg-content', 'bg-white')

@section('content')

<div class="container mt-4">
    {{-- Messages d'erreur et de succès --}}
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert">
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
    <form class="form-inline justify-content-center my-2" action="{{ route('admin.rechercherPublication') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query"
               placeholder="Rechercher une publication" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 mr-5 px-5 align-items-center">
    <div class="d-flex justify-content-between w-100">
        <!-- Filtre avec select -->
        <div class="col-md-4">
            <form action="{{ route('admin.listePublications') }}" method="GET" class="mb-0">
                <select class="custom-select" name="filter" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    @foreach($typesPublications as $type)
                        <option value="{{ $type->idTypePub }}"
                            {{ request('filter') == $type->idTypePub ? 'selected' : '' }}>
                            {{ $type->libeleTypePub }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Bouton d'ajout -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPublicationModal">
            <i class="fas fa-plus-circle"></i> Nouveau
        </button>
    </div>
</div>

<div class="p-5">
    @if ($publications->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucune publication disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucune publication" class="img-fluid"
                 style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($publications as $publication)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-book"></i> {{ $publication->titrePub }}
                                </h6>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Type de publication (obligatoire) -->
                            <p class="mb-2">
                                <span class="badge badge-info">
                                    {{ $publication->typePublication->libeleTypePub }}
                                </span>
                            </p>

                            <!-- Informations de base -->
                            <div class="mt-3">
                                @if($publication->ISSN)
                                    <p class="mb-1">
                                        <i class="fas fa-fingerprint"></i>
                                        <span class="font-weight-bold text-dark">ISSN :</span>
                                        {{ $publication->ISSN }}
                                    </p>
                                @endif
                                @if($publication->editeurPub)
                                    <p class="mb-1">
                                        <i class="fas fa-building"></i>
                                        <span class="font-weight-bold text-dark">Éditeur :</span>
                                        {{ $publication->editeurPub }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">

                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#detailsModal_{{ $publication->idPub }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>

                                <div class="d-flex">
                                    <a href="{{ route('admin.modifierPublication', $publication->idPub) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>

                                    <form id="deletePublicationForm" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDelete({{ $publication->idPub }})">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Détails (à l'intérieur de la boucle) -->
                    <div class="modal fade" id="detailsModal_{{ $publication->idPub }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-info-circle"></i> Détails de la publication
                                    </h5>
                                    <button type="button" class=" close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Description -->
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-2">
                                            <i class="fas fa-align-left"></i> Description
                                        </h6>
                                        @if($publication->descPub)
                                            <p>{{ $publication->descPub }}</p>
                                        @else
                                            <p class="text-muted">Aucune description disponible</p>
                                        @endif
                                    </div>

                                    <!-- Articles associés -->
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-file-alt"></i> Articles associés
                                            <span class="badge badge-info">{{ $publication->articles->count() }}</span>
                                        </h6>
                                        @if($publication->articles->isNotEmpty())
                                            <div class="list-group">
                                                @foreach($publication->articles as $article)
                                                    <div class="list-group-item">
                                                        <h6 class="mb-1">{{ $article->titreArticle }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar"></i>
                                                            {{ \Carbon\Carbon::parse($article->datePubArt)->format('d/m/Y') }}
                                                        </small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">Aucun article associé</p>
                                        @endif
                                    </div>

                                    <!-- Bases d'indexation -->
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-warning mb-3">
                                            <i class="fas fa-database"></i> Bases d'indexation
                                            <span class="badge badge-warning">{{ $publication->bdIndexations->count() }}</span>
                                        </h6>
                                        @if($publication->bdIndexations->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Base d'indexation</th>
                                                            <th>Date début</th>
                                                            <th>Date fin</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($publication->bdIndexations as $bd)
                                                            <tr>
                                                                <td>{{ $bd->nomBDInd }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($bd->pivot->dateDebut)->format('d/m/Y') }}</td>
                                                                <td>
                                                                    @if($bd->pivot->dateFin)
                                                                        {{ \Carbon\Carbon::parse($bd->pivot->dateFin)->format('d/m/Y') }}
                                                                    @else
                                                                        <span class="text-muted">Non définie</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">Aucune base d'indexation associée</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $publications->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal d'ajout de publication -->
<div class="modal fade" id="addPublicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle"></i> Ajouter une publication
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerPublication') }}" method="POST">
                    @csrf

                    <!-- Informations principales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-book"></i> Informations principales</h6>
                        </div>
                        <div class="card-body">
                            <!-- Titre -->
                            <div class="form-group">
                                <label for="titrePub" class="font-weight-bold">
                                    Titre <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="titrePub" name="titrePub" required>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="descPub" class="font-weight-bold">Description</label>
                                <textarea class="form-control" id="descPub" name="descPub" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Type de publication -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-tags"></i> Classification</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="idTypePub" class="font-weight-bold">
                                    Type de publication <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2" id="idTypePub" name="idTypePub" required>
                                    <option value="">Sélectionner un type</option>
                                    @foreach($typesPublications as $type)
                                        <option value="{{ $type->idTypePub }}">
                                            {{ $type->libeleTypePub }}
                                        </option>
                                    @endforeach
                                </select>
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
                                <input type="text" class="form-control" id="ISSN" name="ISSN">
                            </div>

                            <!-- Éditeur -->
                            <div class="form-group">
                                <label for="editeurPub" class="font-weight-bold">Éditeur</label>
                                <input type="text" class="form-control" id="editeurPub" name="editeurPub">
                            </div>
                        </div>
                    </div>

                    <!-- Bases d'indexation -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark  text-white">
                            <h6 class="mb-0"><i class="fas fa-database"></i> Bases d'indexation</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Sélectionner une base d'indexation</label>
                                <select class="form-control select2-bd" id="bdIndexation">
                                    <option value="">Rechercher et sélectionner une base...</option>
                                    @foreach($bdIndexations as $bd)
                                        <option value="{{ $bd->idBDIndex }}">{{ $bd->nomBDInd }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Liste des bases sélectionnées -->
                            <div id="selectedBdList">
                                <!-- Les bases sélectionnées seront ajoutées ici -->
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Fermer
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: function() {
                return $(this).data('placeholder');
            },
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

    function confirmDelete(publicationId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cette publication ?",
            text: "Cette action est irréversible.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deletePublicationForm');
                form.action = '/admin/supprimerPublication/' + publicationId;
                form.submit();
            }
        });
    }
</script>
@endsection
