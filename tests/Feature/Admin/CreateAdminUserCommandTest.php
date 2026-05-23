<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('admin:create creates a new user and reports admin status when email is in ADMIN_EMAILS', function () {
    config()->set('admin.emails', ['boss@example.com']);

    $this->artisan('admin:create', ['name' => 'Boss Person', 'email' => 'boss@example.com'])
        ->expectsQuestion('Password (min 12 characters)', 'correct-horse-battery')
        ->expectsQuestion('Confirm password', 'correct-horse-battery')
        ->expectsOutputToContain('Created admin user "Boss Person"')
        ->expectsOutputToContain('admin access is active')
        ->assertExitCode(0);

    $user = User::query()->where('email', 'boss@example.com')->firstOrFail();

    expect($user->name)->toBe('Boss Person')
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->is_admin)->toBeTrue()
        ->and(Hash::check('correct-horse-battery', $user->password))->toBeTrue();
});

test('admin:create warns when email is not in ADMIN_EMAILS', function () {
    config()->set('admin.emails', ['admin@example.com']);

    $this->artisan('admin:create', ['name' => 'Bystander', 'email' => 'bystander@example.com'])
        ->expectsQuestion('Password (min 12 characters)', 'correct-horse-battery')
        ->expectsQuestion('Confirm password', 'correct-horse-battery')
        ->expectsOutputToContain('not in ADMIN_EMAILS')
        ->assertExitCode(0);

    expect(User::query()->where('email', 'bystander@example.com')->firstOrFail()->is_admin)->toBeFalse();
});

test('admin:create lowercases the email', function () {
    $this->artisan('admin:create', ['name' => 'Mixed Case', 'email' => 'Mixed@Example.com'])
        ->expectsQuestion('Password (min 12 characters)', 'correct-horse-battery')
        ->expectsQuestion('Confirm password', 'correct-horse-battery')
        ->assertExitCode(0);

    expect(User::query()->where('email', 'mixed@example.com')->exists())->toBeTrue();
});

test('admin:create rejects an invalid email', function () {
    $this->artisan('admin:create', ['name' => 'Bad', 'email' => 'not-an-email'])
        ->assertExitCode(1);

    expect(User::query()->count())->toBe(0);
});

test('admin:create rejects a password shorter than 12 characters', function () {
    $this->artisan('admin:create', ['name' => 'Tiny', 'email' => 'tiny@example.com'])
        ->expectsQuestion('Password (min 12 characters)', 'short')
        ->expectsOutputToContain('at least 12 characters')
        ->assertExitCode(1);

    expect(User::query()->count())->toBe(0);
});

test('admin:create rejects mismatched password confirmation', function () {
    $this->artisan('admin:create', ['name' => 'Slip', 'email' => 'slip@example.com'])
        ->expectsQuestion('Password (min 12 characters)', 'correct-horse-battery')
        ->expectsQuestion('Confirm password', 'mismatched-passphrase')
        ->expectsOutputToContain('do not match')
        ->assertExitCode(1);

    expect(User::query()->count())->toBe(0);
});

test('admin:create updates an existing user when confirmed', function () {
    User::factory()->create([
        'email' => 'exists@example.com',
        'name' => 'Old Name',
    ]);

    $this->artisan('admin:create', ['name' => 'New Name', 'email' => 'exists@example.com'])
        ->expectsConfirmation('A user with exists@example.com already exists. Update name and reset password?', 'yes')
        ->expectsQuestion('Password (min 12 characters)', 'new-secret-passphrase')
        ->expectsQuestion('Confirm password', 'new-secret-passphrase')
        ->expectsOutputToContain('Updated admin user "New Name"')
        ->assertExitCode(0);

    $user = User::query()->where('email', 'exists@example.com')->firstOrFail();

    expect($user->name)->toBe('New Name')
        ->and(Hash::check('new-secret-passphrase', $user->password))->toBeTrue();
});

test('admin:create aborts when user declines to update an existing user', function () {
    User::factory()->create(['email' => 'exists@example.com', 'name' => 'Old Name']);

    $this->artisan('admin:create', ['name' => 'New Name', 'email' => 'exists@example.com'])
        ->expectsConfirmation('A user with exists@example.com already exists. Update name and reset password?', 'no')
        ->expectsOutputToContain('Aborted')
        ->assertExitCode(1);

    expect(User::query()->where('email', 'exists@example.com')->firstOrFail()->name)->toBe('Old Name');
});
