<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\BooksController;

Route::resource('/books', BooksController::class);
