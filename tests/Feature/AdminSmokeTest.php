<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_and_resources_render_for_super_admin(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@stienusantara.ac.id')->firstOrFail();
        $this->actingAs($admin);

        $urls = [
            '/admin',
            '/admin/posts',
            '/admin/applicants',
            '/admin/sliders',
            '/admin/testimonials',
            '/admin/activities',
            '/admin/shield/roles',
            '/admin/manage-general-settings',
        ];

        foreach ($urls as $url) {
            $status = $this->get($url)->getStatusCode();
            $this->assertTrue($status >= 200 && $status < 300, "URL {$url} returned {$status}");
        }
    }
}
