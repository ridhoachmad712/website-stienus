{{-- Poles visual panel admin Filament. CSS murni (tanpa Tailwind build) yang
     di-inject lewat render hook HEAD_END, memakai variabel warna --primary-*
     bawaan Filament sehingga otomatis mengikuti warna brand pilihan admin. --}}
<style>
    /* ===== Halaman login / auth: latar gradient berbrand + kartu lebih lembut ===== */
    .fi-simple-layout {
        background-color: rgb(248 250 252); /* slate-50 */
        background-image:
            radial-gradient(40rem 40rem at 100% 0%, rgba(var(--primary-500), 0.16), transparent 60%),
            radial-gradient(34rem 34rem at 0% 100%, rgba(var(--primary-700), 0.12), transparent 55%);
    }

    .dark .fi-simple-layout {
        background-color: rgb(15 23 42); /* slate-900 */
        background-image:
            radial-gradient(40rem 40rem at 100% 0%, rgba(var(--primary-500), 0.22), transparent 60%),
            radial-gradient(34rem 34rem at 0% 100%, rgba(var(--primary-800), 0.20), transparent 55%);
    }

    .fi-simple-main {
        border-radius: 1.25rem;
        box-shadow: 0 24px 50px -24px rgba(var(--primary-700), 0.35);
    }

    /* Logo di halaman login sedikit lebih besar untuk kesan pertama yang kuat. */
    .fi-simple-header .fi-logo {
        height: 2.75rem !important;
    }

    /* ===== Sudut lebih membulat & konsisten pada kartu/section ===== */
    .fi-section,
    .fi-wi-stats-overview-stat {
        border-radius: 1rem;
    }

    /* Item sidebar aktif lebih tegas. */
    .fi-sidebar-item-active .fi-sidebar-item-button {
        font-weight: 600;
    }

    /* Transisi halus saat sidebar dibuka/ditutup di desktop. */
    .fi-sidebar {
        transition: width 200ms ease;
    }
</style>
