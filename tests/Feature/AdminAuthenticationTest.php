<?php

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

it('serves a readable admin login with CSRF and no registration', function () {
    $this->get(route('admin.login'))
        ->assertOk()
        ->assertSee('Akses Terbatas')
        ->assertSee('Financial Intelligence Hub')
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('type-page-title', false)
        ->assertSee('type-card-title', false)
        ->assertSee('type-form-control', false)
        ->assertSee('meta name="robots" content="noindex, nofollow"', false);

    expect(Route::has('register'))->toBeFalse();
    $this->get('/register')->assertNotFound();
});

it('keeps the dashboard private and sends guests to the admin login', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
});

it('rejects authenticated non-admin users from all admin routes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.logout'))->assertForbidden();
});

it('authenticates a real administrator with email and password', function () {
    $admin = User::factory()->create([
        'email' => 'staff@example.test',
        'password' => 'AdminTestPassword!2026',
        'is_admin' => true,
    ]);

    expect($admin->is_admin)->toBeTrue();

    $this->post(route('admin.authenticate'), [
        'email' => 'staff@example.test',
        'password' => 'AdminTestPassword!2026',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin);

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Ruang Pengelolaan Konten')
        ->assertSee($admin->name)
        ->assertSee('Artikel')
        ->assertSee('Library')
        ->assertSee('Media')
        ->assertSee('Pilih salah satu kartu Artikel, Library, atau Media.')
        ->assertSee('type-card-title', false)
        ->assertSee('meta name="robots" content="noindex, nofollow"', false);
});

it('refuses wrong credentials without revealing account existence', function () {
    User::factory()->create([
        'email' => 'administrator@example.test',
        'password' => 'ValidAdminPassword!2026',
        'is_admin' => true,
    ]);

    $this->post(route('admin.authenticate'), [
        'email' => 'administrator@example.test',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('refuses non-admin credentials even when the password is correct', function () {
    User::factory()->create([
        'email' => 'viewer@example.test',
        'password' => 'ViewerPassword!2026',
        'is_admin' => false,
    ]);

    $this->post(route('admin.authenticate'), [
        'email' => 'viewer@example.test',
        'password' => 'ViewerPassword!2026',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('invalidates the authenticated session when logging out', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->post(route('admin.logout'))
        ->assertRedirect(route('admin.login'));

    $this->assertGuest();
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    $this->get(route('admin.logout'))->assertStatus(405);
});

it('limits repetitive login attempts by identity and IP', function () {
    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->post(route('admin.authenticate'), [
            'email' => 'bruteforce@example.test',
            'password' => 'not-a-valid-password',
        ])->assertSessionHasErrors('email');
    }

    $this->post(route('admin.authenticate'), [
        'email' => 'bruteforce@example.test',
        'password' => 'not-a-valid-password',
    ])->assertStatus(429);
});

it('sends no-store and noindex headers on admin responses', function () {
    $response = $this->get(route('admin.login'))->assertOk();

    expect($response->headers->get('Cache-Control'))->toContain('no-store')
        ->and($response->headers->get('X-Robots-Tag'))->toBe('noindex, nofollow');
});

it('never creates a default admin when seed credentials are absent', function () {
    config()->set('admin.seed_email', null);
    config()->set('admin.seed_password', null);

    $this->seed(AdminSeeder::class);

    expect(User::query()->count())->toBe(0);
});

it('creates a single hashed admin from explicit seeder credentials and is idempotent', function () {
    config()->set('admin.seed_name', 'Pengelola Konten');
    config()->set('admin.seed_email', 'MANAGER@example.test');
    config()->set('admin.seed_password', 'SeededAdminPassword!2026');

    $this->seed(AdminSeeder::class);
    $admin = User::query()->sole();

    expect($admin->email)->toBe('manager@example.test')
        ->and($admin->is_admin)->toBeTrue()
        ->and($admin->name)->toBe('Pengelola Konten')
        ->and(Hash::check('SeededAdminPassword!2026', $admin->password))->toBeTrue()
        ->and($admin->password)->not->toBe('SeededAdminPassword!2026');

    config()->set('admin.seed_password', 'DifferentAdminPassword!2026');
    $this->seed(AdminSeeder::class);

    expect(User::query()->count())->toBe(1)
        ->and(Hash::check('SeededAdminPassword!2026', $admin->fresh()->password))->toBeTrue();
});

it('does not promote an existing normal account through the seeder', function () {
    User::factory()->create(['email' => 'member@example.test', 'is_admin' => false]);
    config()->set('admin.seed_email', 'member@example.test');
    config()->set('admin.seed_password', 'SeededAdminPassword!2026');

    expect(fn () => $this->seed(AdminSeeder::class))
        ->toThrow(RuntimeException::class);
});

it('rejects weak or partial seeder credentials', function (string $email, string $password) {
    config()->set('admin.seed_email', $email);
    config()->set('admin.seed_password', $password);

    expect(fn () => $this->seed(AdminSeeder::class))
        ->toThrow(RuntimeException::class);
})->with([
    'short password' => ['admin@example.test', 'password'],
    'malformed email' => ['not-an-email', 'VeryLongPassword!2026'],
    'only email' => ['admin@example.test', ''],
]);
