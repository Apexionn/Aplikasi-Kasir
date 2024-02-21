<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Genre;
use App\Models\Barang;
use App\Models\Diskon;
use App\Models\DetailDiskon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DetailDiskonController;
use App\Http\Controllers\DetailTransactionController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes', function () {
    return view('tesphp');
});


Route::get('/barang', function () {
    if (Auth::check() && Auth::user()->role == 'Admin') {
        $data = Barang::with('genres')->paginate(10);
        return view('CRUD.Barang.barang', compact("data"));
    }
    return redirect('/transaction')->with('error', 'You are not authorized to view this page.');
})->middleware(['auth', 'verified'])->name('games');

Route::get('/genre', function () {
    if (Auth::check() && Auth::user()->role == 'Admin') {
        $data = Genre::paginate(8);
        return view('CRUD.Genre.genre', compact('data'));
    }
    return redirect('/transaction')->with('error', 'You are not authorized to view this page.');
})->middleware(['auth', 'verified'])->name('genre');

Route::get('/users', function () {
    if (Auth::check() && Auth::user()->role == 'Admin') {
        $data = User::all();
        return view('CRUD.Users.users', compact("data"));
    }
    return redirect('/transaction')->with('error', 'You are not authorized to view this page.');
})->middleware(['auth', 'verified'])->name('users');

Route::get('/transaction', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('transaction');

Route::get('/detail-transaction', [DetailTransactionController::class, 'showTransactions'])
    ->middleware(['auth', 'verified'])
    ->name('detail');

Route::get('/diskon', [DiskonController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('diskon');

Route::get('/diskon-genre', function () {
    $data = DetailDiskon::with(['diskon', 'genre'])
        ->get()
        ->groupBy('id_diskon')
        ->map(function ($group) {
            return [
                'id' => $group->first()->id_diskon,
                'nama_diskon' => $group->first()->diskon->nama_diskon,
                'genres' => $group->pluck('genre.nama_genre')->join(', '),
            ];
        });

    return view('CRUD.DetailDiskon.detail-diskon', compact('data'));
})->middleware(['auth', 'verified'])->name('detaildiskon');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Diskon
Route::get('/form-add-diskon', [DiskonController::class, 'AddDiskonPage'])->name('add-diskon-page');
Route::post('/proses-tambah-diskon', [DiskonController::class, 'ProsesTambah'])->name('proses-tambah-diskon');
Route::get('/form-edit-diskon/{id}', [DiskonController::class, 'EditDiskonPage'])->name('edit-diskon');
Route::post('/proses-edit-diskon/{id}', [DiskonController::class, 'ProsesUpdate'])->name('proses-edit-diskon');
Route::post('/delete-diskon/{id}', [DiskonController::class, 'DeleteDiskon'])->name('delete-diskon');

// Detail Diskon
Route::get('/form-add-detail-diskon', [DetailDiskonController::class, 'AddDetailDiskonPage'])->name('add-detail-diskon-page');
Route::post('/proses-tambah-detail-diskon', [DetailDiskonController::class, 'ProsesTambah'])->name('proses-tambah-detail-diskon');
Route::get('/form-edit-detail-diskon/{id}', [DetailDiskonController::class, 'EditDetailDiskonPage'])->name('edit-detail-diskon');
Route::post('/proses-edit-detail-diskon/{id}', [DetailDiskonController::class, 'ProsesUpdate'])->name('proses-edit-detail-diskon');
Route::post('/delete-detail-diskon/{id}', [DetailDiskonController::class, 'DeleteDetailDiskon'])->name('delete-detail-diskon');


// Users
Route::get('/form-add-users', [UserController::class, 'AddUserPage'])->name('add-users-page');
Route::post('/proses-tambah-users', [UserController::class, 'ProsesTambah'])->name('proses-tambah-users');
Route::get('/form-edit-users/{id}', [UserController::class, 'EditUserPage'])->name('edit-users');
Route::post('/proses-edit-users/{id}', [UserController::class, 'ProsesUpdate'])->name('proses-edit-users');
Route::post('/delete-users/{id}', [UserController::class, 'DeleteUsers'])->name('delete-users');

Route::get('/search-users', [UserController::class, 'search'])->name('search-users');


// Barang
Route::get('/form-add-barang', [BarangController::class, 'AddBarangPage'])->name('add-barang-page');
Route::post('/proses-tambah-barang', [BarangController::class, 'ProsesTambah'])->name('proses-tambah-barang');
Route::get('/form-edit-barang/{id}', [BarangController::class, 'EditBarangPage'])->name('edit-barang');
Route::post('/proses-edit-barang/{id}', [BarangController::class, 'ProsesUpdate'])->name('proses-edit-barang');
Route::post('/delete-Barang/{id}', [BarangController::class, 'DeleteBarang'])->name('delete-barang');

Route::get('/search-barang', [BarangController::class, 'search'])->name('search-barang');

// Genre
Route::get('/form-add-genre', [GenreController::class, 'AddGenrePage'])->name('add-genre-page');
Route::post('/proses-tambah-genre', [GenreController::class, 'ProsesTambah'])->name('proses-tambah-genre');
Route::get('/form-edit-genre/{id}', [GenreController::class, 'EditGenrePage'])->name('edit-genre');
Route::post('/proses-edit-genre/{id}', [GenreController::class, 'ProsesUpdate'])->name('proses-edit-genre');
Route::post('/delete-Genre/{id}', [GenreController::class, 'DeleteGenre'])->name('delete-genre');

// Transaction
Route::post('/transaction/store', [TransactionController::class, 'store'])->name('proses-transaction');
Route::get('/search-barang-transaction', [TransactionController::class, 'search'])->name('search-barang-transaction');

// Download PDF
Route::get('/download-pdf', [DetailTransactionController::class, 'downloadPdf'])->name('download-pdf');
