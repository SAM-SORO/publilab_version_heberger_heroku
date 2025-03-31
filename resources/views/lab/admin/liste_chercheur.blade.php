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

<!-- En-tête avec bouton d'ajout -->
<div class="d-flex justify-content-end align-items-center mb-2 mt-5" style="max-width: 90%">
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addChercheurModal">
        <i class="fas fa-plus-circle"></i> Nouveau chercheur
    </button>
</div>

<div class="p-5">
    @if ($chercheurs->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun chercheur disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun chercheur" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($chercheurs as $chercheur)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <h6 class="card-title mb-0">
                                {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                @if($chercheur->genreCherch)
                                    <span class="badge {{ $chercheur->genreCherch == 'M' ? 'badge-info' : 'badge-danger' }}">
                                        {{ $chercheur->genreCherch == 'M' ? 'M' : 'F' }}
                                    </span>
                                @endif
                                @if($chercheur->grades->isNotEmpty())
                                    <span class="badge badge-light">
                                        {{ $chercheur->grades->first()->sigleGrade }}
                                    </span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Informations de contact -->
                            <div class="mb-3">
                                @if($chercheur->matriculeCherch)
                                    <p class="mb-1">
                                        <i class="fas fa-id-card text-secondary"></i>
                                        <span class="font-weight-bold">Matricule :</span>
                                        {{ $chercheur->matriculeCherch }}
                                    </p>
                                @endif

                                @if($chercheur->emailCherch)
                                    <p class="mb-1">
                                        <i class="fas fa-envelope text-secondary"></i>
                                        <span class="font-weight-bold">Email :</span>
                                        <a href="mailto:{{ $chercheur->emailCherch }}">{{ $chercheur->emailCherch }}</a>
                                    </p>
                                @endif

                                @if($chercheur->telCherch)
                                    <p class="mb-1">
                                        <i class="fas fa-phone text-secondary"></i>
                                        <span class="font-weight-bold">Téléphone :</span>
                                        {{ $chercheur->telCherch }}
                                    </p>
                                @endif
                            </div>

                            <!-- Informations professionnelles -->
                            <div class="mb-3">
                                @if($chercheur->emploiCherch)
                                    <p class="mb-1">
                                        <i class="fas fa-briefcase text-secondary"></i>
                                        <span class="font-weight-bold">Emploi :</span>
                                        {{ $chercheur->emploiCherch }}
                                    </p>
                                @endif

                                @if($chercheur->departementCherch)
                                    <p class="mb-1">
                                        <i class="fas fa-building text-secondary"></i>
                                        <span class="font-weight-bold">Département :</span>
                                        {{ $chercheur->departementCherch }}
                                    </p>
                                @endif

                                @if($chercheur->specialiteCherch)
                                    <p class="mb-1">
                                        <i class="fas fa-microscope text-secondary"></i>
                                        <span class="font-weight-bold">Spécialité :</span>
                                        {{ $chercheur->specialiteCherch }}
                                    </p>
                                @endif
                            </div>

                            <!-- UMRI -->
                            @if($chercheur->umri)
                                <div class="mb-3">
                                    <p class="mb-1">
                                        <i class="fas fa-university text-secondary"></i>
                                        <span class="font-weight-bold">UMRI :</span>
                                        {{ $chercheur->umri->sigleUMRI }}
                                    </p>
                                </div>
                            @endif

                            <!-- Statistiques -->
                            <div class="mb-3">
                                <p class="mb-1">
                                    <i class="fas fa-file-alt text-secondary"></i>
                                    <span class="font-weight-bold">Articles :</span>
                                    <span class="badge badge-info">{{ $chercheur->articles->count() }}</span>
                                </p>

                                <p class="mb-1">
                                    <i class="fas fa-user-graduate text-secondary"></i>
                                    <span class="font-weight-bold">Doctorants encadrés :</span>
                                    <span class="badge badge-info">{{ $chercheur->doctorantsEncadres->count() }}</span>
                                </p>

                                @if($chercheur->laboratoire)
                                    <!-- Modifier cette partie pour le laboratoire unique -->
                                    <p class="mb-1">
                                        <i class="fas fa-flask text-secondary"></i>
                                        <span class="font-weight-bold">Laboratoire :</span>
                                        <span class="badge badge-success">{{ $chercheur->laboratoire->sigleLabo }}</span>
                                        @if($chercheur->dateAffectationLabo)
                                            <small class="text-muted">
                                                (Depuis le {{ $chercheur->dateAffectationLabo->format('d/m/Y') }})
                                            </small>
                                        @endif
                                    </p>
                                @endif

                            </div>

                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">

                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#detailsModal_{{ $chercheur->idCherch }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>

                                <div class="d-flex">
                                    <a href="{{ route('admin.modifierLaboChercheur', $chercheur->idCherch) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>

                                    <form id="deleteChercheurForm" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $chercheur->idCherch }})">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Détails -->
                <div class="modal fade" id="detailsModal_{{ $chercheur->idCherch }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-tie"></i>
                                    {{ $chercheur->nomCherch }} {{ $chercheur->prenomCherch }}
                                    @if($chercheur->grades->isNotEmpty())
                                        <span class="badge badge-light">{{ $chercheur->grades->first()->sigleGrade }}</span>
                                    @endif
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Nom :</strong> {{ $chercheur->nomCherch }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Prénom :</strong> {{ $chercheur->prenomCherch }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Genre :</strong>
                                                @if($chercheur->genreCherch)
                                                    {{ $chercheur->genreCherch == 'M' ? 'Masculin' : 'Féminin' }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Matricule :</strong> {{ $chercheur->matriculeCherch }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Date de naissance :</strong>
                                                @if($chercheur->dateNaissCherch)
                                                    {{ Carbon::parse($chercheur->dateNaissCherch)->format('d/m/Y') }}
                                                @endif
                                            </p>
                                            <p class="mb-2">
                                                <strong>Date d'arrivée :</strong>
                                                @if($chercheur->dateArriveeCherch)
                                                    {{ Carbon::parse($chercheur->dateArriveeCherch)->format('d/m/Y') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations professionnelles -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-info mb-3">
                                        <i class="fas fa-briefcase"></i> Informations professionnelles
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Emploi :</strong> {{ $chercheur->emploiCherch }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Département :</strong> {{ $chercheur->departementCherch }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Spécialité :</strong> {{ $chercheur->specialiteCherch }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Fonction administrative :</strong> {{ $chercheur->fonctionAdministrativeCherch }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>UMRI :</strong>
                                                @if($chercheur->umri)
                                                    {{ $chercheur->umri->nomUMRI }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-info mb-3">
                                        <i class="fas fa-address-card"></i> Contact
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Email :</strong>
                                        <a href="mailto:{{ $chercheur->emailCherch }}">{{ $chercheur->emailCherch }}</a>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Téléphone :</strong> {{ $chercheur->telCherch }}
                                    </p>
                                </div>

                                <!-- Grades -->
                                @if($chercheur->grades->isNotEmpty())
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-graduation-cap"></i> Grades
                                            <span class="badge badge-info">{{ $chercheur->grades->count() }}</span>
                                        </h6>
                                        <div class="row">
                                            @foreach($chercheur->grades as $grade)
                                                <div class="col-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-body py-2">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-graduation-cap text-secondary"></i>
                                                                {{ $grade->nomGrade }} ({{ $grade->sigleGrade }})
                                                                @if($grade->pivot->dateGrade)
                                                                    <small class="text-muted">
                                                                        depuis {{ Carbon::parse($grade->pivot->dateGrade)->format('d/m/Y') }}
                                                                    </small>
                                                                @endif
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Laboratoire -->
                                @if($chercheur->laboratoire)
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-flask"></i> Laboratoire
                                        </h6>
                                        <div class="card">
                                            <div class="card-body py-2">
                                                <h6 class="card-title mb-0">
                                                    <i class="fas fa-flask text-secondary"></i>
                                                    {{ $chercheur->laboratoire->nomLabo }}
                                                    <span class="badge badge-info">{{ $chercheur->laboratoire->sigleLabo }}</span>
                                                    @if($chercheur->dateAffectationLabo)
                                                        <small class="text-muted">
                                                            (Depuis le {{ $chercheur->dateAffectationLabo->format('d/m/Y') }})
                                                        </small>
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-flask"></i> Laboratoire
                                        </h6>
                                        <p class="text-muted mb-0">Aucun laboratoire assigné</p>
                                    </div>
                                @endif

                                <!-- Doctorants encadrés -->
                                @if($chercheur->doctorantsEncadres->isNotEmpty())
                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-user-graduate"></i> Doctorants encadrés
                                            <span class="badge badge-info">{{ $chercheur->doctorantsEncadres->count() }}</span>
                                        </h6>
                                        <div class="row">
                                            @foreach($chercheur->doctorantsEncadres as $doctorant)
                                                <div class="col-12 mb-2">
                                                    <div class="card">
                                                        <div class="card-body py-2">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-user-graduate text-secondary"></i>
                                                                {{ $doctorant->nomDoc }} {{ $doctorant->prenomDoc }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Laboratoire et date d'affectation -->
                                @if($chercheur->laboratoire)
                                    <div class="mb-3">
                                        <strong>Laboratoire :</strong>
                                        {{ $chercheur->laboratoire->sigleLabo }}
                                        @if($chercheur->dateAffectationLabo)
                                            <small class="text-muted">(Depuis le {{ $chercheur->dateAffectationLabo->format('d/m/Y') }})</small>
                                        @endif
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
            {{ $chercheurs->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal d'ajout de chercheur -->
<div class="modal fade" id="addChercheurModal" tabindex="-1" role="dialog" aria-labelledby="addChercheurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addChercheurModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouveau chercheur
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerChercheur') }}" method="POST">
                    @csrf

                    <!-- Informations personnelles -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-user"></i> Informations personnelles</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Nom -->
                                <div class="col-md-6 form-group">
                                    <label for="nomCherch" class="font-weight-bold">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomCherch') is-invalid @enderror"
                                           id="nomCherch" name="nomCherch" value="{{ old('nomCherch') }}" required>
                                    @error('nomCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Prénom -->
                                <div class="col-md-6 form-group">
                                    <label for="prenomCherch" class="font-weight-bold">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('prenomCherch') is-invalid @enderror"
                                           id="prenomCherch" name="prenomCherch" value="{{ old('prenomCherch') }}" required>
                                    @error('prenomCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Genre -->
                                <div class="col-md-6 form-group">
                                    <label for="genreCherch" class="font-weight-bold">Genre</label>
                                    <select class="form-control @error('genreCherch') is-invalid @enderror"
                                            id="genreCherch" name="genreCherch">
                                        <option value="">Sélectionner...</option>
                                        <option value="M" {{ old('genreCherch') == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('genreCherch') == 'F' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                    @error('genreCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Matricule -->
                                <div class="col-md-6 form-group">
                                    <label for="matriculeCherch" class="font-weight-bold">Matricule <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('matriculeCherch') is-invalid @enderror"
                                           id="matriculeCherch" name="matriculeCherch" value="{{ old('matriculeCherch') }}">
                                    @error('matriculeCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Date de naissance -->
                                <div class="col-md-6 form-group">
                                    <label for="dateNaissCherch" class="font-weight-bold">Date de naissance</label>
                                    <input type="date" class="form-control @error('dateNaissCherch') is-invalid @enderror"
                                           id="dateNaissCherch" name="dateNaissCherch" value="{{ old('dateNaissCherch') }}">
                                    @error('dateNaissCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Date d'arrivée -->
                                <div class="col-md-6 form-group">
                                    <label for="dateArriveeCherch" class="font-weight-bold">Date d'arrivée</label>
                                    <input type="date" class="form-control @error('dateArriveeCherch') is-invalid @enderror"
                                           id="dateArriveeCherch" name="dateArriveeCherch" value="{{ old('dateArriveeCherch') }}">
                                    @error('dateArriveeCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">

                                <!-- Email -->
                                <div class="col-md-6 form-group">
                                    <label for="emailCherch" class="font-weight-bold">Email</label>
                                    <input type="email" class="form-control @error('emailCherch') is-invalid @enderror"
                                        id="emailCherch" name="emailCherch" value="{{ old('emailCherch') }}">
                                    @error('emailCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Téléphone -->
                                <div class="col-md-6 form-group">
                                    <label for="telCherch" class="font-weight-bold">Téléphone</label>
                                    <input type="tel" class="form-control @error('telCherch') is-invalid @enderror"
                                        id="telCherch" name="telCherch" value="{{ old('telCherch') }}">
                                    @error('telCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Après la section Informations personnelles et avant Informations professionnelles -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-lock"></i> Sécurité</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Mot de passe -->
                                <div class="col-md-6 form-group">
                                    <label for="password" class="font-weight-bold">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="col-md-6 form-group">
                                    <label for="password_confirmation" class="font-weight-bold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations professionnelles -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-briefcase"></i> Informations professionnelles</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Emploi -->
                                <div class="col-md-6 form-group">
                                    <label for="emploiCherch" class="font-weight-bold">Emploi</label>
                                    <input type="text" class="form-control @error('emploiCherch') is-invalid @enderror"
                                           id="emploiCherch" name="emploiCherch" value="{{ old('emploiCherch') }}">
                                    @error('emploiCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Département -->
                                <div class="col-md-6 form-group">
                                    <label for="departementCherch" class="font-weight-bold">Département</label>
                                    <input type="text" class="form-control @error('departementCherch') is-invalid @enderror"
                                           id="departementCherch" name="departementCherch" value="{{ old('departementCherch') }}">
                                    @error('departementCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ajouter aussi les champs manquants dans la section Informations professionnelles -->
                            <div class="row">
                                <!-- Fonction administrative -->
                                <div class="col-md-6 form-group">
                                    <label for="fonctionAdministrativeCherch" class="font-weight-bold">Fonction administrative</label>
                                    <input type="text" class="form-control @error('fonctionAdministrativeCherch') is-invalid @enderror"
                                           id="fonctionAdministrativeCherch" name="fonctionAdministrativeCherch"
                                           value="{{ old('fonctionAdministrativeCherch') }}">
                                    @error('fonctionAdministrativeCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Spécialité -->
                                <div class="col-md-6 form-group">
                                    <label for="specialiteCherch" class="font-weight-bold">Spécialité</label>
                                    <input type="text" class="form-control @error('specialiteCherch') is-invalid @enderror"
                                           id="specialiteCherch" name="specialiteCherch"
                                           value="{{ old('specialiteCherch') }}">
                                    @error('specialiteCherch')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Laboratoire -->
                            <div class="form-group">
                                <label for="idLabo" class="font-weight-bold">Laboratoire</label>
                                <select class="form-control select2" id="idLabo" name="idLabo" multiple>
                                    {{-- <option value="">Sélectionner un laboratoire</option> --}}
                                    @foreach($laboratoires as $labo)
                                        <option value="{{ $labo->idLabo }}"
                                            {{ old('idLabo', $chercheur->idLabo ?? '') == $labo->idLabo ? 'selected' : '' }}>
                                            {{ $labo->sigleLabo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date d'affectation au laboratoire -->
                            <div class="form-group">
                                <label for="dateAffectationLabo" class="font-weight-bold">Date d'affectation au laboratoire</label>
                                <input type="date" class="form-control" id="dateAffectationLabo"
                                    name="dateAffectationLabo"
                                    value="{{ old('dateAffectationLabo', $chercheur->dateAffectationLabo ?? '') }}">
                            </div>

                        </div>
                    </div>

                    <!-- Affiliations -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-building"></i> Affiliations</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- UMRI -->
                                <div class="col-md-6 form-group">
                                    <label for="idUMRI" class="font-weight-bold">UMRI</label>
                                    <select class="form-control select2 @error('idUMRI') is-invalid @enderror"
                                            id="idUMRI" name="idUMRI" multiple>
                                        <option value="">Sélectionner...</option>
                                        @foreach($umris as $umri)
                                            <option value="{{ $umri->idUMRI }}"
                                                {{ old('idUMRI') == $umri->idUMRI ? 'selected' : '' }}>
                                                {{ $umri->sigleUMRI }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idUMRI')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Laboratoires -->
                                <div class="col-md-6 form-group">
                                    <label for="laboratoires" class="font-weight-bold">Laboratoires</label>
                                    <select class="form-control select2 @error('laboratoires') is-invalid @enderror"
                                            id="laboratoires" name="laboratoires[]" multiple>
                                        @foreach($laboratoires as $labo)
                                            <option value="{{ $labo->idLabo }}"
                                                {{ (old('laboratoires') && in_array($labo->idLabo, old('laboratoires'))) ? 'selected' : '' }}>
                                                {{ $labo->sigleLabo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('laboratoires')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grades -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="fas fa-graduation-cap"></i> Grades</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="grades" class="font-weight-bold">Sélectionner les grades</label>
                                <select class="form-control select2 @error('grades') is-invalid @enderror"
                                        id="grades" name="grades[]" multiple>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->idGrade }}"
                                            {{ (old('grades') && in_array($grade->idGrade, old('grades'))) ? 'selected' : '' }}>
                                            ({{ $grade->sigleGrade }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('grades')
                                    <span class="invalid-feedback">{{ $message }}</span>
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
        // Initialisation de Select2 pour tous les sélecteurs
        $('#idUMRI').select2({
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

        $('#grades').select2({
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

        $('#laboratoires').select2({
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

        // Script pour la confirmation de suppression
        function confirmDelete(chercheurId) {
            Swal.fire({
                title: "Êtes-vous sûr de vouloir supprimer ce chercheur ?",
                text: "Cette action est irréversible.",
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

        // Rendre la fonction disponible globalement
        window.confirmDelete = confirmDelete;
    });
</script>
<script>
    $(document).ready(function() {
        $('#idLabo').select2({
            placeholder: 'Sélectionner un laboratoire',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
            language: {
                noResults: function() {
                    return "Aucun laboratoire trouvé";
                },
                searching: function() {
                    return "Recherche...";
                },
                maximumSelectionLength: function() {
                    return "Vous ne pouvez sélectionner qu'un seul laboratoire";
                }
            }
        });

        $('.select2-selection').css('min-height', '40px');
    });
</script>
@endsection

@section('styles')
<style>
    .modal-dialog {
        max-width: 800px;
        margin: 1.75rem auto;
    }

    .modal-content {
        border-radius: 0.3rem;
        border: none;
    }

    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 3.5rem);
    }

    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }

    .modal-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .modal-footer {
        border-top: 1px solid rgba(0,0,0,.125);
    }
</style>
@endsection

