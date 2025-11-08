<?php

use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\QRDocumentController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\MailApprovalController;
use Illuminate\Support\Facades\Route;

// Remove any existing auth routes that might conflict with Filament's
// Filament will handle all auth routes automatically
Route::get('/', fn () => redirect()->route('filament.admin.auth.login'));
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// Public QR Document route
Route::get('/qr-document/{qrGeneratorId}', [QRDocumentController::class, 'show'])
    ->name('qr.document.show');

// QR Code generation routes
Route::get('/qr-code/{qrGeneratorId}/download', [QRDocumentController::class, 'downloadQrCode'])
    ->name('qr.code.download');

Route::get('/qr-code/{qrGeneratorId}/generate', [QRDocumentController::class, 'generateQrCode'])
    ->name('qr.code.generate');

Route::get('/qr-code/{qrGeneratorId}/preview', [QRDocumentController::class, 'previewQrCode'])
    ->name('qr.code.preview');

// Public Financial Report route
Route::get('/laporan-keuangan', [FinancialReportController::class, 'index'])
    ->name('laporan-keuangan');
Route::get('/laporan-keuangan/pdf', [FinancialReportController::class, 'exportPdf'])
    ->name('laporan-keuangan.pdf');

// Mail Approval routes
Route::get('/approval/{qr_generator_qr_signer_id}', [MailApprovalController::class, 'show'])
    ->name('mail.approval.show');
Route::post('/approval/{qr_generator_qr_signer_id}/approve', [MailApprovalController::class, 'approve'])
    ->name('mail.approval.approve');
Route::post('/approval/{qr_generator_qr_signer_id}/reject', [MailApprovalController::class, 'reject'])
    ->name('mail.approval.reject');