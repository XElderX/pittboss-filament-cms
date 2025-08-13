<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-external-db', function() {
    $user = \App\Models\Users::where('role', 'admin')->first();
    return response()->json([
        'user' => $user,
        'name' => $user ? $user->getFilamentName() : 'No admin user found'
    ]);
});
