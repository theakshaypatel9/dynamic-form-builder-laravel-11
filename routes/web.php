<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\FormController as AdminFormController;
use App\Http\Controllers\FormSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin actions
    Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/forms', [AdminFormController::class, 'index'])->name('forms.index');
        Route::get('/forms/create', [AdminFormController::class, 'create'])->name('forms.create');
        Route::post('/forms', [AdminFormController::class, 'store'])->name('forms.store');

        Route::get('/forms/show/{id}', [AdminFormController::class, 'show'])->name('forms.show');
        Route::get('/forms/edit/{id}', [AdminFormController::class, 'edit'])->name('forms.edit');
        Route::put('/forms/update/{id}', [AdminFormController::class, 'update'])->name('forms.update');
        Route::get('forms/{form}/submissions', [AdminFormController::class, 'submissionIndex'])
            ->name('forms.submissions.index');

        Route::get('forms/{form}/submissions-data', [AdminFormController::class, 'submissionsDataTable'])
            ->name('forms.submissions.data');

        Route::get('forms/{form}/submissions/{submission}', [AdminFormController::class, 'submissionShow'])
            ->name('forms.submissions.show');

        Route::delete('forms/{form}/submissions/{submission}', [AdminFormController::class, 'submissionDestroy'])
            ->name('forms.submissions.delete');
    });

    // User actions
    Route::middleware(['auth', 'role:User'])->prefix('user')->name('user.')->group(function () {
        Route::get('/', [FormSubmissionController::class, 'index'])->name('forms.index');
        Route::get('/forms/{id}', [FormSubmissionController::class, 'show'])->name('forms.show');
        Route::post('/forms/{id}/submit', [FormSubmissionController::class, 'store'])->name('forms.submit');
        Route::get('submissions/{submission}/edit', [FormSubmissionController::class, 'edit'])->name('submissions.edit');
        Route::put('submissions/{submission}', [FormSubmissionController::class, 'update'])->name('submissions.update');
    });
});

require __DIR__ . '/auth.php';
