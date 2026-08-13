<?php

use App\Http\Controllers\Admin\AdminController as AdminDashboardController;
use App\Http\Controllers\Admin\JobAdminController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Candidate\CandidateController as CandidateDashboardController;
use App\Http\Controllers\Candidate\ProfileController as CandidateProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Recruiter\JobController as RecruiterJobController;
use App\Http\Controllers\Recruiter\ProfileController as RecruiterProfileController;
use App\Http\Controllers\Recruiter\RecruiterController as RecruiterDashboardController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])
    ->middleware('throttle:contact')
    ->name('contact.send');

// Download CV (protected route)
Route::middleware(['auth'])->group(function () {
    Route::get('/download/cv/{userId}', [DownloadController::class, 'downloadCV'])->name('download.cv');
});

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');

// Password reset
Route::get('/mot-de-passe-oublie', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/mot-de-passe-email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:password-reset')
    ->name('password.email');
Route::get('/reinitialiser/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reinitialiser', [ResetPasswordController::class, 'reset'])
    ->middleware('throttle:password-reset')
    ->name('password.update');

// Jobs (public)
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');

// Dashboards
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/jobs', [JobAdminController::class, 'index'])->name('admin.jobs.index');
    Route::get('/admin/logs', [AdminLogController::class, 'index'])->name('admin.logs.index');
    Route::get('/admin/logs/export', [AdminLogController::class, 'export'])->name('admin.logs.export');
    // Job approval
    Route::put('/admin/jobs/{id}/approve', [AdminDashboardController::class, 'approveJob'])->name('admin.jobs.approve');
    Route::put('/admin/jobs/{id}/reject', [AdminDashboardController::class, 'rejectJob'])->name('admin.jobs.reject');
    // User status toggle
    Route::put('/admin/users/{id}/toggle-status', [AdminDashboardController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
});

Route::middleware(['auth', 'role:candidate'])->group(function () {
    Route::get('/candidate/dashboard', [CandidateDashboardController::class, 'dashboard'])->name('candidate.dashboard');
    Route::get('/jobs/{id}/apply', [ApplicationController::class, 'applyForm'])->name('applications.apply.form');
    Route::post('/jobs/{id}/apply', [ApplicationController::class, 'applyStore'])->name('applications.apply.store');
    Route::get('/candidate/profile', [CandidateProfileController::class, 'edit'])->name('candidate.profile.edit');
    Route::put('/candidate/profile', [CandidateProfileController::class, 'update'])->name('candidate.profile.update');
    Route::delete('/candidate/profile/cv', [CandidateProfileController::class, 'deleteCV'])->name('candidate.profile.deleteCV');
});

Route::middleware(['auth', 'role:recruiter'])->group(function () {
    Route::get('/recruiter/dashboard', [RecruiterDashboardController::class, 'dashboard'])->name('recruiter.dashboard');
    // Recruiter job CRUD
    Route::get('/recruiter/jobs', [RecruiterJobController::class, 'index'])->name('recruiter.jobs.index');
    Route::get('/recruiter/jobs/create', [RecruiterJobController::class, 'create'])->name('recruiter.jobs.create');
    Route::post('/recruiter/jobs', [RecruiterJobController::class, 'store'])->name('recruiter.jobs.store');
    Route::get('/recruiter/jobs/{id}/edit', [RecruiterJobController::class, 'edit'])->name('recruiter.jobs.edit');
    Route::put('/recruiter/jobs/{id}', [RecruiterJobController::class, 'update'])->name('recruiter.jobs.update');
    Route::delete('/recruiter/jobs/{id}', [RecruiterJobController::class, 'destroy'])->name('recruiter.jobs.destroy');
    Route::post('/recruiter/jobs/{id}/logo', [RecruiterJobController::class, 'uploadLogo'])->name('recruiter.jobs.logo');
    // Profile
    Route::get('/recruiter/profile', [RecruiterProfileController::class, 'edit'])->name('recruiter.profile.edit');
    Route::put('/recruiter/profile', [RecruiterProfileController::class, 'update'])->name('recruiter.profile.update');
    // Applications management
    Route::put('/recruiter/applications/{id}/accept', [RecruiterDashboardController::class, 'acceptApplication'])->name('recruiter.applications.accept');
    Route::put('/recruiter/applications/{id}/reject', [RecruiterDashboardController::class, 'rejectApplication'])->name('recruiter.applications.reject');
});

// Messaging (authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessagingController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/outbox', [MessagingController::class, 'outbox'])->name('messages.outbox');
    Route::get('/messages/compose', [MessagingController::class, 'compose'])->name('messages.compose');
    Route::post('/messages/send', [MessagingController::class, 'send'])->name('messages.send');
    Route::post('/messages/{id}/read', [MessagingController::class, 'markRead'])->name('messages.read');

    // Profile avatar upload
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');

    // CV download (guarded)
    Route::get('/cv/{applicationId}', [FileController::class, 'downloadCv'])->name('cv.download');
});
