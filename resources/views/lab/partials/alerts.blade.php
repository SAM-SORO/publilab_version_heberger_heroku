
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
