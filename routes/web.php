<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Livewire\Dashboard\DashboardComponent;
use App\Livewire\Locations\LocationComponent;
use App\Livewire\Modules\ModuleComponent;
use App\Livewire\Review\ReviewComponent;
use App\Livewire\Roles\RoleComponent;
use App\Livewire\Tasks\TaskComponent;
use App\Livewire\Users\UserComponent;
use App\Livewire\Validation\ValidationComponent;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function(){
    return view('auth.login');
})->name('auth');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/home', DashboardComponent::class)->name('home');
    Route::get('/tasks', TaskComponent::class)->name('tasks');
    Route::get('/validations', ValidationComponent::class)->name('validations');
    Route::get('/reviews', ReviewComponent::class)->name('reviews');
    Route::get('/users', UserComponent::class)->name('users');
    Route::get('/locations', LocationComponent::class)->name('locations');
    Route::get('/roles',RoleComponent::class)->name('roles');
    Route::get('/modules', ModuleComponent::class)->name('modules');
});
// Route::get('/register', function(){
//     return view('auth.register');
// });
