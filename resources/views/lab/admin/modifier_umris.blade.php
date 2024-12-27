@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeUmris') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier un UMRI</h2>
    </div>

    <div class="mb-5">
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

    <form action="{{ route('admin.updateUmris', $umri->idUMRI) }}" method="POST">
        @csrf
        @method('POST')

        <!-- Nom de l'UMRI -->
        <div class="form-group mb-4">
            <label for="nomUMRI">Nom de l'UMRI <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nomUMRI') is-invalid @enderror"
                   id="nomUMRI" name="nomUMRI"
                   value="{{ old('nomUMRI', $umri->nomUMRI) }}" required>
            @error('nomUMRI')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Localisation -->
        <div class="form-group mb-4">
            <label for="localisationUMI">Localisation</label>
            <input type="text" class="form-control @error('localisationUMI') is-invalid @enderror"
                   id="localisationUMI" name="localisationUMI"
                   value="{{ old('localisationUMI', $umri->localisationUMI) }}">
            @error('localisationUMI')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- WhatsApp -->
        <div class="form-group mb-4">
            <label for="WhatsAppUMRI">WhatsApp</label>
            <input type="text" class="form-control @error('WhatsAppUMRI') is-invalid @enderror"
                   id="WhatsAppUMRI" name="WhatsAppUMRI"
                   value="{{ old('WhatsAppUMRI', $umri->WhatsAppUMRI) }}">
            @error('WhatsAppUMRI')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group mb-4">
            <label for="emailUMRI">Email</label>
            <input type="email" class="form-control @error('emailUMRI') is-invalid @enderror"
                   id="emailUMRI" name="emailUMRI"
                   value="{{ old('emailUMRI', $umri->emailUMRI) }}">
            @error('emailUMRI')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- EDP -->
        <div class="form-group mb-4">
            <label for="idEDP">EDP <span class="text-danger">*</span></label>
            <select class="form-control @error('idEDP') is-invalid @enderror"
                    id="idEDP" name="idEDP" required>
                <option value="">-- Sélectionnez un EDP --</option>
                @foreach ($edps as $edp)
                    <option value="{{ $edp->idEDP }}"
                        {{ old('idEDP', $umri->idEDP) == $edp->idEDP ? 'selected' : '' }}>
                        {{ $edp->nomEDP }}
                    </option>
                @endforeach
            </select>
            @error('idEDP')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Bouton de soumission -->
        <div class="form-group mb-4 text-center">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Sauvegarder les modifications
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de Select2 pour l'EDP
        $('#idEDP').select2({
            placeholder: '-- Sélectionnez un EDP --',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
