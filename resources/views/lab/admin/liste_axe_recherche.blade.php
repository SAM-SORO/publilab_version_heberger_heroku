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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherAxeRecherch') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un axe de recherche" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>

</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">
        <!-- Bouton pour ouvrir le modal pour ajouter un EDP -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAxeRechModal">
                Ajouter
            </button>
        </div>

    </div>
</div>


<div class="p-5">
    @if ($axesRecherches->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun axe de recherche disponible.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($axesRecherches as $axe)
                <div class="col mb-4">
                    <div class="d-flex flex-column rounded shadow bg-white p-3 h-100">
                        <div class="d-flex">
                            <div class="ml-3">
                                <p class="mb-1 font-weight-bold">{{ $axe->titreAxeRech }}</p>

                                @if($axe->descAxeRech)
                                    <p>Description : {{ $axe->descAxeRech }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-auto">
                            <!-- Formulaire pour modifier un axe -->
                            <form action="{{ route('admin.modifierAxeRecherche', $axe->idAxeRech) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2" type="submit">Modifier</button>
                            </form>

                            <form id="deleteAxeForm" action="{{ route('admin.supprimerAxeRecherche', $axe->idAxeRech) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $axe->idAxeRech }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>



                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $axesRecherches->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>


<!-- Modal pour enregistrer un axe -->
<div class="modal fade" id="addAxeRechModal" tabindex="-1" aria-labelledby="addAxeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.enregistrerAxeRecherche') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAxeModalLabel">Ajouter un Axe de Recherche</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titreAxeRech">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="titreAxeRech" name="titreAxeRech" required>
                    </div>
                    <div class="form-group">
                        <label for="descAxeRech">Description</label>
                        <textarea class="form-control" id="descAxeRech" name="descAxeRech"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function confirmDelete(axeId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cet axe de recherche ?",
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
@endsection
