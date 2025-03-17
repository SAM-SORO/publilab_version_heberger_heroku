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

<!-- En-tête avec bouton d'ajout -->
<div class="d-flex justify-content-end align-items-center mb-2 mt-5" style="max-width: 90%">
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDoctorantModal">
        <i class="fas fa-plus-circle"></i> Nouveau doctorant
    </button>
</div>

<div class="p-5">
    @if ($doctorants->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun doctorant disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun doctorant" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($doctorants as $doctorant)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <h6 class="card-title mb-0">
                                {{ $doctorant->nomDoc }} {{ $doctorant->prenomDoc }}
                                @if($doctorant->genreDoc)
                                    <span class="badge {{ $doctorant->genreDoc == 'M' ? 'badge-info' : 'badge-danger' }}">
                                        {{ $doctorant->genreDoc == 'M' ? 'M' : 'F' }}
                                    </span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Informations de contact -->
                            <div class="mb-3">
                                @if($doctorant->matriculeDoc)
                                    <p class="mb-1">
                                        <i class="fas fa-id-card text-secondary"></i>
                                        <span class="font-weight-bold">Matricule :</span>
                                        {{ $doctorant->matriculeDoc }}
                                    </p>
                                @endif

                                @if($doctorant->emailDoc)
                                    <p class="mb-1">
                                        <i class="fas fa-envelope text-secondary"></i>
                                        <span class="font-weight-bold">Email :</span>
                                        <a href="mailto:{{ $doctorant->emailDoc }}">{{ $doctorant->emailDoc }}</a>
                                    </p>
                                @endif

                                @if($doctorant->telDoc)
                                    <p class="mb-1">
                                        <i class="fas fa-phone text-secondary"></i>
                                        <span class="font-weight-bold">Téléphone :</span>
                                        {{ $doctorant->telDoc }}
                                    </p>
                                @endif
                            </div>

                            <!-- Thème de recherche -->
                            @if($doctorant->theme)
                                <div class="mb-3">  
                                    <p class="mb-1">
                                        <i class="fas fa-bookmark text-secondary"></i>
                                        <span class="font-weight-bold">Thème :</span>
                                        {{ $doctorant->theme->intituleTheme }}
                                        
                                    </p>
                                </div>
                            @endif
                                
                            

                            <!-- Statistiques -->
                            <div class="mb-3">
                                <p class="mb-1">
                                    <i class="fas fa-file-alt text-secondary"></i>
                                    <span class="font-weight-bold">Articles :</span>
                                    <span class="badge badge-info">{{ $doctorant->articles->count() }}</span>
                                </p>

                                <p class="mb-1">
                                    <i class="fas fa-user-tie text-secondary"></i>
                                    <span class="font-weight-bold">Encadrants :</span>
                                    <span class="badge badge-info">{{ $doctorant->encadrants->count() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#detailsModal_{{ $doctorant->idDoc }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>
                                <div class="d-flex">
                                    <a href="{{ route('admin.modifierDoctorant', $doctorant->idDoc) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form id="deleteDoctorantForm" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDelete({{ $doctorant->idDoc }})">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Détails -->
                <div class="modal fade" id="detailsModal_{{ $doctorant->idDoc }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-graduate"></i> Détails du doctorant
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Informations personnelles -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-info mb-3">
                                        <i class="fas fa-user"></i> Informations personnelles
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Nom complet :</strong>
                                        {{ $doctorant->nomDoc }} {{ $doctorant->prenomDoc }}
                                        @if($doctorant->genreDoc)
                                            <span class="badge {{ $doctorant->genreDoc == 'M' ? 'badge-info' : 'badge-danger' }}">
                                                {{ $doctorant->genreDoc == 'M' ? 'Homme' : 'Femme' }}
                                            </span>
                                        @endif
                                    </p>

                                    @if($doctorant->matriculeDoc)
                                        <p class="mb-2">
                                            <strong>Matricule :</strong> {{ $doctorant->matriculeDoc }}
                                        </p>
                                    @endif

                                    @if($doctorant->emailDoc)
                                        <p class="mb-2">
                                            <strong>Email :</strong>
                                            <a href="mailto:{{ $doctorant->emailDoc }}">{{ $doctorant->emailDoc }}</a>
                                        </p>
                                    @endif

                                    @if($doctorant->telDoc)
                                        <p class="mb-2">
                                            <strong>Téléphone :</strong> {{ $doctorant->telDoc }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Thème de recherche -->
                                @if($doctorant->theme)
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-bookmark"></i> Thème de recherche
                                        </h6>
                                        <p class="mb-2">
                                            <strong>Intitulé :</strong> {{ $doctorant->theme->intituleTheme }}
                                        </p>

                                        @if($doctorant->theme->descTheme)
                                            <p class="mb-2">
                                                <strong>Description :</strong> {{ $doctorant->theme->descTheme }}
                                            </p>
                                        @endif

                                        @if($doctorant->theme->axeRecherche)
                                            <p class="mb-2">
                                                <strong>Axe de recherche :</strong>
                                                {{ $doctorant->theme->axeRecherche->titreAxeRech }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <!-- Encadrants -->
                                @if($doctorant->encadrants->isNotEmpty())
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-user-tie"></i> Encadrants
                                            <span class="badge badge-info">{{ $doctorant->encadrants->count() }}</span>
                                        </h6>
                                        <div class="row">
                                            @foreach($doctorant->encadrants as $encadrant)
                                                <div class="col-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-body py-2">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-user-tie text-secondary"></i>
                                                                {{ $encadrant->nomCherch }} {{ $encadrant->prenomCherch }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Articles -->
                                @if($doctorant->articles->isNotEmpty())
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-file-alt"></i> Articles
                                            <span class="badge badge-info">{{ $doctorant->articles->count() }}</span>
                                        </h6>
                                        <div class="row">
                                            @foreach($doctorant->articles as $article)
                                                <div class="col-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-body py-2">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-file-alt text-secondary"></i>
                                                                {{ $article->titreArticle }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            </div>
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

<!-- Modal d'ajout de doctorant -->
<div class="modal fade" id="addDoctorantModal" tabindex="-1" role="dialog" aria-labelledby="addDoctorantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addDoctorantModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouveau doctorant
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerDoctorant') }}" method="POST">
                    @csrf

                    <!-- Informations personnelles -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-user"></i> Informations personnelles</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomDoc" class="font-weight-bold">
                                            Nom <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('nomDoc') is-invalid @enderror"
                                               id="nomDoc" name="nomDoc" required>
                                        @error('nomDoc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenomDoc" class="font-weight-bold">Prénom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('prenomDoc') is-invalid @enderror"
                                               id="prenomDoc" name="prenomDoc" value="{{ old('prenomDoc') }}" required>
                                        @error('prenomDoc')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="genreDoc" class="font-weight-bold">Genre</label>
                                        <select class="form-control @error('genreDoc') is-invalid @enderror"
                                                id="genreDoc" name="genreDoc">
                                            <option value="">Sélectionner</option>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                        @error('genreDoc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="matriculeDoc" class="font-weight-bold">Matricule <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('matriculeDoc') is-invalid @enderror"
                                               id="matriculeDoc" name="matriculeDoc" value="{{ old('matriculeDoc') }}" required>
                                        @error('matriculeDoc')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailDoc" class="font-weight-bold">Email</label>
                                        <input type="email" class="form-control @error('emailDoc') is-invalid @enderror"
                                               id="emailDoc" name="emailDoc">
                                        @error('emailDoc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telDoc" class="font-weight-bold">Téléphone</label>
                                        <input type="text" class="form-control @error('telDoc') is-invalid @enderror"
                                               id="telDoc" name="telDoc">
                                        @error('telDoc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="font-weight-bold">
                                    Mot de passe <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="font-weight-bold">
                                    Confirmer le mot de passe <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Thème et encadrants -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-bookmark"></i> Thème et encadrants</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="idTheme" class="font-weight-bold">Thème de recherche</label>
                                <select class="form-control select2 @error('idTheme') is-invalid @enderror"
                                        id="idTheme" name="idTheme" multiple>
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme->idTheme }}">
                                            {{ $theme->intituleTheme }}
                                            @if($theme->etatAttribution == "false")
                                                (Déjà attribué)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('idTheme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="encadrants" class="font-weight-bold">Encadrants</label>
                                <select class="form-control select2 @error('encadrants') is-invalid @enderror"
                                        id="encadrants" name="encadrants[]" multiple>
                                    @foreach($chercheurs as $chercheur)
                                        <option value="{{ $chercheur->idCherch }}">
                                            {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                            @if(isset($chercheur->grade))
                                                ({{ $chercheur->grade->sigleGrade }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('encadrants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">

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
            placeholder: 'Sélectionner...'
        });

        // Initialisation de Select2 pour tous les sélecteurs
        $('#idTheme').select2({
            width: '100%',
            placeholder: 'Sélectionner...',
            allowClear: true,
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

        // Initialisation de Select2 pour tous les sélecteurs
        $('#encadrants').select2({
            width: '100%',
            placeholder: 'Sélectionner...',
            allowClear: true,
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

    function confirmDelete(doctorantId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce doctorant ?",
            text: "Cette action est irréversible.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteDoctorantForm');
                form.action = '/admin/supprimerDoctorant/' + doctorantId;
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
