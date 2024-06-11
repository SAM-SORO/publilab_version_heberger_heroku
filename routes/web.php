<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\chercheurController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\mesTest;
use App\Http\Controllers\VisiteurController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// routes/web.php

//Route::get('/',

// Connexion
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login_submit', [LoginController::class, "login_submit"])->name('submitLogin');
// Route de déconnexion
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Visiteur
Route::get('/', [VisiteurController::class, 'pageAccueil'])->name('home');
Route::get('/articles', [VisiteurController::class, 'Articles'])->name('visiteur.article');


// Chercheur
Route::middleware(['auth:chercheur'])->group(function(){
    Route::get('/espace-chercheur', [ChercheurController::class, 'listeArticles'])->name('chercheur.espace');

    Route::post('/modifier-article/{article}', [ChercheurController::class, 'modifierArticle'])->name('chercheur.modifier-article');

    Route::post('/enregistrerModification-article/{article}', [ChercheurController::class, 'enregistrerModificationArticle'])->name('chercheur.enregistrer-modification-article');

    Route::post('/supprimer-article/{article}', [ChercheurController::class, 'supprimerArticle'])->name('chercheur.supprimer-article');

    Route::post('/publier-article', [ChercheurController::class, 'enregistrerPublication'])->name('chercheur.enregistrer-publication');

    Route::get('/publier-article', [ChercheurController::class, 'publierArticle'])->name('chercheur.publierArticle');

    Route::get('/profil', [ChercheurController::class, 'profil'])->name('chercheur.profil');

    Route::post('/modifier-profil/{id}', [ChercheurController::class, 'modifierProfil'])->name('chercheur.modifier-profil');

    Route::get('/telecharger/{document}', [chercheurController::class, 'telecharger'])->name('telecharger.article');
});


// Admin
Route::prefix('admin')->middleware(['auth:admin'])->group(function(){
    Route::get('/espace-admin', [AdminController::class, 'index'])->name('admin.espace');

    Route::get('/chercheur', [AdminController::class, 'listeChercheur'])->name('admin.chercheur');
    Route::get('/enregistrer-chercheur', [AdminController::class, 'enregistrerChercheur'])->name('admin.enregistrer-chercheur');
    Route::get('/modifier-chercheur/{chercheur}', [AdminController::class, 'mdifierChercheur'])->name('admin.modifier-chercheur');
    Route::get('/supprimer-chercheur/{chercheur}', [AdminController::class, 'supprimerChercheur'])->name('admin.supprimer-chercheur');



    Route::get('/articles-publier', [AdminController::class, 'profil'])->name('admin.liste-article-publier');
    Route::get('/publier-articles', [AdminController::class, 'publierArticle'])->name('admin.publier-article');
    Route::get('/supprimer-articles/{article}', [AdminController::class, 'supprimerArtricle'])->name('admin.supprimer-article');
});


    // Route:st('/exist_email', [LoginController::class, 'existEmail'])->name('app_exist_email');});


// Route::middleware(['auth', 'role:visiteur'])->group(function () {
//     Route::match(['get', 'post'], '/', [VisiteurController::class, 'pageAccueil'])->name('home');
//
// });




/*

Route::get('/admin/profil',[AdminController::class, 'profilAdmin'])->name('admin.profil');

*/

