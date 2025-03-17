@extends("baseAdmin")

@section('bg-content', 'bg-white')

@section('content')

<div class="container-fluid">
  

    <div class="mb-4">
        @include("lab.partials.alerts")
    </div>

    <!-- Formulaire de recherche -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.rechercherAxeRecherch') }}" method="GET" class="mb-0">
                <div class="input-group">
                    <input type="text" class="form-control" name="query" 
                           placeholder="Rechercher un axe de recherche..." 
                           value="{{ request()->query('query') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-4">
        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addAxeModal">
            <i class="fas fa-plus"></i> Ajouter
        </button>
    </div>

    <!-- Liste des axes de recherche -->
    @if ($axeRecherches->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun axe de recherche disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun axe" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row">
            @foreach ($axeRecherches as $axe)
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0">{{ $axe->titreAxeRech }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Description -->
                            @if($axe->descAxeRech)
                                <p class="card-text">{{ $axe->descAxeRech }}</p>
                            @endif

                            <!-- Statistiques -->
                            <div class="mt-3">
                                <p class="mb-1">
                                    <i class="fas fa-bookmark text-secondary"></i>
                                    <span class="font-weight-bold">Thèmes associés :</span>
                                    <span class="badge badge-info">{{ $axe->themes->count() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-toggle="modal" data-target="#detailsModal_{{ $axe->idAxeRech }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>
                                <div class="d-flex">
                                    <a href="{{ route('admin.modifierAxeRecherche', $axe->idAxeRech) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form id="deleteAxeForm" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDelete({{ $axe->idAxeRech }})">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $axeRecherches->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif

    <!-- Modal d'ajout -->
    <div class="modal fade" id="addAxeModal" tabindex="-1" role="dialog" aria-labelledby="addAxeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.enregistrerAxeRecherche') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="addAxeModalLabel">
                            <i class="fas fa-plus-circle"></i> Ajouter un axe de recherche
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Informations de l'axe -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle"></i> Informations de l'axe
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="titreAxeRech" class="font-weight-bold">
                                                Titre <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('titreAxeRech') is-invalid @enderror"
                                                   id="titreAxeRech" name="titreAxeRech" 
                                                   value="{{ old('titreAxeRech') }}" required>
                                            @error('titreAxeRech')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label for="descAxeRech" class="font-weight-bold">Description</label>
                                            <textarea class="form-control @error('descAxeRech') is-invalid @enderror"
                                                      id="descAxeRech" name="descAxeRech" rows="4"
                                                      style="resize: none;">{{ old('descAxeRech') }}</textarea>
                                            @error('descAxeRech')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de détails -->
    @foreach($axeRecherches as $axe)
    <div class="modal fade" id="detailsModal_{{ $axe->idAxeRech }}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel_{{ $axe->idAxeRech }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="detailsModalLabel_{{ $axe->idAxeRech }}">
                        <i class="fas fa-info-circle"></i> Détails de l'axe de recherche
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
                                <div class="col-md-12">
                                    <p class="mb-2">
                                        <span class="font-weight-bold">Titre :</span>
                                        {{ $axe->titreAxeRech }}
                                    </p>
                                    @if($axe->descAxeRech)
                                        <p class="mb-0">
                                            <span class="font-weight-bold">Description :</span>
                                            {{ $axe->descAxeRech }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thèmes associés -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-bookmark"></i> Thèmes associés 
                                <span class="badge badge-info">{{ $axe->themes->count() }}</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($axe->themes->isEmpty())
                                <p class="text-muted mb-0">Aucun thème associé à cet axe.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Titre</th>
                                                <th>Description</th>
                                                <th>État</th>
                                                <th>Doctorants</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($axe->themes as $theme)
                                                <tr>
                                                    <td>{{ $theme->intituleTheme }}</td>
                                                    <td>
                                                        {{ Str::limit($theme->descTheme, 50) }}
                                                    </td>
                                                    <td>
                                                        @if($theme->etatAttribution)
                                                            <span class="badge badge-success">Attribué</span>
                                                        @else
                                                            <span class="badge badge-secondary">Non attribué</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-info">
                                                            {{ $theme->doctorants->count() }}
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
</div>

<!-- Script pour la confirmation de suppression -->
<script>
    function confirmDelete(axeId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cet axe ?",
            text: "Cette action est irréversible et ne peut être effectuée si des thèmes sont associés.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteAxeForm');
                form.action = '/admin/supprimer-axeRecherche/' + axeId;
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
