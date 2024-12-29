let mix = require('laravel-mix');

// Compiler app.js
mix.js('resources/js/app.js', 'public/assets/js')

// Ajouter Select2 JS
   .scripts('resources/select2/select2.min.js', 'public/assets/js/select2.min.js')

// Ajouter jQuery
   .scripts('resources/bootstrap/jquery-3.7.1.min.js', 'public/assets/js/jquery.min.js')

// Ajouter Bootstrap JS
   .scripts('resources/bootstrap/js/bootstrap.min.js', 'public/assets/js/bootstrap.min.js')

// Ajouter Bootstrap CSS
   .css('resources/bootstrap/css/bootstrap.min.css', 'public/assets/css/bootstrap.min.css')

// Ajouter FontAwesome CSS
   .css('resources/fontawesome-free-6.5.1-web/css/fontawesome.min.css', 'public/assets/css/fontawesome.min.css')

// Ajouter Select2 CSS
   .css('resources/select2/select2.min.css', 'public/assets/css/select2.min.css')

// Compiler les fichiers CSS aussi
   .css('resources/css/app.css', 'public/assets/css')

// Chemin public
   .setPublicPath('public');

// Ajouter la version pour le cache busting
mix.version();
