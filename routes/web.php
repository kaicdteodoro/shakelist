<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any?}', static fn() => view('app'))->where('any', '[\/\w\.-]*');
