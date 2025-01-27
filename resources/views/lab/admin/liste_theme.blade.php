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



<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">
        <!-- Bouton pour ouvrir le modal pour ajouter un thème -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addThemeModal">
                Ajouter un Thème
            </button>
        </div>
    </div>
</div>

<div class="p-5">
    @if ($themes->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun thème disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($themes as $theme)
                <div class="col mb-4">
                    <div class="d-flex flex-column rounded shadow bg-white p-3 h-100">
                        <div class="d-flex">
                            <div class="ml-3">
                                <p class="mb-1 font-weight-bold">Intitulé : {{ $theme->intituleTheme }}</p>
                                @if ($theme->descTheme)
                                    <p>Description : {{ $theme->descTheme }}</p>
                                @endif
                                <p>Axe de Recherche : {{ $theme->axeRecherche->titreAxeRech }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-auto">
                            <!-- Formulaire pour modifier un thème -->
                            <form action="{{ route('admin.modifierTheme', $theme->idTheme) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2" type="submit">Modifier</button>
                            </form>

                            <form id="deleteThemeForm" action="{{ route('admin.supprimerTheme', $theme->idTheme) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $theme->idTheme }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>


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

<!-- Modal pour enregistrer un thème -->
<div class="modal fade" id="addThemeModal" tabindex="-1" aria-labelledby="addThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.enregistrerTheme') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addThemeModalLabel">Ajouter un Thème</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="intituleTheme">Intitulé du Thème <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control @error('intituleTheme') is-invalid @enderror"
                            id="intituleTheme"
                            name="intituleTheme"
                            value="{{ old('intituleTheme') }}"
                            required
                        >
                        @error('intituleTheme')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="descTheme">Description du Thème</label>
                        <textarea
                            class="form-control @error('descTheme') is-invalid @enderror"
                            id="descTheme"
                            name="descTheme">{{ old('descTheme') }}</textarea>
                        @error('descTheme')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="idAxeRech">Axe de Recherche <span class="text-danger">*</span></label>
                        <select
                            class="form-control @error('idAxeRech') is-invalid @enderror"
                            id="idAxeRech"
                            name="idAxeRech[]"
                            multiple
                            required
                        >
                            <option value="" disabled>-- Sélectionnez un Axe de Recherche --</option>
                            @foreach ($axesRecherches as $axe)
                                <option value="{{ $axe->idAxeRech }}"
                                    @if(in_array($axe->idAxeRech, old('idAxeRech', []))) selected @endif
                                >
                                    {{ $axe->titreAxeRech }}
                                </option>
                            @endforeach
                        </select>
                        @error('idAxeRech')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        // Initialisation de Select2 avec une recherche et une sélection unique
        $('#idAxeRech').select2({
            placeholder: '-- Sélectionnez un Axe de Recherche --', // Texte par défaut
            maximumSelectionLength: 1, // Restriction : Une seule option sélectionnable
            allowClear: true, // Autorise l'effacement de la sélection
            width: '100%' // Largeur responsive
        });
    });

    function confirmDelete(themeId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce thème ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire avec l'ID générique
                const form = document.getElementById('deleteThemeForm');
                // Modifier l'action du formulaire pour inclure l'ID du thème
                form.action = '/admin/supprimer-theme/' + themeId;
                // Soumettre le formulaire
                form.submit();
            }
        });
    }



</script>

@endsection
