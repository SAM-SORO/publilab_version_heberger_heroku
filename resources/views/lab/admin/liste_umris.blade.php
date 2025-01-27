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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherUmris') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un UMRI" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">

        <!-- Bouton pour ouvrir le modal pour ajouter un EDP -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUmriModal">
                Ajouter un UMRI
            </button>
        </div>

    </div>
</div>

<div class="p-5">
    @if ($umris->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucune UMRI disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($umris as $umri)
                <div class="col mb-4">
                    <div class="d-flex flex-column rounded shadow bg-white p-3 h-100">
                        <div class="d-flex">
                            <div class="ml-3">
                                @if($umri->nomUMRI)
                                    <p class="mb-1 font-weight-bold">{{ $umri->nomUMRI }}</p>
                                @endif

                                @if($umri->localisationUMI)
                                    <p>Lieu : {{ $umri->localisationUMI }}</p>
                                @endif

                                @if($umri->WhatsAppUMRI)
                                    <p>WhatsApp: {{ $umri->WhatsAppUMRI }}</p>
                                @endif

                                @if($umri->emailUMRI)
                                    <p>Email: {{ $umri->emailUMRI }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-auto">
                            <!-- Formulaire pour modifier un UMRI -->
                            <form action="{{ route('admin.modifierUmris', $umri->idUMRI) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2" type="submit">Modifier</button>
                            </form>

                            <form id="deleteUMRIForm" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $umri->idUMRI }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $umris->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>
</div>

<!-- Modal pour enregistrer un UMRI -->
<div class="modal fade" id="addUmriModal" tabindex="-1" role="dialog" aria-labelledby="addUmriModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addUmriModalLabel">Enregistrer un UMRI</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.enregistrerUmris') }}" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label for="nomUMRI">Nom de l'UMRI <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nomUMRI') is-invalid @enderror" id="nomUMRI" name="nomUMRI" placeholder="Nom de l'UMRI" required>
                    @error('nomUMRI')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="localisationUMI">Localisation</label>
                    <input type="text" class="form-control @error('localisationUMI') is-invalid @enderror" id="localisationUMI" name="localisationUMI" placeholder="Localisation">
                    @error('localisationUMI')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="WhatsAppUMRI">WhatsAppUMRI</label>
                    <input type="text" class="form-control @error('WhatsAppUMRI') is-invalid @enderror" id="WhatsAppUMRI" name="WhatsAppUMRI" placeholder="WhatsApp">
                    @error('WhatsAppUMRI')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="emailUMRI">Email</label>
                    <input type="email" class="form-control @error('emailUMRI') is-invalid @enderror" id="emailUMRI" name="emailUMRI" placeholder="Email">
                    @error('emailUMRI')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="idEDP">Sélectionner EDP <span class="text-danger">*</span></label>
                    <select class="form-control @error('idEDP') is-invalid @enderror" id="idEDP" name="idEDP" multiple required>
                        @foreach ($edps as $edp)
                            <option value="{{ $edp->idEDP }}">{{ $edp->nomEDP }}</option>
                        @endforeach
                    </select>
                    @error('idEDP')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>

    </div>
</div>
</div>

@endsection

@section('scripts')
<script>

    $('#idEDP').select2({
        allowClear: true,
        maximumSelectionLength: 1, // Limite la sélection à une seule option
        width: '100%' // Ajuste la largeur pour un affichage responsive
    });

    function confirmDelete(umriId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cet UMRI ?",
            text: "Cette action est irréversible et supprimera également les laboratoires associés.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire et mettre à jour l'URL de l'action
                const form = document.getElementById('deleteUMRIForm');
                form.action = '/admin/supprimer-umris/' + umriId;

                // Soumettre le formulaire
                form.submit();
            }
        });
    }
</script>
@endsection
