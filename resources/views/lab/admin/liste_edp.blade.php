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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherEdp') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un EDP" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">

        <!-- Bouton pour ouvrir le modal pour ajouter un EDP -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEdpModal">
                Ajouter un EDP
            </button>
        </div>

    </div>
</div>

<div class="p-5">
    @if ($edps->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucune EDP disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($edps as $edp)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                EDP - {{ $edp->sigleEDP ?? 'EDP' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Informations principales -->
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-2 text-primary">{{ $edp->nomEDP }}</h6>
                                @if($edp->localisationEDP)
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-secondary"></i>
                                        <strong>Localisation:</strong> {{ $edp->localisationEDP }}
                                    </p>
                                @endif
                            </div>

                            <!-- Direction -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-secondary mb-2">
                                    <i class="fas fa-user-tie"></i> Direction
                                </h6>
                                @if($edp->directeur)
                                    <p class="mb-2">
                                        <strong>Directeur:</strong>
                                        {{ $edp->directeur->nomCherch }} {{ $edp->directeur->prenomCherch }}
                                    </p>
                                @endif
                            </div>

                            <!-- Secrétariat -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-secondary mb-2">
                                    <i class="fas fa-user-friends"></i> Secrétariat
                                </h6>
                                @if($edp->secretaireEDP)
                                    <p class="mb-2">
                                        <strong>Secrétaire:</strong> {{ $edp->secretaireEDP }}
                                    </p>
                                @endif
                                @if($edp->contactSecretariatEDP)
                                    <p class="mb-2">
                                        <i class="fas fa-phone text-secondary"></i>
                                        <strong>Contact:</strong> {{ $edp->contactSecretariatEDP }}
                                    </p>
                                @endif
                                @if($edp->emailSecretariatEDP)
                                    <p class="mb-2">
                                        <i class="fas fa-envelope text-secondary"></i>
                                        <strong>Email:</strong>
                                        <a href="mailto:{{ $edp->emailSecretariatEDP }}">
                                            {{ $edp->emailSecretariatEDP }}
                                        </a>
                                    </p>
                                @endif
                            </div>

                            <!-- Informations de base -->
                            <div class="mt-3">
                                @if($edp->localisationEDP)
                                    <p class="mb-1">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="font-weight-bold text-dark">Localisation :</span>
                                        {{ $edp->localisationEDP }}
                                    </p>
                                @endif

                                <!-- Nombre d'UMRIs -->
                                <p class="mb-1">
                                    <i class="fas fa-sitemap"></i>
                                    <span class="font-weight-bold text-dark">UMRIs rattachés :</span>
                                    <span class="badge badge-warning">{{ $edp->umris->count() }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">

                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#detailsModal_{{ $edp->idEDP }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>
                                <div>
                                    <a href="{{ route('admin.modifierEDP', $edp->idEDP) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>

                                    <form id="deleteEDPForm" style="display: inline;" method="POST">
                                        @csrf
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="confirmDelete({{ $edp->idEDP }})">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Détails (à l'intérieur de la boucle) -->
                    <div class="modal fade" id="detailsModal_{{ $edp->idEDP }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-secondary text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-info-circle"></i> Détails de l'EDP
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
                                            <strong>Sigle :</strong> {{ $edp->sigleEDP }}
                                        </p>
                                        <p class="mb-2">
                                            <strong>Nom :</strong> {{ $edp->nomEDP }}
                                        </p>
                                        @if($edp->localisationEDP)
                                            <p class="mb-2">
                                                <i class="fas fa-map-marker-alt text-secondary"></i>
                                                <strong>Localisation :</strong> {{ $edp->localisationEDP }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Direction -->
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-user-tie"></i> Direction
                                        </h6>
                                        @if($edp->directeur)
                                            <p class="mb-2">
                                                <strong>Directeur :</strong>
                                                {{ $edp->directeur->nomCherch }} {{ $edp->directeur->prenomCherch }}
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
                                        @if($edp->secretaireEDP || $edp->contactSecretariatEDP || $edp->emailSecretariatEDP)
                                            @if($edp->secretaireEDP)
                                                <p class="mb-2">
                                                    <strong>Secrétaire :</strong> {{ $edp->secretaireEDP }}
                                                </p>
                                            @endif
                                            @if($edp->contactSecretariatEDP)
                                                <p class="mb-2">
                                                    <i class="fas fa-phone text-secondary"></i>
                                                    <strong>Contact :</strong> {{ $edp->contactSecretariatEDP }}
                                                </p>
                                            @endif
                                            @if($edp->emailSecretariatEDP)
                                                <p class="mb-2">
                                                    <i class="fas fa-envelope text-secondary"></i>
                                                    <strong>Email :</strong>
                                                    <a href="mailto:{{ $edp->emailSecretariatEDP }}">
                                                        {{ $edp->emailSecretariatEDP }}
                                                    </a>
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-muted">Aucune information de secrétariat disponible</p>
                                        @endif
                                    </div>

                                    <!-- UMRIs associés -->
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-warning mb-3">
                                            <i class="fas fa-sitemap"></i> UMRIs rattachés
                                            <span class="badge badge-warning">{{ $edp->umris->count() }}</span>
                                        </h6>
                                        @if($edp->umris->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Sigle</th>
                                                            <th>Nom</th>
                                                            <th>Directeur</th>
                                                            <th>Localisation</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($edp->umris as $umri)
                                                            <tr>
                                                                <td>{{ $umri->sigleUMRI }}</td>
                                                                <td>{{ $umri->nomUMRI }}</td>
                                                                <td>
                                                                    @if($umri->directeur)
                                                                        {{ $umri->directeur->nomCherch }} {{ $umri->directeur->prenomCherch }}
                                                                    @else
                                                                        <span class="text-muted">Non défini</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($umri->localisationUMRI)
                                                                        {{ $umri->localisationUMRI }}
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
                                            <p class="text-muted">Aucun UMRI rattaché</p>
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
            {{ $edps->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>


<!-- Modal pour enregistrer un EDP -->
<div class="modal fade" id="addEdpModal" tabindex="-1" role="dialog" aria-labelledby="addEdpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addEdpModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouvel EDP
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerEdp') }}" method="POST">
                    @csrf

                    <!-- Informations principales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations principales</h5>
                        </div>
                        <div class="card-body">
                            <!-- Sigle -->
                            <div class="form-group mb-4">
                                <label for="sigleEDP" class="font-weight-bold">
                                    Sigle de l'EDP <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('sigleEDP') is-invalid @enderror"
                                       id="sigleEDP" name="sigleEDP" placeholder="Ex: EDP-01" required>
                                @error('sigleEDP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nom -->
                            <div class="form-group mb-4">
                                <label for="nomEDP" class="font-weight-bold">
                                    Nom de l'EDP <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nomEDP') is-invalid @enderror"
                                       id="nomEDP" name="nomEDP" placeholder="Nom complet de l'EDP" required>
                                @error('nomEDP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Localisation -->
                            <div class="form-group">
                                <label for="localisationEDP" class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt"></i> Localisation
                                </label>
                                <input type="text" class="form-control @error('localisationEDP') is-invalid @enderror"
                                       id="localisationEDP" name="localisationEDP" placeholder="Adresse de l'EDP">
                                @error('localisationEDP')
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
                                <label for="idDirecteurEDP" class="font-weight-bold">Directeur</label>
                                <select class="form-control select2 @error('idDirecteurEDP') is-invalid @enderror"
                                        id="idDirecteurEDP" name="idDirecteurEDP" multiple="multiple">
                                    {{-- <option value="">Sélectionner un directeur</option> --}}
                                    @foreach($chercheurs as $chercheur)
                                        <option value="{{ $chercheur->idCherch }}">
                                            {{ $chercheur->prenomCherch }} {{ $chercheur->nomCherch }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idDirecteurEDP')
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
                                <label for="secretaireEDP" class="font-weight-bold">Nom du secrétaire</label>
                                <input type="text" class="form-control @error('secretaireEDP') is-invalid @enderror"
                                       id="secretaireEDP" name="secretaireEDP" placeholder="Nom complet du secrétaire">
                                @error('secretaireEDP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact -->
                            <div class="form-group mb-4">
                                <label for="contactSecretariatEDP" class="font-weight-bold">
                                    <i class="fas fa-phone"></i> Contact
                                </label>
                                <input type="number" class="form-control @error('contactSecretariatEDP') is-invalid @enderror"
                                       id="contactSecretariatEDP" name="contactSecretariatEDP"
                                       placeholder="Numéro de téléphone">
                                @error('contactSecretariatEDP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="emailSecretariatEDP" class="font-weight-bold">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control @error('emailSecretariatEDP') is-invalid @enderror"
                                       id="emailSecretariatEDP" name="emailSecretariatEDP"
                                       placeholder="adresse@email.com">
                                @error('emailSecretariatEDP')
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
@parent
<script>
    $(document).ready(function() {
        $('#idDirecteurEDP').select2({
            placeholder: 'Sélectionner un directeur',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
            language: {
                noResults: function() {
                    return "Aucune base trouvée";
                },
                searching: function() {
                    return "Recherche...";
                },
                maximumSelectionLength: function() {
                    return "Vous ne pouvez sélectionner qu'un seul élément";  // Message personnalisé en français
                }
            },

        });

        $('.select2-selection').css('min-height', '40px'); // Applique la hauteur après initialisation

    });

    function confirmDelete(edpId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cet EDP ?",
            text: "Cette action est irréversible et ne peut être effectuée si des UMRIs sont associés.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire et mettre à jour l'URL de l'action
                const form = document.getElementById('deleteEDPForm');
                form.action = '/admin/supprimer-edp/' + edpId;

                // Soumettre le formulaire
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

    .modal-dialog.modal-lg {
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
</style>
@endsection




  <!-- Métadonnées -->
  {{-- <div class="mt-3">
    <small class="text-muted">
        <i class="fas fa-clock"></i> Créé le:
        {{ \Carbon\Carbon::parse($edp->created_at)->format('d/m/Y H:i') }}
    </small>
    @if($edp->updated_at != $edp->created_at)
        <br>
        <small class="text-muted">
            <i class="fas fa-edit"></i> Dernière modification:
            {{ \Carbon\Carbon::parse($edp->updated_at)->format('d/m/Y H:i') }}
        </small>
    @endif
</div> --}}
