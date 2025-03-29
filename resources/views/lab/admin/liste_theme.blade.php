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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherTheme') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un thème" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<!-- En-tête avec filtres et bouton d'ajout -->
<div class="d-flex justify-content-between align-items-center mb-2 mt-5" style="max-width: 90% ">
    <!-- Filtres -->
    <div class="btn-group ml-5">
        <a href="{{ route('admin.listeTheme') }}"
           class="btn btn-outline-dark {{ !request()->has('filter') ? 'active' : '' }}">
            <i class="fas fa-list"></i> Tous
        </a>
        <a href="{{ route('admin.listeTheme', ['filter' => 'attributed']) }}"
           class="btn btn-outline-success {{ request()->get('filter') == 'attributed' ? 'active' : '' }}">
            <i class="fas fa-check-circle"></i> Attribués
        </a>
        <a href="{{ route('admin.listeTheme', ['filter' => 'not-attributed']) }}"
           class="btn btn-outline-secondary {{ request()->get('filter') == 'not-attributed' ? 'active' : '' }}">
            <i class="fas fa-clock"></i> Non attribués
        </a>
    </div>

    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addThemeModal">
        <i class="fas fa-plus-circle"></i> Nouveau thème
    </button>
</div>

<div class="p-5">
    @if ($themes->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun thème disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun thème" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($themes as $theme)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <h6 class="card-title mb-0">{{ $theme->intituleTheme }}</h6>
                        </div>
                        <div class="card-body">
                            <!-- Description -->
                            @if($theme->descTheme)
                                <p class="card-text">{{ Str::limit($theme->descTheme, 150) }}</p>
                            @else
                                <p class="card-text text-muted">Aucune description disponible</p>
                            @endif

                            <!-- Axe de recherche -->
                            <div class="mt-3">
                                <p class="mb-1">
                                    <i class="fas fa-microscope"></i>
                                    <span class="font-weight-bold text-dark">Axe de recherche :</span>
                                    @if($theme->axeRecherche)
                                        {{ $theme->axeRecherche->titreAxeRech }}
                                    @else
                                        <span class="text-muted">Non défini</span>
                                    @endif
                                </p>
                            </div>

                            <!-- État d'attribution -->
                            <div class="mt-2">
                                <p class="mb-1">
                                    <i class="fas fa-user-graduate"></i>
                                    <span class="font-weight-bold text-dark">État :</span>
                                    @if($theme->etatAttribution)
                                        <span class="badge badge-success">Attribué</span>
                                        <span class="badge badge-info">{{ $theme->doctorants->count() }} doctorant(s)</span>
                                    @else
                                        <span class="badge badge-secondary">Non attribué</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#detailsModal_{{ $theme->idTheme }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>

                                <div class="d-flex">
                                    <a href="{{ route('admin.modifierTheme', $theme->idTheme) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>

                                    <form id="deleteThemeForm" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDelete({{ $theme->idTheme }})">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Détails -->
                <div class="modal fade" id="detailsModal_{{ $theme->idTheme }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-info-circle"></i> Détails du thème
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Informations principales -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-info mb-3">
                                        <i class="fas fa-file-alt"></i> Informations générales
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Intitulé :</strong> {{ $theme->intituleTheme }}
                                    </p>

                                    @if($theme->descTheme)
                                        <p class="mb-2">
                                            <strong>Description :</strong>
                                            {{ $theme->descTheme }}
                                        </p>
                                    @endif

                                </div>

                                <!-- Axe de recherche -->
                                <div class="mb-4">
                                    @if($theme->axeRecherche)

                                        <h6 class="font-weight-bold text-info mb-3">
                                            <i class="fas fa-microscope"></i> Axe de recherche
                                        </h6>

                                        <p class="mb-2">
                                            <strong>Titre :</strong> {{ $theme->axeRecherche->titreAxeRech }}
                                        </p>
                                        <p class="mb-2">
                                            <strong>Description :</strong>
                                            @if($theme->axeRecherche->descAxeRech)
                                                {{ $theme->axeRecherche->descAxeRech }}
                                            @endif
                                        </p>

                                    @endif
                                </div>

                                <!-- Doctorants -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-info mb-3">
                                        <i class="fas fa-user-graduate"></i> Doctorants
                                        <span class="badge badge-info">{{ $theme->doctorants->count() }}</span>
                                    </h6>
                                    @if($theme->doctorants->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Prénom</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($theme->doctorants as $doctorant)
                                                        <tr>
                                                            <td>{{ $doctorant->nomDoc }}</td>
                                                            <td>{{ $doctorant->prenomDoc }}</td>
                                                            <td>
                                                                @if($doctorant->emailDoc)
                                                                    <a href="mailto:{{ $doctorant->emailDoc}}">
                                                                        {{ $doctorant->emailDoc }}
                                                                    </a>
                                                                @endif
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Aucun doctorant associé à ce thème</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $themes->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal pour ajouter un thème -->
<div class="modal fade" id="addThemeModal" tabindex="-1" role="dialog" aria-labelledby="addThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addThemeModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouveau thème
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerTheme') }}" method="POST">
                    @csrf
                    <!-- Informations de base -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations générales</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="titreTheme" class="font-weight-bold">
                                    Intitulé du thème <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('titreTheme') is-invalid @enderror"
                                       id="titreTheme" name="titreTheme" required>
                                @error('titreTheme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="descTheme" class="font-weight-bold">Description</label>
                                <textarea class="form-control @error('descTheme') is-invalid @enderror"
                                          id="descTheme" name="descTheme" rows="4"></textarea>
                                @error('descTheme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Rattachement -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-compass"></i> Rattachement</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="idAxeRech" class="font-weight-bold">
                                    Axe de recherche <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2 @error('idAxeRech') is-invalid @enderror"
                                        id="idAxeRech" name="idAxeRech" multiple required>
                                    {{-- <option value="">Sélectionner un axe de recherche</option> --}}
                                    @foreach($axesRecherches as $axe)
                                        <option value="{{ $axe->idAxeRech }}">
                                            {{ $axe->titreAxeRech }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idAxeRech')
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

<!-- Script pour la confirmation de suppression -->
<script>
    function confirmDelete(themeId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce thème ?",
            text: "Cette action est irréversible et ne peut être effectuée si des doctorants sont associés.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteThemeForm');
                form.action = '/admin/supprimerTheme/' + themeId;
                form.submit();
            }
        });
    }
</script>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#idAxeRech').select2({
            placeholder: 'Sélectionner un axe de recherche',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 1,
        });

        $('.select2-selection').css('height', '40px'); // Applique la hauteur après initialisation

    });
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
