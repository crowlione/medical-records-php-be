<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SickLeaveController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\VisitController;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Diagnosis;
use App\Models\SickLeave;
use App\Models\Visit;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function () {
        return response()->json(auth()->user()->load('patient', 'doctor'), 200);
    })->name('user');

    Route::group(['prefix' => 'doctors'], function () {
        Route::get('/', [DoctorController::class, 'index'])
            ->name('doctors.index')
            ->can('viewAny', Doctor::class);

        Route::post('/', [DoctorController::class, 'store'])
            ->name('doctors.store')
            ->can('create', Doctor::class);

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
            ->name('patients.index')
            ->can('viewAny', Patient::class);

        Route::post('/', [PatientController::class, 'store'])
            ->name('patients.store')
            ->can('create', Patient::class);

        Route::get('/{id}', [PatientController::class, 'show'])
            ->name('patients.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [PatientController::class, 'update'])
            ->name('patients.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [PatientController::class, 'destroy'])
            ->name('patients.destroy')
            ->where('id', '[0-9]+');

        Route::get('/diagnosis/{diagnosis}', [PatientController::class, 'diagnosis'])
            ->name('patients.diagnosis')
            ->where('diagnosis', '[0-9]+');

        Route::get('/gp/{gp}', [PatientController::class, 'gp'])
            ->name('patients.gp')
            ->where('gp', '[0-9]+');

        Route::get('/count/gps', [PatientController::class, 'countByGps'])
            ->name('patients.count.gps')
            ->can('countByGps', Patient::class);
    });

    Route::group(['prefix' => 'sick-leaves'], function () {
        Route::get('/', [SickLeaveController::class, 'index'])
            ->name('sick-leaves.index')
            ->can('viewAny', SickLeave::class);

        Route::post('/', [SickLeaveController::class, 'store'])
            ->name('sick-leaves.store')
            ->can('create', SickLeave::class);

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
            ->name('diagnoses.index')
            ->can('viewAny', Diagnosis::class);

        Route::post('/', [DiagnosisController::class, 'store'])
            ->name('diagnoses.store')
            ->can('create', Diagnosis::class);

        Route::get('/{id}', [DiagnosisController::class, 'show'])
            ->name('diagnoses.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [DiagnosisController::class, 'update'])
            ->name('diagnoses.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [DiagnosisController::class, 'destroy'])
            ->name('diagnoses.destroy')
            ->where('id', '[0-9]+');

        Route::get('/most-common', [DiagnosisController::class, 'mostCommon'])
            ->name('diagnoses.mostCommon')
            ->can('viewAny', Diagnosis::class);
    });


    Route::group(['prefix' => 'visits'], function () {
        Route::get('/', [VisitController::class, 'index'])
            ->name('visits.index')
            ->can('viewAny', Visit::class);

        Route::post('/', [VisitController::class, 'store'])
            ->name('visits.store')
            ->can('create', Visit::class);

        Route::get('/{id}', [VisitController::class, 'show'])
            ->name('visits.show')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [VisitController::class, 'update'])
            ->name('visits.update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [VisitController::class, 'destroy'])
            ->name('visits.destroy')
            ->where('id', '[0-9]+');

        Route::get('/doctor/{doctor}', [VisitController::class, 'doctor'])
            ->name('visits.doctor')
            ->where('doctor', '[0-9]+')
            ->can('viewAllDoctorVisits', Visit::class);
    });
});