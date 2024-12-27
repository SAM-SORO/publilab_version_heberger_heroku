@extends('baseAdmin')

@section('content')
<div class="container-sm mb-5 bg-white shadow-sm mt-5 mb-5 py-5 px-5" style="max-width: 90%">

    <!-- Bouton Retour -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('admin.listeRevue') }}" class="btn btn-outline-secondary mb-4">
            <i class="fa fa-arrow-left"></i> Retour
        </a>

        <h2 class="mb-4 flex-grow-1 text-center">Modifier une revue</h2>
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

    <form action="{{ route('admin.updateRevue', $revue->idRevue) }}" method="POST">
        @csrf
        @method('POST')

        <!-- ISSN -->
        <div class="form-group mb-4">
            <label for="ISSN">ISSN</label>
            <input type="text" class="form-control @error('ISSN') is-invalid @enderror"
                   id="ISSN" name="ISSN" value="{{ old('ISSN', $revue->ISSN) }}" required>
            @error('ISSN')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nom de la revue -->
        <div class="form-group mb-4">
            <label for="nomRevue">Nom de la Revue</label>
            <input type="text" class="form-control @error('nomRevue') is-invalid @enderror"
                   id="nomRevue" name="nomRevue" value="{{ old('nomRevue', $revue->nomRevue) }}" required>
            @error('nomRevue')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-4">
            <label for="descRevue">Description</label>
            <textarea class="form-control @error('descRevue') is-invalid @enderror"
                      id="descRevue" name="descRevue" rows="3">{{ old('descRevue', $revue->descRevue) }}</textarea>
            @error('descRevue')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Type de revue -->
        <div class="form-group mb-4">
            <label for="typeRevue">Type de la Revue</label>
            <input type="text" class="form-control @error('typeRevue') is-invalid @enderror"
                   id="typeRevue" name="typeRevue" value="{{ old('typeRevue', $revue->typeRevue) }}" required>
            @error('typeRevue')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Base d'indexation -->
        <div class="form-group mb-4">
            <label for="bdIndexation">Base d'Indexation</label>
            <select id="bdIndexation" name="bdIndexation[]" class="form-control @error('bdIndexation') is-invalid @enderror">
                @foreach ($bdIndexations as $bdIndexation)
                    <option value="{{ $bdIndexation->idBDIndex }}"
                        {{ in_array($bdIndexation->idBDIndex, old('bdIndexation', $revue->bdIndexations->pluck('idBDIndex')->toArray())) ? 'selected' : '' }}>
                        {{ $bdIndexation->nomBDInd }}
                    </option>
                @endforeach
            </select>
            @error('bdIndexation')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Date de début -->
        <div class="form-group mb-4">
            <label for="dateDebut">Date de début</label>
            <input type="date" class="form-control @error('dateDebut') is-invalid @enderror"
                   id="dateDebut" name="dateDebut"
                   value="{{ old('dateDebut', $revue->bdIndexations->first() ? $revue->bdIndexations->first()->pivot->dateDebut : '') }}">
            @error('dateDebut')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Date de fin -->
        <div class="form-group mb-4">
            <label for="dateFin">Date de fin</label>
            <input type="date" class="form-control @error('dateFin') is-invalid @enderror"
                   id="dateFin" name="dateFin"
                   value="{{ old('dateFin', $revue->bdIndexations->first() ? $revue->bdIndexations->first()->pivot->dateFin : '') }}">
            @error('dateFin')
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
        $('#bdIndexation').select2({
            placeholder: 'Sélectionnez une base d\'indexation',
            allowClear: true,
            maximumSelectionLength: 1,
            width: '100%'
        });
    });
</script>
@endsection
