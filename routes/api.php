<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SickLeaveController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\VisitController;

Route::post('/register/patient', [AuthController::class, 'registerPatient']);
Route::post('/register/doctor', [AuthController::class, 'registerDoctor']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'doctors'], function () {
        Route::get('/', [DoctorController::class, 'index'])
            ->name('doctors.index');

        Route::post('/', [DoctorController::class, 'store'])
            ->name('doctors.store');

        Route::get('/{id}', [DoctorController::class, 'show'])
            ->name('doctors.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [DoctorController::class, 'update'])
            ->name('doctors.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [DoctorController::class, 'destroy'])
            ->name('doctors.destroy')
            ->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'patients'], function () {
        Route::get('/', [PatientController::class, 'index'])
            ->name('patients.index');

        Route::post('/', [PatientController::class, 'store'])
            ->name('patients.store');

        Route::get('/{id}', [PatientController::class, 'show'])
            ->name('patients.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [PatientController::class, 'update'])
            ->name('patients.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [PatientController::class, 'destroy'])
            ->name('patients.destroy')
            ->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'sick-leaves'], function () {
        Route::get('/', [SickLeaveController::class, 'index'])
            ->name('sick-leaves.index');

        Route::post('/', [SickLeaveController::class, 'store'])
            ->name('sick-leaves.store');

        Route::get('/{id}', [SickLeaveController::class, 'show'])
            ->name('sick-leaves.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [SickLeaveController::class, 'update'])
            ->name('sick-leaves.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [SickLeaveController::class, 'destroy'])
            ->name('sick-leaves.destroy')
            ->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'diagnoses'], function () {
        Route::get('/', [DiagnosisController::class, 'index'])
            ->name('diagnoses.index');

        Route::post('/', [DiagnosisController::class, 'store'])
            ->name('diagnoses.store');

        Route::get('/{id}', [DiagnosisController::class, 'show'])
            ->name('diagnoses.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [DiagnosisController::class, 'update'])
            ->name('diagnoses.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [DiagnosisController::class, 'destroy'])
            ->name('diagnoses.destroy')
            ->where('id', '[0-9]+');
    });


    Route::group(['prefix' => 'visits'], function () {
        Route::get('/', [VisitController::class, 'index'])
            ->name('visits.index');

        Route::post('/', [VisitController::class, 'store'])
            ->name('visits.store');

        Route::get('/{id}', [VisitController::class, 'show'])
            ->name('visits.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [VisitController::class, 'update'])
            ->name('visits.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [VisitController::class, 'destroy'])
            ->name('visits.destroy')
            ->where('id', '[0-9]+');
    });
});