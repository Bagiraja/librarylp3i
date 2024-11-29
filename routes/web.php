<?php

use App\Models\Rak;
use App\Models\Book;

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

use App\Models\Fine;
use Faker\Guesser\Name;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RakController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FineController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PustakawanController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NotificationController;

Route::get('/', [LandingPageController::class, 'landingPage'])->name('landing');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('login', [AuthController::class , 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class,'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class,'register']);
Route::post('logout',[AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function(){
Route::get('/admin/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
Route::post('/users/import', [AdminController::class, 'import'])->name('admin.users.import');
Route::get('/users/template', [AdminController::class, 'downloadTemplate'])->name('admin.users.template');
Route::get('/admin/users', [AdminController::class,'manegeuser'])->name('admin.users');
Route::post('/admin/users',[AdminController::class,'storeuser'])->name('admin.users.store');
Route::get('/admin/users/{id}/edit',[AdminController::class,'edituser'])->name('admin.users.edit');
Route::put('/admin/users/{id}',[AdminController::class,'updateuser'])->name('admin.users.update');
Route::post('/admin/users{id}/update-role', [AdminController::class,'updaterole'])->name('admin.users.update-role');
Route::delete('/admin/users/{id}',[AdminController::class,'deleteuser'])->name('admin.users.delete');

Route::get('/admin/users/search',[AdminController::class,'searchuser'])->name('admin.users.search');


Route::get('/admin/rak', [RakController::class, 'index'])->name('admin.rak');
Route::get('/admin/rak/create', [RakController::class, 'create'])->name('admin.rak.create');
Route::post('/admin/rak', [RakController::class, 'store'])->name('admin.rak.store');
Route::get('/admin/rak/{id}/edit', [RakController::class, 'edit'])->name('admin.rak.edit');
Route::put('/admin/rak/{id}', [RakController::class, 'update'])->name('admin.rak.update');
Route::delete('/admin/rak/{id}', [RakController::class, 'delete'])->name('admin.rak.delete');


Route::get('/admin/category',[CategoryController::class, 'index'])->name('admin.category');
Route::get('/admin/category/create', [CategoryController::class,'create'])->name('admin.category.create');
Route::post('/admin/category', [CategoryController::class,'store'])->name('admin.category.store');
Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
Route::delete('/admin/category/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');

Route::get('admin/book', [BookController::class, 'index'])->name('admin.book');
Route::get('admin/book/create', [BookController::class, 'create'])->name('admin.book.create');
Route::post('admin/book', [BookController::class, 'store'])->name('admin.book.store');
Route::put('admin/book/{id}', [BookController::class, 'update'])->name('admin.book.update');
Route::delete('admin/book/{id}', [BookController::class, 'delete'])->name('admin.book.delete');
Route::get('admin/book/{id}/edit', [BookController::class, 'edit'])->name('admin.book.edit');
Route::post('admin/book/delete-all', [BookController::class, 'deleteAll'])->name('admin.book.delete-all');
Route::post('admin/book/import', [BookController::class, 'import'])->name('admin.book.import');
Route::get('admin/book/template', [BookController::class, 'template'])->name('admin.book.template');

Route::get('/admin/messages', [ContactController::class, 'messages'])->name('admin.messages');
Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman');
Route::post('/admin/peminjaman/approve/{id}', [PeminjamanController::class, 'approve'])->name('admin.peminjaman.approve');
Route::post('/admin/peminjaman/disapprove/{id}', [PeminjamanController::class, 'disapprove'])->name('admin.peminjaman.disapprove');
Route::post('/admin/peminjaman/return/{id}', [PeminjamanController::class, 'return'])->name('admin.peminjaman.return');
Route::post('/admin/peminjaman/broken/{id}', [PeminjamanController::class, 'markAsBroken'])->name('admin.peminjaman.broken');
Route::post('/admin/peminjaman/lost/{id}', [PeminjamanController::class, 'markAsLost'])->name('admin.peminjaman.lost');

Route::get('/admin/fines', [FineController::class, 'index'])->name('admin.fines');
Route::post('/admin/fines/mark-as-paid/{id}', [FineController::class, 'markAsPaid'])->name('admin.fines.markAsPaid');

Route::post('/admin/carousel', [AdminController::class, 'carousel'])->name('admin.carousel.store');
Route::get('/admin/carousel/admin', [AdminController::class, 'createcarousel'])->name('admin.carousel.create');
Route::get('/admin/carousel', [AdminController::class, 'indexcrsl'])->name('admin.carousel');
Route::delete('/admin/carousel/{id}', [AdminController::class, 'deleteCarousel'])->name('admin.carousel.delete');

Route::get('/admin/profile', [AuthController::class, 'showProfile'])->name('profile.show');
Route::put('/admin/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

});
// Route::get('/books', [BookController::class, 'userIndex'])->name('books.index');
// Route::get('/books/{id}/borrow', [BookController::class, 'borrowBook'])->name('books.borrow');

Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/mahasiswa/books', [MahasiswaController::class, 'index'])->name('mahasiswa.books');
    Route::post('/mahasiswa/borrow/{id}', [MahasiswaController::class, 'borrow'])->name('mahasiswa.borrow');
    Route::get('/mahasiswa/profile', [MahasiswaController::class, 'profile'])->name('mahasiswa.profile.show');
    Route::put('/mahasiswa/profile', [MahasiswaController::class, 'profileupt'])->name('profile.updatte');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.markAllAsRead');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);


 });


 Route::middleware(['auth', 'role:pustakawan'])->group(function () {
    Route::get('/pustakawan/dashboard',[PustakawanController::class,'dashboard'])->name('pustakawan.dashboard');
    Route::get('pustakawan/book',[PustakawanController::class, 'index'])->name('pustakawan.book');
    Route::get('pustakwan/book/create', [PustakawanController::class, 'create'])->name('pustakawan.book.create');
    Route::post('pustakawan/book', [PustakawanController::class, 'store'])->name('pustakawan.book.store');
    Route::get('pustakawan/book/{id}/edit', [PustakawanController::class,'edit'])->name('pustakawan.book.edit');
    Route::put('pustawakan/book/{id}',[PustakawanController::class, 'update'])->name('pustakawan.book.update');
    Route::delete('pustakawan/book/{id}', [PustakawanController::class, 'delete'])->name('pustakawan.book.delete');

    Route::post('pustakawan/book/import', [PustakawanController::class, 'import'])->name('pustakawan.book.import');
    Route::get('pustakawan/book/template', [PustakawanController::class, 'template'])->name('pustakawan.book.template');
    Route::get('pustakawan/peminjaman', [PustakawanController::class, 'indexpustakawan'])->name('pustakawan.peminjaman');
    Route::post('pustakawan/peminjaman/approve/{id}',[PustakawanController::class, 'approve'])->name('pustakawan.peminjaman.approve');
    Route::post('pustakawan/peminjaman/disapprove/{id}', [PustakawanController::class , 'dissapprove'])->name('pustakawan.peminjaman.dissaprove');
    Route::post('pustakawan/peminjaman/return/{id}', [PustakawanController::class ,'returnn'])->name('pustakawan.peminjaman.returnn');
    Route::post('pustakawan/peminjaman/broken/{id}', [PustakawanController::class, 'broken'])->name('pustakawan.peminjaman.broken');
    Route::post('pustakawan/peminjaman/lost/{id}', [PustakawanController::class, 'lost'])->name('pustakawan.peminjaman.lost');
    Route::get('/pustakawan/profile', [PustakawanController::class, 'profile'])->name('profile.shoow');
    Route::put('/pustakawan/profile', [PustakawanController::class, 'profileupdt'])->name('profile.updatee');
    Route::get('/pustakawan/fines', [PustakawanController::class, 'indexfines'])->name('pustakawan.fines');
    Route::post('/pustakawan/fines/mark-as-paid/{id}', [PustakawanController::class, 'markAsPaid'])->name('pustakawan.fines.markAsPaid');
 });


