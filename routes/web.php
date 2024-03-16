<?php

use App\Livewire\Testcomponent;
use Filament\Pages\Auth\Login;
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

Route::get('login', Login::class)->name('login');
Route::get('testcomponent', Testcomponent::class)->name('testcomponent');
