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
    <form class="form-inline justify-content-center my-2 mt-2" action="{{ route('admin.rechercherLaboratoire') }}" method="GET">
        @csrf
        <input class="form-control col-lg-8 col-6 col-sm-8 py-4" type="search" name="query" placeholder="Rechercher un laboratoire" aria-label="Rechercher" value="{{ request('query') }}">
        <button class="btn btn-primary search-btn ml-2" type="submit">Rechercher</button>
    </form>
</div>

<div class="container d-flex mt-5 align-items-center">
    <!-- Utilisation de d-flex et justify-content-between pour espacer les éléments -->
    <div class="d-flex justify-content-end w-100">
        {{-- <!-- Formulaire de filtre pour l'EDP -->
        <div class=" col-9">
            <form action="{{ route('admin.filtrerEdp') }}" method="GET">
                @csrf
                <select title="filtre par nom" class="custom-select col-4 col-lg-2 col-sm-6 col-md-3" name="nomEDP" onchange="this.form.submit()">
                    <option value="Tous">Filtre</option>
                    <option value="Tous" {{ request('nomEDP') === 'Tous' ? 'selected' : '' }}>Tous</option>
                    @foreach ($edps as $edp)
                        <option value="{{ $edp->idEDP }}" {{ request('nomEDP') == $edp->idEDP ? 'selected' : '' }}>{{ $edp->nomEDP }}</option>
                    @endforeach
                </select>
            </form>
        </div> --}}

        <!-- Bouton pour ouvrir le modal pour ajouter un EDP -->
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLaboModal">
                Ajouter un Laboratoire
            </button>
        </div>

    </div>
</div>

<div class="p-5">
    @if ($laboratoires->isEmpty())
        <div class="alert alert-info" role="alert">
            Aucun laboratoire disponible.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($laboratoires as $labo)
                <div class="col mb-4">
                    <div class="d-flex flex-column rounded shadow bg-white p-3 h-100">
                        <div class="d-flex">
                            <div class="ml-3">
                                @if ($labo->nomLabo)
                                    <p class="mb-1 font-weight-bold">{{ $labo->nomLabo }}</p>
                                @endif

                                @if ($labo->anneeCreation)
                                    <p>Année de création : {{ $labo->anneeCreation }}</p>
                                @endif

                                @if ($labo->localisationLabo)
                                    <p>Localisation : {{ $labo->localisationLabo }}</p>
                                @endif

                                @if ($labo->adresseLabo)
                                    <p>Adresse : {{ $labo->adresseLabo }}</p>
                                @endif

                                @if ($labo->telLabo)
                                    <p>Téléphone : {{ $labo->telLabo }}</p>
                                @endif

                                @if ($labo->faxLabo)
                                    <p>Fax : {{ $labo->faxLabo }}</p>
                                @endif

                                @if ($labo->emailLabo)
                                    <p>Email : {{ $labo->emailLabo }}</p>
                                @endif

                                @if ($labo->descLabo)
                                    <p>Description : {{ $labo->descLabo }}</p>
                                @endif

                                @if ($labo->umri && $labo->umri->nomUMRI)
                                    <p>UMRI : {{ $labo->umri->nomUMRI }}</p>
                                @endif

                                <!-- Affichage des axes de recherche -->
                                @if ($labo->axesRecherches->isNotEmpty())
                                    <p class="font-weight-bold">Axes de recherche associés :</p>
                                    <ul class="list-unstyled">
                                        @foreach ($labo->axesRecherches as $axe)
                                            <li> -{{$axe->titreAxeRech }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-auto">
                            <!-- Formulaire pour modifier un laboratoire -->
                            <form action="{{ route('admin.modifierLaboratoire', $labo->idLabo) }}" method="GET">
                                @csrf
                                <button class="btn btn-primary mr-2" type="submit">Modifier</button>
                            </form>

                            <form id="deleteLaboratoireForm" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $labo->idLabo }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $laboratoires->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal pour enregistrer un laboratoire -->
