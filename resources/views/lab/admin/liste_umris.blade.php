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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherUmris') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un UMRI" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">

        <!-- Bouton pour ouvrir le modal pour ajouter un EDP -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUmriModal">
                Ajouter un UMRI
            </button>
        </div>

    </div>
</div>

<div class="p-5">
    @if ($umris->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucune UMRI disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($umris as $umri)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-building"></i> UMRI - {{ $umri->sigleUMRI }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Informations principales -->
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-2 text-primary">{{ $umri->nomUMRI }}</h6>
                                @if($umri->localisationUMRI)
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-secondary"></i>
                                        <strong>Localisation:</strong> {{ $umri->localisationUMRI }}
                                    </p>
                                @endif
                            </div>

                            <!-- Direction -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-info mb-2">
                                    <i class="fas fa-user-tie"></i> Direction
                                </h6>
                                @if($umri->directeur)
                                    <p class="mb-2">
                                        <strong>Directeur:</strong>
                                        {{ $umri->directeur->nomCherch }} {{ $umri->directeur->prenomCherch }}
                                    </p>
                                @endif
                            </div>

                            <!-- Secrétariat -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-success mb-2">
                                    <i class="fas fa-user-friends"></i> Secrétariat
                                </h6>
                                @if($umri->secretaireUMRI)
                                    <p class="mb-2">
                                        <strong>Secrétaire:</strong> {{ $umri->secretaireUMRI }}
                                    </p>
                                @endif
                                @if($umri->contactSecretariatUMRI)
                                    <p class="mb-2">
                                        <i class="fas fa-phone text-secondary"></i>
                                        <strong>Contact:</strong> {{ $umri->contactSecretariatUMRI }}
                                    </p>
                                @endif
                                @if($umri->emailSecretariatUMRI)
                                    <p class="mb-2">
                                        <i class="fas fa-envelope text-secondary"></i>
                                        <strong>Email:</strong>
                                        <a href="mailto:{{ $umri->emailSecretariatUMRI }}">
                                            {{ $umri->emailSecretariatUMRI }}
                                        </a>
                                    </p>
                                @endif
                            </div>

                            <!-- Rattachement -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-warning mb-2">
                                    <i class="fas fa-sitemap"></i> Rattachement
                                </h6>
                                @if($umri->edp)
                                    <p class="mb-2">
                                        <strong>EDP :</strong> {{ $umri->edp->sigleEDP }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#detailsModal_{{ $umri->idUMRI }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>
                                <div>
                                    <a href="{{ route('admin.modifierUmris', $umri->idUMRI) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form id="deleteUmriForm_{{ $umri->idUMRI }}"
                                        action="{{ route('admin.supprimerUmri', $umri->idUMRI) }}"
                                        method="POST"
                                        style="display: inline;">
                                      @csrf
                                      <button type="button" class="btn btn-outline-danger btn-sm"
                                              onclick="confirmDelete({{ $umri->idUMRI }})">
                                          <i class="fas fa-trash"></i> Supprimer
                                      </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Détails (à l'intérieur de la boucle foreach) -->
                <div class="modal fade" id="detailsModal_{{ $umri->idUMRI }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-info-circle"></i> Détails de l'UMRI
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Informations principales -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-primary mb-3">
                                        <i class="fas fa-building"></i> Informations principales
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Sigle :</strong> {{ $umri->sigleUMRI }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Nom :</strong> {{ $umri->nomUMRI }}
                                    </p>
                                    @if($umri->localisationUMRI)
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt text-secondary"></i>
                                            <strong>Localisation :</strong> {{ $umri->localisationUMRI }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Direction -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-info mb-3">
                                        <i class="fas fa-user-tie"></i> Direction
                                    </h6>
                                    @if($umri->directeur)
                                        <p class="mb-2">
                                            <strong>Directeur :</strong>
                                            {{ $umri->directeur->nomCherch }} {{ $umri->directeur->prenomCherch }}
                                        </p>
                                    @else
                                        <p class="text-muted">Aucun directeur assigné</p>
                                    @endif
                                </div>

                                <!-- Secrétariat -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-success mb-3">
                                        <i class="fas fa-user-friends"></i> Secrétariat
                                    </h6>
                                    @if($umri->secretaireUMRI || $umri->contactSecretariatUMRI || $umri->emailSecretariatUMRI)
                                        @if($umri->secretaireUMRI)
                                            <p class="mb-2">
                                                <strong>Secrétaire :</strong> {{ $umri->secretaireUMRI }}
                                            </p>
                                        @endif
                                        @if($umri->contactSecretariatUMRI)
                                            <p class="mb-2">
                                                <i class="fas fa-phone text-secondary"></i>
                                                <strong>Contact :</strong> {{ $umri->contactSecretariatUMRI }}
                                            </p>
                                        @endif
                                        @if($umri->emailSecretariatUMRI)
                                            <p class="mb-2">
                                                <i class="fas fa-envelope text-secondary"></i>
                                                <strong>Email :</strong>
                                                <a href="mailto:{{ $umri->emailSecretariatUMRI }}">
                                                    {{ $umri->emailSecretariatUMRI }}
                                                </a>
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-muted">Aucune information de secrétariat disponible</p>
                                    @endif
                                </div>

                                <!-- Rattachement -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-warning mb-3">
                                        <i class="fas fa-sitemap"></i> Rattachement
                                    </h6>
                                    @if($umri->edp)
                                        <p class="mb-2">
                                            <strong>EDP :</strong> {{ $umri->edp->sigleEDP }} - {{ $umri->edp->nomEDP }}
                                        </p>
                                    @else
                                        <p class="text-muted">Aucun EDP de rattachement</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $umris->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>
</div>

<!-- Modal pour enregistrer un UMRI -->
<div class="modal fade" id="addUmriModal" tabindex="-1" role="dialog" aria-labelledby="addUmriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addUmriModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouvel UMRI
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerUmris') }}" method="POST">
                    @csrf

                    <!-- Informations principales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations principales</h5>
                        </div>
                        <div class="card-body">
                            <!-- Sigle -->
                            <div class="form-group mb-4">
                                <label for="sigleUMRI" class="font-weight-bold">
                                    Sigle de l'UMRI <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('sigleUMRI') is-invalid @enderror"
                                       id="sigleUMRI" name="sigleUMRI" placeholder="Ex: UMRI-01" required>
                                @error('sigleUMRI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nom -->
                            <div class="form-group mb-4">
                                <label for="nomUMRI" class="font-weight-bold">
                                    Nom de l'UMRI <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nomUMRI') is-invalid @enderror"
                                       id="nomUMRI" name="nomUMRI" placeholder="Nom complet de l'UMRI" required>
                                @error('nomUMRI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Localisation -->
                            <div class="form-group">
                                <label for="localisationUMRI" class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt"></i> Localisation
                                </label>
                                <input type="text" class="form-control @error('localisationUMRI') is-invalid @enderror"
                                       id="localisationUMRI" name="localisationUMRI" placeholder="Adresse de l'UMRI">
                                @error('localisationUMRI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Direction -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-user-tie"></i> Direction</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="idDirecteurUMRI" class="font-weight-bold">Directeur</label>
                                <select class="form-control select2 @error('idDirecteurUMRI') is-invalid @enderror"
                                        id="idDirecteurUMRI" name="idDirecteurUMRI">
                                    <option value="">Sélectionner un directeur</option>
                                    @foreach($chercheurs as $chercheur)
                                        <option value="{{ $chercheur->idCherch }}">
                                            {{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idDirecteurUMRI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Secrétariat -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-user-friends"></i> Secrétariat</h5>
                        </div>
                        <div class="card-body">
                            <!-- Secrétaire -->
                            <div class="form-group mb-4">
                                <label for="secretaireUMRI" class="font-weight-bold">Nom du secrétaire</label>
                                <input type="text" class="form-control @error('secretaireUMRI') is-invalid @enderror"
                                       id="secretaireUMRI" name="secretaireUMRI" placeholder="Nom complet du secrétaire">
                                @error('secretaireUMRI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact -->
                            <div class="form-group mb-4">
                                <label for="contactSecretariatUMRI" class="font-weight-bold">
                                    <i class="fas fa-phone"></i> Contact
                                </label>
                                <input type="text" class="form-control @error('contactSecretariatUMRI') is-invalid @enderror"
                                       id="contactSecretariatUMRI" name="contactSecretariatUMRI"
                                       placeholder="Numéro de téléphone">
                                @error('contactSecretariatUMRI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="emailSecretariatUMRI" class="font-weight-bold">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control @error('emailSecretariatUMRI') is-invalid @enderror"
                                       id="emailSecretariatUMRI" name="emailSecretariatUMRI"
                                       placeholder="adresse@email.com">
                                @error('emailSecretariatUMRI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- EDP -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-building"></i> Rattachement</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="idEDP" class="font-weight-bold">
                                    EDP de rattachement <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2 @error('idEDP') is-invalid @enderror"
                                        id="idEDP" name="idEDP" required>
                                    <option value="">Sélectionner un EDP</option>
                                    @foreach($edps as $edp)
                                        <option value="{{ $edp->idEDP }}">
                                            {{ $edp->sigleEDP }} - {{ $edp->nomEDP }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idEDP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
        $('#idDirecteurUMRI').select2({
            placeholder: 'Sélectionner un directeur',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
            dropdownParent: $('#addUmriModal')
        });

        $('#idEDP').select2({
            placeholder: 'Sélectionner un EDP',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
            dropdownParent: $('#addUmriModal')
        });


        $('.select2-selection').css('height', '40px'); // Applique la hauteur après initialisation

    });

    function confirmDelete(umriId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cette UMRI ?",
            text: "Cette action est irréversible et ne peut être effectuée si des laboratoires ou chercheurs sont associés.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteUmriForm_' + umriId);
                form.submit();
            }
        });
    }
</script>
@endsection

@section('styles')
<style>
    .modal-dialog {
        margin-top: 2rem;
        display: flex;
        align-items: flex-start;
        min-height: calc(100% - 4rem);
    }

    .modal {
        overflow-y: auto;
    }

    /* Pour assurer que le modal est bien centré horizontalement */
    .modal-dialog.modal-lg {
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
</style>
@endsection
