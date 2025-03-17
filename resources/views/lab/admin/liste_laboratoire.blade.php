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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherLaboratoire') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un laboratoire" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">
        {{-- <!-- Formulaire de filtre pour l'EDP -->
        <div class=" col-9">
            <form action="{{ route('admin.filtrerEdp') }}" method="GET">
                @csrf
                <select title="filtre par nom" class="custom-select col-4 col-lg-2 col-sm-6 col-md-3" name="nomEDP" onchange="this.form.submit()">
                    <option value="Tous">Filtre</option>
                    <option value="Tous" {{ request('nomEDP') === 'Tous' ? 'selected' : '' }}>Tous</option>
                    @foreach ($edps as $edp)
                        <option value="{{ $edp->idEDP }}" {{ request('nomEDP') == $edp->idEDP ? 'selected' : '' }}>{{ $edp->nomEDP }}</option>
                    @endforeach
                </select>
            </form>
        </div> --}}

        <!-- Bouton pour ouvrir le modal pour ajouter un EDP -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLaboModal">
                Ajouter un Laboratoire
            </button>
        </div>

    </div>
</div>

<div class="p-5">
    @if ($laboratoires->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun laboratoire disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                @foreach ($laboratoires as $labo)
                    <div class="laboratoire-item p-4 border-bottom">
                        <div class="row">
                            <!-- En-tête du laboratoire -->
                            <div class="col-12 mb-3">
                                <h4 class="text-primary mb-1">
                                    <i class="fas fa-building"></i>
                                    {{ $labo->sigleLabo }}
                                    <small class="text-muted">{{ $labo->nomLabo }}</small>
                                </h4>
                                @if($labo->anneeCreation)
                                    <span class="badge badge-info">
                                        <i class="fas fa-calendar"></i> Créé en {{ $labo->anneeCreation }}
                                    </span>
                                @endif
                            </div>

                            <!-- Informations principales -->
                            <div class="col-md-4">
                                <div class="info-section">
                                    <h6 class="text-dark mb-3">
                                        <i class="fas fa-info-circle"></i> Informations générales
                                    </h6>
                                    @if($labo->descLabo)
                                        <p class="text-muted mb-3">{{ $labo->descLabo }}</p>
                                    @endif

                                    <!-- Nombre de chercheurs -->
                                    <div class="stats-info mb-3">
                                        <p class="mb-1">
                                            <i class="fas fa-users text-info"></i>
                                            <strong>Chercheurs:</strong>
                                            <span class="badge badge-info">{{ $labo->chercheurs->count() }}</span>
                                        </p>
                                    </div>

                                    <div class="location-info">
                                        @if($labo->localisationLabo)
                                            <p class="mb-1">
                                                <i class="fas fa-map-marker-alt text-danger"></i>
                                                {{ $labo->localisationLabo }}
                                            </p>
                                        @endif
                                        @if($labo->adresseLabo)
                                            <p class="mb-1">
                                                <i class="fas fa-road text-secondary"></i>
                                                {{ $labo->adresseLabo }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="col-md-4">
                                <div class="contact-section">
                                    <h6 class="text-dark mb-3">
                                        <i class="fas fa-address-card"></i> Contact
                                    </h6>
                                    <ul class="list-unstyled">
                                        @if($labo->directeur)
                                            <li class="mb-2">
                                                <i class="fas fa-user-tie text-dark"></i>
                                                <strong>Directeur:</strong>
                                                {{ $labo->directeur->nomCherch }} {{ $labo->directeur->prenomCherch }}
                                            </li>
                                        @endif
                                        @if($labo->emailLabo)
                                            <li class="mb-2">
                                                <i class="fas fa-envelope text-primary"></i>
                                                <a href="mailto:{{ $labo->emailLabo }}" class="text-primary">
                                                    {{ $labo->emailLabo }}
                                                </a>
                                            </li>
                                        @endif
                                        @if($labo->telLabo)
                                            <li class="mb-2">
                                                <i class="fas fa-phone text-success"></i>
                                                {{ $labo->telLabo }}
                                            </li>
                                        @endif
                                        @if($labo->faxLabo)
                                            <li>
                                                <i class="fas fa-fax text-info"></i>
                                                {{ $labo->faxLabo }}
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Rattachement et axes -->
                            <div class="col-md-4">
                                <div class="affiliation-section">
                                    <h6 class="text-dark mb-3">
                                        <i class="fas fa-sitemap"></i> Rattachement
                                    </h6>
                                    @if($labo->umri)
                                        <p class="mb-2">
                                            <i class="fas fa-university text-primary"></i>
                                            <strong>UMRI:</strong> {{ $labo->umri->sigleUMRI }}
                                        </p>
                                    @endif

                                    <div class="stats-info mb-3">
                                        <p class="mb-1">
                                            <i class="fas fa-compass"></i>
                                            <strong>Axes de recherche :</strong>
                                            <span class="badge badge-info">{{ $labo->axesRecherches->count() }}</span>

                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="row mt-3">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-toggle="modal" data-target="#detailsModal_{{ $labo->idLabo }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>
                                <a href="{{ route('admin.modifierLaboratoire', $labo->idLabo) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <form id="deleteLaboForm_{{ $labo->idLabo }}"
                                      action="{{ route('admin.supprimerLaboratoire', $labo->idLabo) }}"
                                      method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="confirmDelete({{ $labo->idLabo }})">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $laboratoires->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal pour enregistrer un laboratoire -->
<div class="modal fade" id="addLaboModal" tabindex="-1" role="dialog" aria-labelledby="addLaboModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLaboModalLabel">Enregistrer un Laboratoire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerLaboratoire') }}" method="POST">
                    @csrf
                    <!-- Informations principales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-building"></i> Informations principales</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sigleLabo">Sigle <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('sigleLabo') is-invalid @enderror"
                                               id="sigleLabo" name="sigleLabo" required>
                                        @error('sigleLabo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomLabo">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nomLabo') is-invalid @enderror"
                                               id="nomLabo" name="nomLabo" required>
                                        @error('nomLabo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Année et Description -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="anneeCreation">Année de création</label>
                                        <input type="number" min="1900" max="{{ date('Y') }}"
                                               class="form-control @error('anneeCreation') is-invalid @enderror"
                                               id="anneeCreation" name="anneeCreation">
                                        @error('anneeCreation')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="descLabo">Description</label>
                                        <textarea class="form-control @error('descLabo') is-invalid @enderror"
                                                  id="descLabo" name="descLabo" rows="3"></textarea>
                                        @error('descLabo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Localisation</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="localisationLabo">Localisation</label>
                                        <input type="text" class="form-control" id="localisationLabo" name="localisationLabo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="adresseLabo">Adresse complète</label>
                                        <input type="text" class="form-control" id="adresseLabo" name="adresseLabo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-address-card"></i> Contact</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telLabo">Téléphone</label>
                                        <input type="tel" class="form-control" id="telLabo" name="telLabo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="faxLabo">Fax</label>
                                        <input type="tel" class="form-control" id="faxLabo" name="faxLabo">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="emailLabo">Email</label>
                                <input type="email" class="form-control" id="emailLabo" name="emailLabo">
                            </div>
                        </div>
                    </div>

                    <!-- Rattachement -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-sitemap"></i> Rattachement</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="idUMRI">UMRI</label>
                                        <select class="form-control select2" id="idUMRI" name="idUMRI" multiple>
                                            @foreach ($umris as $umri)
                                                <option value="{{ $umri->idUMRI }}">{{ $umri->sigleUMRI }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="axesRecherche">Axes de recherche</label>
                                <select class="form-control select2" id="axesRecherche" name="axesRecherche[]" multiple>
                                    @foreach ($axesRecherche as $axe)
                                        <option value="{{ $axe->idAxeRech }}">{{ $axe->titreAxeRech }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="idDirecteurLabo">Directeur du laboratoire</label>
                                <select class="form-control select2" id="idDirecteurLabo" name="idDirecteurLabo" multiple>
                                    @foreach ($chercheurs as $chercheur)
                                        <option value="{{ $chercheur->idCherch }}">
                                            {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
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

<!-- Modal de détails -->
@foreach($laboratoires as $labo)
<div class="modal fade" id="detailsModal_{{ $labo->idLabo }}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel_{{ $labo->idLabo }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="detailsModalLabel_{{ $labo->idLabo }}">
                    <i class="fas fa-info-circle"></i> Détails du laboratoire
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informations de base -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informations générales
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <span class="font-weight-bold">Nom :</span>
                                    {{ $labo->nomLabo }}
                                </p>
                                <p class="mb-2">
                                    <span class="font-weight-bold">Sigle :</span>
                                    {{ $labo->sigleLabo }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($labo->directeur)
                                    <p class="mb-2">
                                        <span class="font-weight-bold">Directeur :</span>
                                        {{ $labo->directeur->nomCherch }} {{ $labo->directeur->prenomCherch }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Axes de recherche -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-compass"></i> Axes de recherche
                            <span class="badge badge-info">{{ $labo->axesRecherches->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($labo->axesRecherches->isEmpty())
                            <p class="text-muted mb-0">Aucun axe de recherche associé.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Titre</th>
                                            <th>Description</th>
                                            <th>Thèmes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($labo->axesRecherches as $axe)
                                            <tr>
                                                <td>{{ $axe->titreAxeRech }}</td>
                                                <td>{{ Str::limit($axe->descAxeRech, 50) }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-info">
                                                        {{ $axe->themes->count() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chercheurs -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-users"></i> Chercheurs
                            <span class="badge badge-info">{{ $labo->chercheurs->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($labo->chercheurs->isEmpty())
                            <p class="text-muted mb-0">Aucun chercheur associé.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Nom complet</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($labo->chercheurs as $chercheur)
                                            <tr>
                                                <td>
                                                    {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                                    @if($chercheur->id === $labo->idDirecteur)
                                                        <span class="badge badge-primary">Directeur</span>
                                                    @endif
                                                </td>
                                                <td>{{ $chercheur->emailCherch ?? '-' }}</td>
                                                <td>{{ $chercheur->telCherch ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@section('scripts')

<script>

    $(document).ready(function() {
        $('#axesRecherche').select2({
            placeholder: 'Sélectionnez le ou les axes de recherches',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 5,
            language: {
                noResults: function() {
                    return "Aucune base trouvée";
                },
                searching: function() {
                    return "Recherche...";
                },
            },
        });

        $('#idDirecteurLabo').select2({
            placeholder: 'Sélectionnez le directure',
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
            },
        });

        $('#idUMRI').select2({
            placeholder: 'Sélectionnez l\'umris',
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
            },
        });

        $('.select2-selection').css('min-height', '40px'); // Applique la hauteur après initialisation
    });

    function confirmDelete(laboId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce laboratoire ?",
            text: "Cette action est irréversible et ne peut être effectuée si des chercheurs sont associés.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteLaboForm_' + laboId);
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

@endsection


{{-- // $('#idUMRI').select2({
//     placeholder: 'Sélectionner UMRI',
//     allowClear: true,
//     ajax: {
//         url: '/api/umris',  // Une route qui retourne les UMRI en fonction de la recherche
//         dataType: 'json',
//         delay: 250,  // délai de recherche
//         processResults: function(data) {
//             return {
//                 results: data.map(function(item) {
//                     return {
//                         id: item.idUMRI,
//                         text: item.nomUMRI
//                     };
//                 })
//             };
//         }
//     }
// }); --}}




