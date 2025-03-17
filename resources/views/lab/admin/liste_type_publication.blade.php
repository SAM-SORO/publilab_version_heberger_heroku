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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherTypePublication') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un type de publication" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="d-flex justify-content-end align-items-center mb-2 mt-5" style="max-width: 90%">
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTypePublicationModal">
        <i class="fas fa-plus-circle"></i> Nouveau
    </button>
</div>

<div class="p-5">
    @if ($typesPublications->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun type de publication disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun type de publication" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($typesPublications as $typePublication)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-book"></i> {{ $typePublication->libeleTypePub }}
                            </h6>
                        </div>

                        <div class="card-body">
                            <!-- Description -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-info mb-2">
                                    <i class="fas fa-info-circle"></i> Description
                                </h6>
                                <p class="mb-2">
                                    {{ $typePublication->descTypePub ?? 'Aucune description disponible' }}
                                </p>
                            </div>

                            <!-- Statistiques -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-info mb-2">
                                    <i class="fas fa-chart-bar"></i> Statistiques
                                </h6>
                                <p class="mb-2">
                                    <i class="fas fa-book text-secondary"></i>
                                    <strong>Nombre de publications :</strong> {{ $typePublication->publications->count() }}
                                </p>
                            </div>

                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.modifierTypePublication', $typePublication->idTypePub) }}"
                                   class="btn btn-outline-primary btn-sm mr-2">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>

                                <form id="deleteTypePublicationForm" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $typePublication->idTypePub }})">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $typesPublications->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal d'ajout de type de publication -->
<div class="modal fade" id="addTypePublicationModal" tabindex="-1" role="dialog" aria-labelledby="addTypePublicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addTypePublicationModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouveau type de publication
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerTypePublication') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="libeleTypePub" class="font-weight-bold">
                            Libellé du type de publication <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('libeleTypePub') is-invalid @enderror"
                               id="libeleTypePub" name="libeleTypePub" required>
                        @error('libeleTypePub')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="descTypePub" class="font-weight-bold">Description</label>
                        <textarea class="form-control @error('descTypePub') is-invalid @enderror"
                                  id="descTypePub" name="descTypePub" rows="4"></textarea>
                        @error('descTypePub')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
    function confirmDelete(typePublicationId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce type de publication ?",
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
                const form = document.getElementById('deleteTypePublicationForm');
                form.action = '/admin/supprimer-TypePublication/' + typePublicationId;

                // Soumettre le formulaire
                form.submit();
            }
        });
    }
</script>

@endsection
