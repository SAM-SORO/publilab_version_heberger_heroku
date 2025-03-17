@extends("baseAdmin")

@section('bg-content', 'bg-white')

@section('content')

<div class="container mt-4">
    {{-- Messages d'alerte --}}
    @include('lab.partials.alerts')
</div>

<div class="container mt-5">
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherBaseIndexation') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher une base d'indexation" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Bouton pour ajouter une base d'indexation -->
    <div class="d-flex justify-content-end w-100">
        <button type="button" class="btn btn-outline-primary btn-sm shadow-sm" data-toggle="modal" data-target="#addBaseIndexationModal">
            <i class="fas fa-plus"></i> Ajouter
        </button>
    </div>
</div>

<div class="p-5">
    @if ($bdIndexations->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucune base d'indexation disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($bdIndexations as $bdIndexation)
                <div class="col mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $bdIndexation->nomBDInd }}</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="{{ route('admin.modifierBaseIndexation', $bdIndexation->idBDIndex) }}" class="btn btn-outline-primary btn-sm mr-2">
                                <i class="fas fa-edit"></i> Modifier
                            </a>

                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $bdIndexation->idBDIndex }})">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $bdIndexations->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal pour enregistrer une base d'indexation -->
<div class="modal fade" id="addBaseIndexationModal" tabindex="-1" aria-labelledby="addBaseIndexationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.enregistrerBaseIndexation') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBaseIndexationModalLabel">Ajouter une Base d'Indexation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomBDInd">Nom de la Base d'Indexation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomBDInd" name="nomBDInd" required>
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

<!-- Formulaire caché pour la suppression -->
<form id="deleteBdIndexationForm" action="" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

@endsection

@section('scripts')
<script>
    function confirmDelete(bdIndexationId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cette base d'indexation ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire pour l'ID spécifique
                const form = document.getElementById('deleteBdIndexationForm');
                // Mettre à jour l'action du formulaire pour y inclure l'ID spécifique
                form.action = '/admin/supprimer-baseIndexation/' + bdIndexationId;
                // Soumettre le formulaire
                form.submit();
            }
        });
    }
</script>
@endsection


