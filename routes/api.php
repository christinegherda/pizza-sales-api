<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException; 
use App\Models\User; 
use App\Http\Controllers\Api\SalesController; 
use App\Http\Controllers\Api\ImportController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken('api-token')->plainTextToken;
})->name('login');
 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Import CSV
Route::post('/import-pizzas', [ImportController::class, 'pizza']);
Route::post('/import-orders', [ImportController::class, 'orders']);
Route::post('/import-order-items', [ImportController::class, 'orderDetails']);
Route::post('/import/pizza-types', [ImportController::class, 'pizzaType']);

// Get API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [SalesController::class, 'orders']);
    Route::get('/pizzas', [SalesController::class, 'pizzas']);
    Route::get('/stats', [SalesController::class, 'stats']);
});
