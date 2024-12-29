let mix = require('laravel-mix');

// Compiler app.js
mix.js('resources/js/app.js', 'public/assets/js')

// Ajouter Select2
   .scripts('resources/select2/select2.min.js', 'public/assets/js/select2.min.js')

// Ajouter jQuery
   .scripts('resources/bootstrap/jquery-3.7.1.min.js', 'public/assets/js/jquery.min.js')

// Ajouter Bootstrap JS
   .scripts('resources/bootstrap/js/bootstrap.min.js', 'public/assets/js/bootstrap.min.js')

// Chemin public
   .setPublicPath('public');

// Compiler les fichiers CSS aussi
mix.css('resources/css/app.css', 'public/assets/css');

// Ajouter la version pour le cache busting
mix.version();
