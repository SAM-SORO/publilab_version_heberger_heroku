@extends("baseChercheur")

@section('content')

    <div id="content" class="p-4 p-md-5 pt-5 mt-4 ">
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
            <h2 class="mb-5 text-center" style="color: #2a52be;">PUBLIER UN ARTICLE</h2>
            <form action="{{ route('chercheur.enregistrer-publication')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="form-group row mb-4">
                    <label for="article-title" class="col-sm-3 col-form-label">Titre de l'article</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="article-title" name="titre" placeholder="Saisir le titre">
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="article-description" class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="article-description" name="description" rows="3" placeholder="Saisir la description"></textarea>
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="article-document" class="col-sm-3 col-form-label">Document PDF</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="article-document" name="document" accept=".pdf">
                        <small class="form-text text-muted">
                            Uniquement les fichiers PDF sont autorisés
                        </small>
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label for="article-image" class="col-sm-3 col-form-label">Image du Document</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="article-image" name="image_document" accept=".jpg,.png">
                        <small class="form-text text-muted">
                            Uniquement les fichiers JPG/PNG
                        </small>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success submit-button py-2 px-4">Publier l'article</button>
                </div>
            </form>
        </div>
    </div>
@endsection
