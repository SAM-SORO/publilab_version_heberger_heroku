<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AxeRechercheController;
use App\Http\Controllers\BdIndexationController;
use App\Http\Controllers\chercheurController;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\EdpController;
use App\Http\Controllers\LaboChercheurController;
use App\Http\Controllers\LaboratoireController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UmrisController;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\TypePublicationController;
use App\Http\Controllers\TypeArticleController;
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



// routes/web.php

//Route::get('/',

// Connexion
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [LoginController::class, 'register'])->name('register');

Route::post('/register_submit', [LoginController::class, 'register_submit'])->name('submitRegister');

Route::post('/login_submit', [LoginController::class, "login_submit"])->name('submitLogin');
// Route de déconnexion
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');



// Visiteur
Route::get('/', [VisiteurController::class, 'pageAccueil'])->name('home');

Route::get('/articles', [VisiteurController::class, 'Articles'])->name('visiteur.article');

Route::get('/filtre-article', [ChercheurController::class, 'filtreArticle'])->name('filtre.article');

Route::get('/rechercher-article', [VisiteurController::class, 'rechercherEtFiltrerArticles'])->name('rechercherArticle');



// Chercheur
Route::middleware(['auth:chercheur'])->group(function(){

    Route::get('/espace-chercheur', [ChercheurController::class, 'index'])->name('chercheur.espace');

    Route::get('/listes-articles/chercheur', [ChercheurController::class, 'listeArticles'])->name('chercheur.listeArticles');

    Route::get('/modifier-article/{id}', [ChercheurController::class, 'modifierArticle'])->name('chercheur.modifierArticle');

    Route::post('/chercheur-article/{id}/modifier', [ChercheurController::class, 'updateArticle'])->name('chercheur.updateArticle');


    Route::post('/supprimer-article/chercheur/{id}', [ChercheurController::class, 'supprimerArticle'])->name('chercheur.supprimerArticle');

    Route::post('/enregistrer-article/chercheur', [ChercheurController::class, 'enregistrerArticle'])->name('chercheur.enregistrerArticle');

    Route::get('/profil/chercheur', [ChercheurController::class, 'profil'])->name('chercheur.profil');

    Route::post('/modifier-profil/chercheur', [ChercheurController::class, 'modifierProfil'])->name('chercheur.updateProfil');

    Route::get('/filtre-article/chercheur', [ChercheurController::class, 'filtreArticle'])->name('chercheur.filtreArticle');

    Route::get('/recherche-article/chercheur', [ChercheurController::class, 'rechercheArticle'])->name('chercheur.rechercherArticle');

});


// doctorant
Route::middleware(['auth:doctorant'])->group(function(){

    Route::get('/espace-doctorant', [DoctorantController::class, 'index'])->name('doctorant.espace');

    Route::get('/listes-articles/doctorant', [DoctorantController::class, 'listeArticles'])->name('doctorant.listeArticles');

    Route::get('/modifier/article/{id}', [DoctorantController::class, 'modifierArticle'])->name('doctorant.modifierArticle');

    Route::post('/doctorant-article/{id}/modifier', [DoctorantController::class, 'updateArticle'])->name('doctorant.updateArticle');

    Route::post('/supprimer-article/{id}', [DoctorantController::class, 'delete'])->name('doctorant.supprimerArticle');

    Route::post('/enregistrer-article', [DoctorantController::class, 'enregistrerArticle'])->name('doctorant.enregistrerArticle');

    Route::get('/profil/doctorant', [DoctorantController::class, 'profil'])->name('doctorant.profil');

    Route::post('/modifier-profil/doctorant', [DoctorantController::class, 'modifierProfil'])->name('doctorant.updateProfil');

    Route::get('/filtre-article', [DoctorantController::class, 'filtreArticle'])->name('doctorant.filtreArticle');

    Route::get('/recherche-article', [DoctorantController::class, 'rechercheArticle'])->name('doctorant.rechercherArticle');

});