<div class="modal fade" id="addLaboModal" tabindex="-1" role="dialog" aria-labelledby="addLaboModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLaboModalLabel">Enregistrer un Laboratoire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.enregistrerLaboratoire') }}" method="POST">
                    @csrf
                    <!-- Nom du laboratoire -->
                    <div class="form-group">
                        <label for="nomLabo">Nom du Laboratoire <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomLabo') is-invalid @enderror" id="nomLabo" name="nomLabo" placeholder="Nom du laboratoire" required>
                        @error('nomLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Année de création -->
                    <div class="form-group">
                        <label for="anneeCreation">Année de création</label>
                        <input type="text" class="form-control @error('anneeCreation') is-invalid @enderror" id="anneeCreation" name="anneeCreation" placeholder="Année de création">
                        @error('anneeCreation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Localisation -->
                    <div class="form-group">
                        <label for="localisationLabo">Localisation</label>
                        <input type="text" class="form-control @error('localisationLabo') is-invalid @enderror" id="localisationLabo" name="localisationLabo" placeholder="Localisation">
                        @error('localisationLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div class="form-group">
                        <label for="adresseLabo">Adresse</label>
                        <input type="text" class="form-control @error('adresseLabo') is-invalid @enderror" id="adresseLabo" name="adresseLabo" placeholder="Adresse">
                        @error('adresseLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label for="telLabo">Téléphone</label>
                        <input type="text" class="form-control @error('telLabo') is-invalid @enderror" id="telLabo" name="telLabo" placeholder="Téléphone">
                        @error('telLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fax -->
                    <div class="form-group">
                        <label for="faxLabo">Fax</label>
                        <input type="text" class="form-control @error('faxLabo') is-invalid @enderror" id="faxLabo" name="faxLabo" placeholder="Fax">
                        @error('faxLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="emailLabo">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('emailLabo') is-invalid @enderror" id="emailLabo" name="emailLabo" placeholder="Email" required>
                        @error('emailLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="descLabo">Description</label>
                        <textarea class="form-control @error('descLabo') is-invalid @enderror" id="descLabo" name="descLabo" placeholder="Description du laboratoire"></textarea>
                        @error('descLabo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- UMRI -->
                    <div class="form-group">
                        <label for="idUMRI">Sélectionner UMRI <span class="text-danger">*</span></label>
                        <select class="form-control @error('idUMRI') is-invalid @enderror" id="idUMRI" name="idUMRI" required>
                            <option value="" disabled selected>Sélectionnez un UMRI</option>
                            @foreach ($umris as $umri)
                                <option value="{{ $umri->idUMRI }}">{{ $umri->nomUMRI }}</option>
                            @endforeach
                        </select>
                        @error('idUMRI')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Axes de recherche -->
                    <div class="form-group">
                        <label for="AxeRecherche">Axes de recherche</label>
                        <select class="form-control" id="AxeRecherche" name="axesRecherche[]" multiple>
                            @foreach ($axesRecherche as $axe)
                                {{-- <option value="{{ $axe->idAxe }}">{{ $axe->titreAxeRech }}</option> --}}
                                <option value="{{ $axe->idAxeRech }}">{{ $axe->descAxeRech}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@section('scripts')

<script>

    $(document).ready(function() {
        $('#AxeRecherche').select2({
            placeholder: 'Sélectionnez le ou les axes de recherches',
            allowClear: true,  // Permet de désélectionner tout
            width: '100%',     // Utilise toute la largeur disponible
            maximumSelectionLength: 5, // Limite le nombre d'éléments sélectionnables (optionnel)
        });
    });

    function confirmDelete(laboId) {
        Swal.fire({
            title: "Êtes-vous sûr de vouloir supprimer ce laboratoire ?",
            text: "Cette action est irréversible et supprimera également les relations associées.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, Supprimer !",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                // Trouver le formulaire et mettre à jour l'URL de l'action
                const form = document.getElementById('deleteLaboratoireForm');
                form.action = '/admin/supprimer-laboratoire/' + laboId;

                // Soumettre le formulaire
                form.submit();
            }
        });
    }

</script>

@endsection

@endsection


{{-- // $('#idUMRI').select2({
//     placeholder: 'Sélectionner UMRI',
//     allowClear: true,
//     ajax: {
//         url: '/api/umris',  // Une route qui retourne les UMRI en fonction de la recherche
//         dataType: 'json',
//         delay: 250,  // délai de recherche
//         processResults: function(data) {
//             return {
//                 results: data.map(function(item) {
//                     return {
//                         id: item.idUMRI,
//                         text: item.nomUMRI
//                     };
//                 })
//             };
//         }
//     }
// }); --}}




