
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskUpdateController;
use App\Http\Controllers\TaskVerificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\ActivityLogController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/', '/dashboard');

    // Manajemen tugas
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/start', [TaskController::class, 'start'])->name('tasks.start');
    Route::patch('/tasks/{task}/cancel', [TaskController::class, 'cancel'])->name('tasks.cancel');

    Route::post('/tasks/{task}/updates', [TaskUpdateController::class, 'store'])->name('tasks.updates.store');
    Route::post('/tasks/{task}/verify', [TaskVerificationController::class, 'store'])->name('tasks.verify');

    // Manajemen pengguna — otorisasi admin diperiksa di controller.
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Manajemen departemen
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Manajemen kategori tugas
    Route::get('/task-categories', [TaskCategoryController::class, 'index'])->name('task-categories.index');
    Route::get('/task-categories/create', [TaskCategoryController::class, 'create'])->name('task-categories.create');
    Route::post('/task-categories', [TaskCategoryController::class, 'store'])->name('task-categories.store');
    Route::get('/task-categories/{taskCategory}/edit', [TaskCategoryController::class, 'edit'])->name('task-categories.edit');
    Route::put('/task-categories/{taskCategory}', [TaskCategoryController::class, 'update'])->name('task-categories.update');
    Route::patch('/task-categories/{taskCategory}/status', [TaskCategoryController::class, 'toggleStatus'])->name('task-categories.toggle-status');
    Route::delete('/task-categories/{taskCategory}', [TaskCategoryController::class, 'destroy'])->name('task-categories.destroy');

    // Activity Log
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Pengaturan akun
    Route::get('/account/settings', [AccountSettingsController::class, 'edit'])->name('account.settings');
    Route::patch('/account/settings/profile', [AccountSettingsController::class, 'updateProfile'])->name('account.profile.update');
    Route::patch('/account/settings/password', [AccountSettingsController::class, 'updatePassword'])->name('account.password.update');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
