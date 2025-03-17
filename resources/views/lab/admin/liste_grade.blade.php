@extends("baseAdmin")

@section('bg-content', 'bg-white')

@section('content')

<div class="container mt-4">
    {{-- Messages d'alerte --}}
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" id="alert-danger-login">
            {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-login">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherGrade') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un grade" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="d-flex justify-content-end align-items-center mb-2 mt-5" style="max-width: 90%">
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addGradeModal">
        <i class="fas fa-plus-circle"></i> Nouveau grade
    </button>
</div>

<div class="p-5">
    @if ($grades->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun grade disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun grade" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($grades as $grade)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-graduation-cap"></i> {{ $grade->sigleGrade }}
                            </h6>
                        </div>

                        <div class="card-body">
                            <!-- Nom complet -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-info mb-2">
                                    <i class="fas fa-info-circle"></i> Nom complet
                                </h6>
                                <p class="mb-2">
                                    {{ $grade->nomGrade }}
                                </p>
                            </div>

                            <!-- Statistiques -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-info mb-2">
                                    <i class="fas fa-chart-bar"></i> Statistiques
                                </h6>
                                <p class="mb-2">
                                    <i class="fas fa-users text-secondary"></i>
                                    <strong>Nombre de chercheurs :</strong>
                                    <span class="badge badge-info">{{ $grade->getChercheurCount() }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#detailsModal_{{ $grade->idGrade }}">
                                    <i class="fas fa-info-circle"></i> Détails
                                </button>

                                <div class="d-flex">
                                    <a href="{{ route('admin.modifierGrade', $grade->idGrade) }}"
                                       class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>

                                    <form id="deleteGradeForm" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDelete({{ $grade->idGrade }})">
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

        <div class="d-flex justify-content-center mt-4">
            {{ $grades->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal d'ajout de grade -->
<div class="modal fade" id="addGradeModal" tabindex="-1" role="dialog" aria-labelledby="addGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addGradeModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouveau grade
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerGrade') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="sigleGrade" class="font-weight-bold">
                            Sigle du grade <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('sigleGrade') is-invalid @enderror"
                               id="sigleGrade" name="sigleGrade" required>
                        <small class="form-text text-muted">Ex: PR, MCF, etc.</small>
                        @error('sigleGrade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nomGrade" class="font-weight-bold">
                            Nom complet du grade <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('nomGrade') is-invalid @enderror"
                               id="nomGrade" name="nomGrade" required>
                        <small class="form-text text-muted">Ex: Professeur des Universités</small>
                        @error('nomGrade')
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

<!-- Formulaire de suppression -->
<form id="deleteGradeForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    function confirmDelete(gradeId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce grade ?",
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
                const form = document.getElementById('deleteGradeForm');
                form.action = '/admin/supprimerGrade/' + gradeId;

                // Soumettre le formulaire
                form.submit();
            }
        });
    }
</script>
@endsection
