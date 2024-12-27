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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherGrade') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un grade" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Bouton pour ajouter un grade -->
    <div class="d-flex justify-content-end w-100">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addGradeModal">
            Ajouter un Grade
        </button>
    </div>
</div>

<div class="p-5">
    @if ($grades->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun grade disponible.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($grades as $grade)
                <div class="col mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $grade->nomGrade }}</h5>
                            <p class="card-text">Sigle : {{ $grade->sigleGrade ?? '-' }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <form action="{{ route('admin.modifierGrade', $grade->idGrade) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2">Modifier</button>
                            </form>
                            <form id="deleteGradeForm" action="" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" id="submitDelete" style="display:none;"></button> <!-- Bouton caché -->
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $grade->idGrade }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>

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

<!-- Modal pour enregistrer un grade -->
<div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.enregistrerGrade') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGradeModalLabel">Ajouter un Grade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomGrade">Nom du Grade <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomGrade" name="nomGrade" required>
                    </div>
                    <div class="form-group">
                        <label for="sigleGrade">Sigle</label>
                        <input type="text" class="form-control" id="sigleGrade" name="sigleGrade">
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
  function confirmDelete(gradeId) {
    Swal.fire({
        title: "Êtes-vous sûr de vouloir supprimer ce grade ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, Supprimer !",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            // Trouver le formulaire pour l'ID spécifique
            const form = document.getElementById('deleteGradeForm');
            // Mettre à jour l'action du formulaire pour inclure l'ID spécifique
            form.action = '/admin/supprimer-grade/' + gradeId;
            // Soumettre le formulaire
            form.submit();
        }
    });
}

</script>
@endsection


@endsection
