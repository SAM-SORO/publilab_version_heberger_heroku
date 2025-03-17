<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

# moi

Personnalisation des Liens de Pagination
Si vous souhaitez personnaliser davantage les liens de pagination, vous pouvez publier les vues de pagination et les modifier :

Publiez les vues de pagination :

bash
Copier le code
php artisan vendor:publish --tag=laravel-pagination
Modifiez la vue resources/views/vendor/pagination/bootstrap-4.blade.php selon vos besoins.



<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $clients->links('vendor.pagination.bootstrap-4') }}
</div>


## creation des models et de leur controllleurs

php artisan make:model BDIndexation -m
php artisan make:model Grade -m
php artisan make:model Chercheur -m
php artisan make:model Laboratoire -m
php artisan make:model Doctorant -m
php artisan make:model Revue -m
php artisan make:model Article -m
php artisan make:model Theme -m
php artisan make:model AxeRecherche -m
php artisan make:model UMRI -m
php artisan make:model EDP -m


php artisan make:model ChercheurGrade -m
php artisan make:model ChercheurArticle -m
php artisan make:model LaboratoireAxeRecherche -m
php artisan make:model DoctorantChercheur -m
php artisan make:model DoctorantArticleChercheur -m
php artisan make:model BDIndexationRevue -m
php artisan make:model ArticleRevue -m



## //
php artisan make:migration update_themes_table

// php artisan 
make:migration update_doctorants_table --table=doctorants

// php artisan make:migration add_datefin_to_doctorant_chercheur --table=doctorant_chercheur

## Pour mettre en place la suppression d'un article en utilisant SweetAlert et un bouton moda
Avec un gestionnaire de paquets (npm ou yarn)

npm install sweetalert2

## 2. Configuration de SweetAlert dans Laravel
Si vous utilisez un gestionnaire de paquets (npm), importez SweetAlert2 dans vos fichiers JavaScript.

import Swal from 'sweetalert2';
window.Swal = Swal;

## faire npm install

## afin d'utiliser mix pour la compilation faire 

npm install laravel-mix --save-dev

##
créer un fichier webpack.mix.js Dans le répertoire racine pour gérer la compilation de vos fichiers JavaScript, y compris l'utilisation de import dans des modules

let mix = require('laravel-mix');

mix.js('resources/js/main.js', 'public/assets/js')
   .setPublicPath('public');

## utiliser require pour les importation
//remplaceer
il faut changer simplement en require 
import './bootstrap';

// Par celle-ci
require('./bootstrap');

## si on veut continuer a utiliser les import inclurer babel dans le webpack

let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/assets/js')
   .setPublicPath('public')
   .babelConfig({
       presets: ['@babel/preset-env'],
   });

puis 

Installer les dépendances nécessaires :

Assurez-vous d'installer @babel/preset-env et babel-loader si ce n'est pas déjà fait :

npm install --save-dev @babel/preset-env babel-loader

## compiler les assets avec mix


## avec le cdn
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

## Ajout du jeton CSRF
Le jeton CSRF est nécessaire pour les requêtes sécurisées en Laravel. Assurez-vous que votre mise en page inclut cette balise :
essentiel pour que on puisse faire des requetes securise du client vers le serveur

<meta name="csrf-token" content="{{ csrf_token() }}">


## afin de benificier du choix multiple 
avec fonctionnaliser de recherche (version plus esthethique)
telecharger si tu ne veux pas utiliser le CDN
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

https://github.com/select2/select2/releases


Incluez les fichiers dans votre projet :

Décompressez le fichier ZIP et placez les fichiers select2.min.css et select2.min.js dans un dossier, par exemple public/vendor/select2/.
Modifiez votre code pour inclure les fichiers locaux : Dans votre fichier HTML, ajoutez les liens vers les fichiers locaux que vous avez téléchargés :

<link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
<script src="{{ asset('assets/select2/select2.min.js') }}"></script>

on peut l'utiliser maintenant 


php artisan migrate:fresh --seed
php artisan db:wipe
hp artisan migrate