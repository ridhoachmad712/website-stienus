<?php

use App\Livewire\Public\AgendaIndex;
use App\Livewire\Public\AgendaShow;
use App\Livewire\Public\ContactPage;
use App\Livewire\Public\DownloadIndex;
use App\Livewire\Public\GalleryIndex;
use App\Livewire\Public\HomePage;
use App\Livewire\Public\LecturerIndex;
use App\Livewire\Public\PmbPage;
use App\Livewire\Public\PostIndex;
use App\Livewire\Public\PostShow;
use App\Livewire\Public\ProfilePage;
use App\Livewire\Public\ProgramIndex;
use App\Livewire\Public\ProgramShow;
use App\Models\Agenda;
use App\Models\Download;
use App\Models\Post;
use App\Models\Program;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Halaman publik website profil kampus. Setiap route dipetakan ke sebuah
| full-page Livewire component. Dashboard admin ditangani secara terpisah
| oleh Filament pada prefix "/admin".
|
*/

// Beranda
Route::get('/', HomePage::class)->name('home');

// Profil
Route::get('/profil', ProfilePage::class)->defaults('section', 'about')->name('profile');
Route::get('/profil/sejarah', ProfilePage::class)->defaults('section', 'history')->name('profile.history');
Route::get('/profil/sambutan', ProfilePage::class)->defaults('section', 'leader')->name('profile.leader');
Route::get('/profil/struktur', ProfilePage::class)->defaults('section', 'structure')->name('profile.structure');

// Akademik
Route::get('/program-studi', ProgramIndex::class)->name('programs.index');
Route::get('/program-studi/{slug}', ProgramShow::class)->name('programs.show');
Route::get('/dosen', LecturerIndex::class)->name('lecturers.index');

// Berita
Route::get('/berita', PostIndex::class)->name('posts.index');
Route::get('/berita/{slug}', PostShow::class)->name('posts.show');

// Agenda
Route::get('/agenda', AgendaIndex::class)->name('agenda.index');
Route::get('/agenda/{agenda}', AgendaShow::class)->name('agenda.show');

// Galeri
Route::get('/galeri', GalleryIndex::class)->name('gallery.index');

// Pusat Unduhan
Route::get('/unduhan', DownloadIndex::class)->name('downloads.index');
Route::get('/unduhan/{download}', function (Download $download) {
    $download->incrementQuietly('downloads_count');

    return redirect()->away($download->file_url);
})->name('downloads.file');

// PMB (Penerimaan Mahasiswa Baru)
Route::get('/pmb', PmbPage::class)->name('pmb');

// Kontak
Route::get('/kontak', ContactPage::class)->name('contact');

// Sitemap untuk SEO
Route::get('/sitemap.xml', function () {
    $urls = collect();

    $urls->push(['loc' => route('home'), 'priority' => '1.0']);
    foreach (['profile', 'profile.history', 'profile.leader', 'profile.structure', 'programs.index', 'lecturers.index', 'posts.index', 'agenda.index', 'gallery.index', 'downloads.index', 'pmb', 'contact'] as $name) {
        $urls->push(['loc' => route($name), 'priority' => '0.7']);
    }

    Post::query()->where('status', 'published')->get(['slug', 'updated_at'])
        ->each(fn (Post $p) => $urls->push(['loc' => route('posts.show', $p->slug), 'lastmod' => $p->updated_at?->toAtomString(), 'priority' => '0.6']));

    Program::query()->get(['slug', 'updated_at'])
        ->each(fn (Program $p) => $urls->push(['loc' => route('programs.show', $p->slug), 'lastmod' => $p->updated_at?->toAtomString(), 'priority' => '0.6']));

    Agenda::query()->get(['id', 'updated_at'])
        ->each(fn (Agenda $a) => $urls->push(['loc' => route('agenda.show', $a), 'lastmod' => $a->updated_at?->toAtomString(), 'priority' => '0.5']));

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
