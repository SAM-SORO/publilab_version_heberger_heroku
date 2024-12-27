@extends("baseChercheur")

@section('content')
    <div class="container mt-4">
        <!-- Notifications d'erreur ou de succès -->
        @if (Session::has('error'))
            <div class="alert alert-danger" role="alert">
                <span>{{ Session::get('error') }}</span>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                <span>{{ Session::get('success') }}</span>
            </div>
        @endif

        <div class="p-4 p-md-5">
            <h2 class="mb-5">Modifier mon profil</h2>
            <form action="{{ route('chercheur.modifierProfil') }}" method="POST">
                @csrf

                <!-- Nom -->
                <div class="form-group">
                    <label for="nomCherch">Nom</label>
                    <input type="text" name="nomCherch" id="nomCherch" class="form-control"
                        value="{{ old('nomCherch', Auth::user()->nomCherch) }}" required>
                    @error('nomCherch')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Prénom -->
                <div class="form-group">
                    <label for="prenomCherch">Prénom</label>
                    <input type="text" name="prenomCherch" id="prenomCherch" class="form-control"
                        value="{{ old('prenomCherch', Auth::user()->prenomCherch) }}" required>
                    @error('prenomCherch')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Adresse -->
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control"
                        value="{{ old('adresse', Auth::user()->adresse) }}">
                    @error('adresse')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact -->
                <div class="form-group">
                    <label for="telCherch">Contact</label>
                    <input type="text" name="telCherch" id="telCherch" class="form-control"
                        value="{{ old('telCherch', Auth::user()->telCherch) }}" required>
                    @error('telCherch')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="emailCherch">Email</label>
                    <input type="email" name="emailCherch" id="emailCherch" class="form-control"
                        value="{{ old('emailCherch', Auth::user()->emailCherch) }}" required>
                    @error('emailCherch')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mot de passe actuel -->
                <div class="form-group">
                    <label for="current_password">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" class="form-control">
                    @error('current_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    @error('new_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmation du nouveau mot de passe -->
                <div class="form-group">
                    <label for="new_password_confirmation">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('modifierBtn').addEventListener('click', function(event) {
            event.preventDefault();
            if (this.innerText === 'Modifier') {
                this.innerText = 'Appliquer';
                document.getElementById('annulerBtn').style.display = 'block';
                toggleFields(false);
            } else {
                document.getElementById('profilForm').submit();
            }
        });

        document.getElementById('annulerBtn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('modifierBtn').innerText = 'Modifier';
            this.style.display = 'none';
            toggleFields(true);
            resetForm();
        });

        function toggleFields(disabled) {
            document.querySelectorAll('#profilForm input').forEach(function(input) {
                input.disabled = disabled;
            });
        }

        function resetForm() {
            var form = document.getElementById('profilForm');
            form.reset();
            Array.from(form.elements).forEach(function(element) {
                if (element.name && element.value !== undefined && element.name in initialValues) {
                    element.value = initialValues[element.name];
                }
            });
        }

        const initialValues = {
            nom: "{{ Auth::user()->nomCherch }}",
            prenom: "{{ Auth::user()->prenomCherch }}",
            contact: "{{ Auth::user()->contact }}",
            email: "{{ Auth::user()->email }}"
        };
    </script>
@endsection

