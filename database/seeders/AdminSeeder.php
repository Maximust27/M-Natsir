<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.seed_email');
        $password = config('admin.seed_password');

        // A routine db:seed must never create a predictable admin account.
        if (blank($email) && blank($password)) {
            $this->command?->warn('AdminSeeder dilewati: isi ADMIN_SEED_EMAIL dan ADMIN_SEED_PASSWORD saat membuat akun.');

            return;
        }

        if (! is_string($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)
            || ! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException('Admin membutuhkan email yang valid dan kata sandi minimal 12 karakter.');
        }

        $email = strtolower(trim($email));
        $name = trim((string) config('admin.seed_name', 'Administrator'));

        $existing = User::query()->where('email', $email)->first();

        if ($existing) {
            if (! $existing->is_admin) {
                throw new RuntimeException('Email sudah dipakai akun non-admin. Pilih email admin yang berbeda.');
            }

            // Idempotent and safe: seeding again must not rotate an existing password.
            $this->command?->info('Admin sudah tersedia; data dan kata sandi tidak diubah.');

            return;
        }

        $admin = new User;
        // is_admin intentionally remains outside the model's Fillable allowlist.
        $admin->forceFill([
            'name' => $name !== '' ? $name : 'Administrator',
            'email' => $email,
            'password' => $password,
            'is_admin' => true,
            'email_verified_at' => now(),
        ])->save();

        $this->command?->info('Akun admin berhasil dibuat. Jaga kerahasiaan kata sandi.');
    }
}
