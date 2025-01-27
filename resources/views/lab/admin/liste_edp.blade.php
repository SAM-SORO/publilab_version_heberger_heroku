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
    <!-- Formulaire de recherche -->
    <form action="{{ route('admin.rechercherEdp') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Rechercher un EDP" value="{{ request('query') }}">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>

</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">

        <!-- Bouton pour ouvrir le modal pour ajouter un EDP -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEdpModal">
                Ajouter un EDP
            </button>
        </div>

    </div>
</div>

<div class="p-5">
    @if ($edps->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucune EDP disponible.
        </div>
        <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/img/empty_data.png') }}" alt="aucun article" class="img-fluid" style="width: 350px; height: 350px;">
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($edps as $edp)
                <div class="col mb-4">
                    <div class="d-flex flex-column rounded shadow bg-white p-3 h-100">
                        <div class="d-flex">
                            <div class="ml-3">
                                <p class="mb-1 font-weight-bold">{{ $edp->nomEDP ?? '' }}</p>
                                @if($edp->localisationEDP)
                                    <p>Lieu : {{ $edp->localisationEDP }}</p>
                                @endif
                                @if($edp->WhatsAppUMI)
                                    <p>WhatsApp: {{ $edp->WhatsAppUMI }}</p>
                                @endif
                                @if($edp->emailUMI)
                                    <p>Email: {{ $edp->emailUMI }}</p>
                                @endif
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-auto">
                            <form action="{{ route('admin.modifierEdp', $edp->idEDP) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2" type="submit">Modifier</button>
                            </form>


                            <form id="deleteEDPForm" method="POST">
                                @csrf
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $edp->idEDP }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $edps->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>


<!-- Modal pour enregistrer un EDP -->
<!-- Modal pour enregistrer un EDP -->
<div class="modal fade" id="addEdpModal" tabindex="-1" role="dialog" aria-labelledby="addEdpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEdpModalLabel">Enregistrer un EDP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Modal pour enregistrer un EDP -->
                <form action="{{ route('admin.enregistrerEdp') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nomEDP">Nom de l'EDP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomEDP') is-invalid @enderror" id="nomEDP" name="nomEDP" placeholder="Nom de l'EDP" required>
                        @error('nomEDP')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="localisationEDP">Localisation</label>
                        <input type="text" class="form-control @error('localisationEDP') is-invalid @enderror" id="localisationEDP" name="localisationEDP" placeholder="Localisation">
                        @error('localisationEDP')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="WhatsAppUMI">WhatsAppUMI</label>
                        <input type="text" class="form-control @error('WhatsAppUMI') is-invalid @enderror" id="WhatsAppUMI" name="WhatsAppUMI" placeholder="WhatsApp">
                        @error('WhatsAppUMI')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="emailUMI">Email</label>
                        <input type="email" class="form-control @error('emailUMI') is-invalid @enderror" id="emailUMI" name="emailUMI" placeholder="Email">
                        @error('emailUMI')
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
    function confirmDelete(edpId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer cet EDP ?",
            text: "Cette action est irréversible et ne peut être effectuée si des UMRIs sont associés.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire et mettre à jour l'URL de l'action
                const form = document.getElementById('deleteEDPForm');
                form.action = '/admin/supprimer-edp/' + edpId;

                // Soumettre le formulaire
                form.submit();
            }
        });
    }
</script>

@endsection

