
@extends("baseChercheur")

@section('content')
    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-5">PROFIL</h2>
        <div>
            <div class="col-10">
                <form action="">
                    @csrf
                    <div class="form-group row mb-5">
                        <label for="title-article" class="col-sm-3 col-form-label">Titre de l'article</label>
                        <div class="col-sm-9">
                            <input type="texte" class="form-control" id="article-title">
                        </div>
                    </div>

                    <div class="form-group row mb-5">
                        <label for="description-article" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-9">
                            <textarea class="form-control " id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-5">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Document physique</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="inputPassword">
                        </div>
                    </div>

                </form>
            </div>
            <div class="btn btn-success ml-3 mt-2 col-5 flex-1 overflow-hidden">Publier l'article</div>
        </div>
    </div>
@endsection

