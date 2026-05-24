<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactInquiryController as AdminContactInquiryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogFeedController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SeoAssessmentController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/service-area', 'service-area')->name('service-area');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('contact.store');

Route::get('/seo-assessment', [SeoAssessmentController::class, 'show'])->name('seo-assessment.show');
Route::post('/seo-assessment', [SeoAssessmentController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('seo-assessment.store');

Route::get('/locations/{slug}', [LocationController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('location.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/rss.xml', BlogFeedController::class)->name('rss');
    Route::get('/category/{slug}', [BlogController::class, 'category'])
        ->where('slug', '[a-z0-9-]+')
        ->name('category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])
        ->where('slug', '[a-z0-9-]+')
        ->name('tag');
    Route::get('/{slug}', [BlogController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->name('show');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('posts', AdminPostController::class)->except('show');
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::resource('tags', AdminTagController::class)->except('show');

        Route::resource('contact-inquiries', AdminContactInquiryController::class)
            ->only(['index', 'show', 'destroy']);
        Route::patch('contact-inquiries/{contact_inquiry}/read', [AdminContactInquiryController::class, 'markRead'])
            ->name('contact-inquiries.read');
        Route::patch('contact-inquiries/{contact_inquiry}/unread', [AdminContactInquiryController::class, 'markUnread'])
            ->name('contact-inquiries.unread');
    });

require __DIR__.'/settings.php';
