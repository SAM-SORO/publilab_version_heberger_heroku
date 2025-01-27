@extends('baseAdmin')

@section('title', 'Dashboard')

@section('content')
    {{-- <h1 class="text-primary ml-5 mt-5">DASHBORD</h1> --}}
    <div class="container-fluid" style="margin-top: 4%">
        <div class="row flex-column p-4">
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre de chercheurs</h5>
                            <a href="{{ route('admin.listeChercheurs') }}"><h2 class="text-center">{{ $nombreChercheurs }}</h2></a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre d'articles</h5>
                            <a href="#"><h2 class="text-center">{{ $nombreArticles}}</h2></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row flex-column p-4">
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre d'umris</h5>
                            <a href="{{ route('admin.listeUmris') }}"><h2 class="text-center">{{ $nombreUmris }}</h2></a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre de laboratoires</h5>
                            <a href="{{ route('admin.listeLaboratoires') }}"><h2 class="text-center">{{ $nombreLaboratoires }}</h2></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row flex-column p-4">
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre d'axe de recherche</h5>
                            <a href="{{ route('admin.listeAxeRecherche') }}"><h2 class="text-center">{{ $nombreAxeRecherche }}</h2></a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre de thèmes</h5>
                            <a href="{{ route('admin.listeTheme') }}"><h2 class="text-center">{{ $nombreThemes }}</h2></a>


                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row flex-column p-4">
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre doctorant</h5>
                            <a href="{{ route('admin.listeDoctorant') }}"><h2 class="text-center">{{ $nombreDoctorant }}</h2></a>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre de grades</h5>
                            <a href="{{ route('admin.listeGrade') }}"><h2 class="text-center">{{ $nombreGrade}}</h2></a>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row flex-column p-4">
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-6">
                        <div class="shadow p-5 rounded bg-white">
                            <h5 class="text-danger">Nombre de base d'indexation</h5>
                            <a href="{{ route('admin.listeBaseIndexation') }}"><h2 class="text-center">{{ $nombreBdIndexation }}</h2></a>

                        </div>
                    </div>

                        <div class="col-6">
                            <div class="shadow p-5 rounded bg-white">
                                <h5 class="text-danger">Nombre de revues</h5>
                                <a href="{{ route('admin.listeRevue') }}"><h2 class="text-center">{{ $nombreRevues}}</h2></a>

                            </div>

                        </div>

                </div>
            </div>
        </div>

    </div>
@endsection