// Admin
Route::prefix('admin')->middleware(['auth:admin'])->group(function (){

    Route::get('/espace-admin', [AdminController::class, 'index'])->name('admin.espace');

    //ce qui est communs aux deux jai mis pour chacun dans son controlleur

    // Routes pour les ARTICLES
    Route::get('/liste-articles', [AdminController::class, 'listeArticles'])->name('admin.listeArticles');

    Route::post('/enregistrer-article', [AdminController::class, 'enregistrerArticle'])->name('admin.enregistrerArticle');

    //pour envoyer sur la page de modification
    Route::get('/modifier-article/{idArticle}', [AdminController::class, 'modifierArticle'])->name('admin.modifierArticle');

    Route::post('/admin-article/{idArticle}/modifier', [AdminController::class, 'updateArticle'])->name('admin.updateArticle');

    Route::post('/supprimer-article/{id}', [AdminController::class, 'supprimerArticle'])->name('admin.supprimerArticle');

    // Routes pour le profil du chercheur
    Route::get('/profil', [AdminController::class, 'profil'])->name('admin.profil');

    Route::post('/modifier-profil', [AdminController::class, 'modifierProfil'])->name('admin.updateProfil');

    //etant donner que c'est le meme principe on va utiliser la meme fonction qu'on a controller VisiteurController
    Route::get('/rechercher-article', [AdminController::class, 'rechercherEtFiltrerArticles'])->name('admin.rechercherArticle');


    // Routes pour les TYPE d'ARTICLE

    Route::get('/liste-TypeArticles', [TypeArticleController::class, 'index'])->name('admin.listeTypeArticle');


    Route::post('/enregistrer-TypeArticle', [TypeArticleController::class, 'create'])->name('admin.enregistrerTypeArticle');

    //pour envoyer sur la page de modification
    Route::get('/modifier-TypeArticle/{idTypeArticle}', [TypeArticleController::class, 'edit'])->name('admin.modifierTypeArticle');

    //enregistrer modification
    Route::post('/admin/TypeArticle/{idTypeArticle}/modifier', [TypeArticleController::class, 'update'])->name('admin.updateTypeArticle');

    Route::post('/supprimer-TypeArticle/{id}', [TypeArticleController::class, 'delete'])->name('admin.supprimerTypeArticle');


    Route::get('/rechercher-TypeArticle', [TypeArticleController::class, 'search'])->name('admin.rechercherTypeArticle');



    // Routes pour les TYPE PUBLICATION

    Route::get('/liste-TypePublications', [TypePublicationController::class, 'index'])->name('admin.listeTypePublications');

    Route::post('/enregistrer-TypePublication', [TypePublicationController::class, 'create'])->name('admin.enregistrerTypePublication');

    //pour envoyer sur la page de modification
    Route::get('/modifier-TypePublication/{idTypePublication}', [TypePublicationController::class, 'edit'])->name('admin.modifierTypePublication');

    //enregistrer modification
    Route::post('/admin/TypePublication/{idTypePublication}/modifier', [TypePublicationController::class, 'update'])->name('admin.updateTypePublication');

    Route::post('/supprimer-TypePublication/{id}', [TypePublicationController::class, 'delete'])->name('admin.supprimerTypePublication');


    Route::get('/rechercher-TypePublication', [TypePublicationController::class, 'search'])->name('admin.rechercherTypePublication');




    // Routes pour les PUBLICATION
    Route::get('/liste-Publications', [PublicationController::class, 'index'])->name('admin.listePublications');

    Route::post('/enregistrer-Publication', [PublicationController::class, 'create'])->name('admin.enregistrerPublication');

    //pour envoyer sur la page de modification
    Route::get('/modifier-Publication/{idPublication}', [PublicationController::class, 'edit'])->name('admin.modifierPublication');

    //enregistrer modification
    Route::post('/admin/Publication/{idPublication}/modifier', [PublicationController::class, 'update'])->name('admin.updatePublication');

    Route::post('/supprimerPublication/{id}', [PublicationController::class, 'delete'])->name('admin.supprimerPublication');


    Route::get('/rechercher-Publication', [PublicationController::class, 'search'])->name('admin.rechercherPublication');



    //Routes pour les BASES D'INDEXATION

    Route::get('/liste-baseIndexations', [BdIndexationController::class, 'index'])->name('admin.listeBaseIndexation');

    Route::post('/enregistrer-baseIndexation', [BdIndexationController::class, 'create'])->name('admin.enregistrerBaseIndexation');

    //pour envoyer sur la page de modification
    Route::get('/modifier-BaseIndexation/{idBaseIndexation}', [BdIndexationController::class, 'edit'])->name('admin.modifierBaseIndexation');

    //enregistrer modification
    Route::post('/admin/BaseIndexation/{idBaseIndexation}/modifier', [BdIndexationController::class, 'update'])->name('admin.updateBaseIndexation');

    Route::post('/supprimer-baseIndexation/{id}', [BdIndexationController::class, 'delete'])->name('admin.supprimerBaseIndexation');


    Route::get('/rechercher-baseIndexation', [BdIndexationController::class, 'search'])->name('admin.rechercherBaseIndexation');




    // Routes pour les DOCTORANTS

    Route::get('/liste-doctorants', [DoctorantController::class, 'listeDoctorants'])->name('admin.listeDoctorants');

    Route::post('/enregistrer-Doctorant', [DoctorantController::class, 'create'])->name('admin.enregistrerDoctorant');

    //pour envoyer sur
    Route::get('/modifier-Doctorant/{idDoctorant}', [DoctorantController::class, 'edit'])->name('admin.modifierDoctorant');

    //enregistrer modification
    Route::post('/admin/Doctorant/{idDoctorant}/modifier', [DoctorantController::class, 'update'])->name('admin.updateDoctorant');

    Route::post('/supprimerDoctorant/{id}', [DoctorantController::class, 'delete'])->name('admin.supprimerDoctorant');


    Route::get('/rechercher-doctorant', [DoctorantController::class, 'search'])->name('admin.rechercherDoctorant');



    // Routes pour les CHERCHEURS

    Route::get('/chercheurs', [LaboChercheurController::class, 'index'])->name('admin.listeChercheurs');

    Route::post('/enregistrer-chercheur', [LaboChercheurController::class, 'create'])->name('admin.enregistrerChercheur');

    Route::post('/ajouter-grade-chercheur', [LaboChercheurController::class, 'addGrade'])->name('admin.ajouterGrade');

    //pour envoyer sur la page de modification
    Route::get('/modifier-LaboChercheur/{idLaboChercheur}', [LaboChercheurController::class, 'edit'])->name('admin.modifierLaboChercheur');

    //enregistrer modification
    Route::post('/admin/LaboChercheur/{idLaboChercheur}/modifier', [LaboChercheurController::class, 'update'])->name('admin.updateLaboChercheur');

    Route::post('/supprimer-chercheur/{id}', [LaboChercheurController::class, 'delete'])->name('admin.supprimerChercheur');


    Route::get('/rechercher-chercheur', [LaboChercheurController::class, 'search'])->name('admin.rechercherChercheur');


    // Routes pour les GRADES
    Route::get('/liste-grades', [GradeController::class, 'index'])->name('admin.listeGrade');
    Route::post('/enregistrer-grade', [GradeController::class, 'create'])->name('admin.enregistrerGrade');

    //pour envoyer sur la page de modification
    Route::get('/modifier-Grade/{idGrade}', [GradeController::class, 'edit'])->name('admin.modifierGrade');

    //enregistrer modification
    Route::post('/admin/Grade/{idGrade}/modifier', [GradeController::class, 'update'])->name('admin.updateGrade');

    Route::post('/supprimerGrade/{id}', [GradeController::class, 'delete'])->name('admin.supprimerGrade');


    Route::get('/rechercher-grade', [GradeController::class, 'search'])->name('admin.rechercherGrade');




    // Routes pour les THEMES DE RECHERCHES
    Route::get('/liste-themes', [ThemeController::class, 'index'])->name('admin.listeTheme');
    
    Route::post('/enregistrer-theme', [ThemeController::class, 'create'])->name('admin.enregistrerTheme');

    //pour envoyer sur la page de modification
    Route::get('/modifier-Theme/{idTheme}', [ThemeController::class, 'edit'])->name('admin.modifierTheme');

    //enregistrer modification
    Route::post('/admin/Theme/{idTheme}', [ThemeController::class, 'update'])->name('admin.updateTheme');

    Route::post('/supprimerTheme/{id}', [ThemeController::class, 'delete'])->name('admin.supprimerTheme');

    Route::get('/rechercher-theme', [ThemeController::class, 'search'])->name('admin.rechercherTheme');





    // Routes pour les AXES DE RECHERCHES
    Route::get('/liste-axeRecherches', [AxeRechercheController::class, 'index'])->name('admin.listeAxeRecherche');
    Route::post('/enregistrerAxeRecherche', [AxeRechercheController::class, 'create'])->name('admin.enregistrerAxeRecherche');

    Route::get('/modifier-axeRecherche/{id}', [AxeRechercheController::class, 'edit'])->name('admin.modifierAxeRecherche');

    Route::post('/modifier-axeRecherche/{axeRecherche}', [AxeRechercheController::class, 'update'])->name('admin.updateAxeRecherche');

    Route::post('/supprimer-axeRecherche/{id}', [AxeRechercheController::class, 'delete'])->name('admin.supprimerAxeRecherche');

    Route::get('/rechercherAxeRecherche', [AxeRechercheController::class, 'search'])->name('admin.rechercherAxeRecherch');


    // Routes pour les LABORATOIRES
    Route::get('/liste-laboratoires', [LaboratoireController::class, 'index'])->name('admin.listeLaboratoires');
    Route::post('/enregistrer-laboratoire', [LaboratoireController::class, 'create'])->name('admin.enregistrerLaboratoire');

    //pour envoyer sur la page de modification
    Route::get('/modifier-Laboratoire/{idLaboratoire}', [LaboratoireController::class, 'edit'])->name('admin.modifierLaboratoire');

    //enregistrer modification
    Route::post('/admin/Laboratoire/{idLaboratoire}/modifier', [LaboratoireController::class, 'update'])->name('admin.updateLaboratoire');

    Route::post('/supprimer-laboratoire/{id}', [LaboratoireController::class, 'delete'])
    ->name('admin.supprimerLaboratoire');


    Route::get('/rechercher-laboratoire', [LaboratoireController::class, 'search'])->name('admin.rechercherLaboratoire');




    // Routes pour les UMRIS
    Route::get('/liste-umris', [UmrisController::class, 'index'])->name('admin.listeUmris');
    Route::post('/enregistrer-umris', [UmrisController::class, 'create'])->name('admin.enregistrerUmris');

    Route::get('/modifier-umris/{id}', [UmrisController::class, 'edit'])->name('admin.modifierUmris');

    Route::post('/modifier-umris/{id}/umris', [UmrisController::class, 'update'])->name('admin.updateUmris');

    Route::post('/supprimer-umri/{id}', [UmrisController::class, 'delete'])
    ->name('admin.supprimerUmri');

    Route::get('/rechercher-umris', [UmrisController::class, 'search'])->name('admin.rechercherUmris');


    // Routes pour les EDP
    Route::get('/liste-edps', [EdpController::class, 'index'])->name('admin.listeEdp');


    Route::post('/admin/Edp/{idEdp}/modifier', [EdpController::class, 'update'])->name('admin.updateEdp');

    Route::post('/supprimer-edp/{id}', [EdpController::class, 'delete'])
        ->name('admin.supprimerEdp');

    Route::post('/enregistrer-edp', [EdpController::class, 'create'])->name('admin.enregistrerEdp');

    Route::get('/modifier-edp/{id}', [EdpController::class, 'edit'])->name('admin.modifierEDP');

    Route::get('/rechercher-edp', [EdpController::class, 'search'])->name('admin.rechercherEdp');

    Route::get('/filtre-edp', [EdpController::class, 'filtre'])->name('admin.filtrerEdp');


});



    // Route:st('/exist_email', [LoginController::class, 'existEmail'])->name('app_exist_email');});


// Route::middleware(['auth', 'role:visiteur'])->group(function () {
//     Route::match(['get', 'post'], '/', [VisiteurController::class, 'pageAccueil'])->name('home');
//
// });




/*

Route::get('/admin/profil',[AdminController::class, 'profilAdmin'])->name('admin.profil');

*/

