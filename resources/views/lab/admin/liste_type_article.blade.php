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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherTypeArticle') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un type d'article" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="d-flex justify-content-end align-items-center mb-2 mt-5" style="max-width: 90%">
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTypeArticleModal">
        <i class="fas fa-plus-circle"></i> Nouveau
    </button>
</div>

<div class="p-5">
    @if ($typesArticles->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun type d'article disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($typesArticles as $typeArticle)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-dark text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-file-alt"></i> {{ $typeArticle->nomTypeArticle }}
                            </h6>
                        </div>

                        <div class="card-body">
                            <!-- Description -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-info mb-2">
                                    <i class="fas fa-info-circle"></i> Description
                                </h6>
                                <p class="mb-2">
                                    {{ $typeArticle->descTypeArticle ?? 'Aucune description disponible' }}
                                </p>
                            </div>

                            <!-- Statistiques -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold text-info mb-2">
                                    <i class="fas fa-chart-bar"></i> Statistiques
                                </h6>
                                <p class="mb-2">
                                    <i class="fas fa-file text-secondary"></i>
                                    <strong>Nombre d'articles :</strong> {{ $typeArticle->articles->count() }}
                                </p>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.modifierTypeArticle', $typeArticle->idTypeArticle) }}"
                                   class="btn btn-outline-primary btn-sm mr-2">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>

                                <form id="deleteTypeArticleForm" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $typeArticle->idTypeArticle}})">
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
            {{ $typesArticles->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal d'ajout de type d'article -->
<div class="modal fade" id="addTypeArticleModal" tabindex="-1" role="dialog" aria-labelledby="addTypeArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addTypeArticleModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouveau type d'article
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerTypeArticle') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="nomTypeArticle" class="font-weight-bold">
                            Nom du type d'article <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('nomTypeArticle') is-invalid @enderror"
                                id="nomTypeArticle" name="nomTypeArticle" required>
                        @error('nomTypeArticle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="descTypeArticle" class="font-weight-bold">Description</label>
                        <textarea class="form-control @error('descTypeArticle') is-invalid @enderror"
                                    id="descTypeArticle" name="descTypeArticle" rows="4"></textarea>
                        @error('descTypeArticle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer bg-light">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Fermer
                        </button> --}}
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
    function confirmDelete(typeArticleId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce type d'article ?",
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
                const form = document.getElementById('deleteTypeArticleForm');
                form.action = '/admin/supprimer-TypeArticle/' + typeArticleId;

                // Soumettre le formulaire
                form.submit();
            }
        });
    }
</script>

@endsection
