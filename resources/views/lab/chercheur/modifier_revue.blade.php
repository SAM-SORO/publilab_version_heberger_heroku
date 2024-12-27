@extends("baseChercheur")

@section('content')

    <div id="content" class="p-4 p-md-5 pt-5 mt-4">
        @if (Session::has('error'))
            <div class="alert alert-danger" role="alert">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                <span>{{ Session::get('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="col-10 mx-auto form-container shadow p-5 mb-5 bg-body rounded">
            <h2 class="mb-5 text-center" style="color: #2a52be;">MODIFIER UNE REVUE</h2>
            <form action="{{ route("chercheur.enregistrer-modification-revue", $revue->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="form-group row mb-4">
                    <label for="revue-cod_ISSN" class="col-sm-3 col-form-label">Code ISSN</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="revue-cod_ISSN" name="cod_ISSN" value="{{ old('cod_ISSN', $revue->cod_ISSN) }}" placeholder="Saisir le code ISSN">
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="revue-cod_DOI" class="col-sm-3 col-form-label">Code DOI</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="revue-cod_DOI" name="cod_DOI" value="{{ old('cod_DOI', $revue->cod_DOI) }}" placeholder="Saisir le code DOI">
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="revue-editeur" class="col-sm-3 col-form-label">Éditeur</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="revue-editeur" name="editeur" value="{{ old('editeur', $revue->editeur) }}" placeholder="Saisir l'éditeur">
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="revue-titre" class="col-sm-3 col-form-label">Titre</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="revue-titre" name="titre" value="{{ old('titre', $revue->titre) }}" placeholder="Saisir le titre">
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="revue-indexe" class="col-sm-3 col-form-label">Indexé</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="revue-indexe" name="indexe">
                            <option value="" disabled selected>Choisir</option>
                            <option value="1" {{ old('indexe', $revue->indexe) == 1 ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ old('indexe', $revue->indexe) == 0 ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="revue-organisme_index" class="col-sm-3 col-form-label">Organisme d'indexation</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="revue-organisme_index" name="organisme_index" value="{{ old('organisme_index', $revue->organisme_index) }}" placeholder="Saisir l'organisme d'indexation">
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success submit-button py-2 px-4">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
@endsection
