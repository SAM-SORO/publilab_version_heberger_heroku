<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\chercheurController;
use App\Http\Controllers\mesTest;
use App\Http\Controllers\VisiteurController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::match(['get', 'post'], '/', [VisiteurController::class, 'pageAccueil'])->name('home');


Route::get('/deconnexion',[VisiteurController::class,'deconnexion'])->name('app_deconnexion');


Route::get('/articles',[VisiteurController::class, 'Articles'])->name('visiteur.article');

Route::get('/chercheur/article',[chercheurController::class, 'espaceChercheur'])->name('article.chercheur');


Route::get('/chercheur/publication',[chercheurController::class, 'publier'])->name('publier');

Route::get('/chercheur/profil',[chercheurController::class, 'profil'])->name('chercheur.profil');

Route::get('/test',[mesTest::class,'gererTest']);

Route::post('/recherche-article', 'VisiteurController@rechercheArticleParAuteur')->name('recherche.article');


Route::post('/exist_email', [VisiteurController::class, 'existEmail'])->name('app_exist_email');


/*
Route::match(['get', 'post'], '/login', [VisiteurController::class, 'connexion'])->name('login');

Route::match(['get', 'post'], '/register', [VisiteurController::class, 'inscription'])->name('register');
nous allons utiliser fortify
*/



Route::get('/admin/enregistrer-chercheur',[AdminController::class, 'enregistrerChercheur'])->name('enregistrer.chercheur');

Route::get('/admin/profil',[AdminController::class, 'profilAdmin'])->name('admin.profil');



