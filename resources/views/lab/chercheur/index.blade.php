@extends("baseChercheur")

@section('bg-content', 'bg-white')

@section('content')

<div class="container-fluid" style="margin-top: 4%">
    <div class="row flex-column p-4">
        <div class="col-12 mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="shadow p-5 rounded bg-white">
                        <h5 class="text-danger">Nombre d'articles publié</h5>
                        <a href="{{ route('chercheur.listeArticles')}}"><h2 class="text-center">{{ $NbreArticles }}</h2></a>
                    </div>
                </div>
                {{-- <div class="col-6">
                    <div class="shadow p-5 rounded bg-white">
                        <h5 class="text-danger">Nombre de visiteurs</h5>
                        <a href="#"><h2 class="text-center">{{ $nombreVisiteurs }}</h2></a>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

@endsection
