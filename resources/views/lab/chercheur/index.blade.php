@extends("baseChercheur")

@section('content')

    <div class="container mt-4">
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
    </div>

    <!-- Page Content -->
    <div class="p-5">
        @if ($articles->isEmpty())
            <div class="alert alert-info" role="alert">
                Vous n'avez publié aucun article.
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach ($articles as $article)
                    <div class="col mb-4">
                        <div class="d-flex flex-column rounded shadow bg-white p-3 h-100">
                            <div class="d-flex">
                                <!-- Vous pouvez remplacer les liens et images par les données réelles de vos articles -->
                                @if ($article->documents->isNotEmpty())
                                    <a href="#" class="d-flex">
                                        <img src="{{ asset('storage/' . $article->documents->first()->image) }}"
                                        class="img-fluid custom-img" width="150" height="150" alt="Image de l'article">
                                    </a>
                                @else
                                    <a href="#" class="d-flex">
                                        <img src="{{ asset('path/to/default_image.jpg') }}" class="img-fluid custom-img" alt="Image par défaut">
                                    </a>
                                @endif

                                <div class="ml-3">
                                    <p>
                                        <a href="#" class="custom-link">
                                            <h5 class="text-danger">{{ $article->titre }}</h5>
                                        </a>
                                    </p>
                                    <p>{{ $article->description }}</p> <!-- Remplacez par le champ approprié de votre modèle Article -->

                                    <p class="text-muted mt-3"><small>Ajouté le {{ $article->created_at->format('d.m.Y') }}</small></p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-auto">

                                <form action="{{route('chercheur.modifier-article', $article->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary mr-2">Modifier</button>
                                </form>

                                <form action="{{ route('chercheur.supprimer-article', $article->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }} <!-- Affichage de la pagination -->
            </div>
        @endif
    </div>

@endsection



</div>
