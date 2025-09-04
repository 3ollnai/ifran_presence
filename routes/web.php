<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinateurController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\EtudiantController;

// Redirection de la racine vers la page de connexion
Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Inscription
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// =======================
// Espaces sécurisés par rôle (Spatie)
// =======================

// Espace administrateur

Route::middleware(['auth', 'role:administrateur'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');

    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    // Nouvelles routes pour les classes et les filières
    Route::get('/admin/classes-filieres', [AdminController::class, 'showClassesAndFilieres'])->name('admin.classes-filieres');
    Route::post('/admin/classes', [AdminController::class, 'createClasse'])->name('admin.classes.create');
    Route::post('/users/{etudiantId}/assign-classe', [AdminController::class, 'assignEtudiantToClasse'])->name('admin.etudiants.assign-classe');
});


// Espace coordinateur

Route::prefix('coordinateur')->name('coordinateur.')->middleware(['auth', 'role:coordinateur'])->group(function () {
    Route::get('/', [CoordinateurController::class, 'index'])->name('index');
    Route::get('seances', [CoordinateurController::class, 'seances'])->name('seances');
    Route::post('seances', [CoordinateurController::class, 'storeSeance'])->name('seances.store');
    Route::get('/coordinateur/seances/{seance}/reporter', [CoordinateurController::class, 'reporter'])->name('coordinateur.seances.reporter');
    Route::get('seances/create', [CoordinateurController::class, 'createSeance'])->name('seances.create');
    Route::get('seances/{seance}/edit', [CoordinateurController::class, 'editSeance'])->name('seances.edit');
    Route::put('seances/{seance}', [CoordinateurController::class, 'updateSeance'])->name('seances.update');
    Route::delete('seances/{seance}', [CoordinateurController::class, 'destroySeance'])->name('seances.destroy');
    Route::get('seances/{seance}/presence', [CoordinateurController::class, 'presence'])->name('seances.presence');
    Route::post('seances/{seance}/presence', [CoordinateurController::class, 'storePresence'])->name('seances.storePresence');
    Route::get('/absences', [CoordinateurController::class, 'absences'])->name('absences');
    Route::post('/justifier-absence/{presence_id}', [CoordinateurController::class, 'justifierAbsence'])->name('absencejustifier');
    Route::get('/emploi', [CoordinateurController::class, 'emploi'])->name('emploi');
});


// Espace professeur

Route::middleware(['auth', 'role:professeur'])->group(function () {
    Route::get('/professeur', [ProfesseurController::class, 'index'])->name('professeur.index');
    Route::get('/professeur/seances', [ProfesseurController::class, 'seances'])->name('professeur.seances');
    Route::get('/professeur/seance/{id}', [ProfesseurController::class, 'showSeance'])->name('professeur.showSeance');

    Route::get('/professeur/presences/create', [ProfesseurController::class, 'createPresence'])->name('presences.create');
    Route::post('/professeur/presences', [ProfesseurController::class, 'storePresence'])->name('professeur.storePresence');
    Route::get('/professeur/historique', [ProfesseurController::class, 'historique'])->name('historique.presence');

    Route::post('/professeur/seance/{id}/presence', [ProfesseurController::class, 'markPresence'])->name('professeur.markPresence');
});


// Espace étudiant
// routes/web.php

Route::prefix('etudiant')->name('etudiant.')->middleware(['auth', 'role:etudiant'])->group(function () {
    Route::get('/', [EtudiantController::class, 'index'])->name('index');
    Route::get('/emploi-du-temps', [EtudiantController::class, 'emploiDuTemps'])->name('emploi_du_temps');
    Route::get('/taux-presence', [EtudiantController::class, 'tauxPresence'])->name('taux_presence');
    Route::get('/justifier-absence', [EtudiantController::class, 'justifierAbsence'])->name('justifier_absence');
    Route::post('/justifier-absence', [EtudiantController::class, 'storeJustification'])->name('store_justification');
    Route::get('/historique', [EtudiantController::class, 'historique'])->name('historique');
});


// Espace parent
Route::middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/parent', fn() => 'Espace parent');
});

// Route de test (hors Spatie)
Route::middleware(['test'])->get('/test', function () {
    return 'Test route';
});
