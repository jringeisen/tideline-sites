<?php

use App\Models\User;

test('is_admin is true when user email is in the admin emails config', function () {
    config()->set('admin.emails', ['admin@example.com', 'other@example.com']);

    $user = User::factory()->create(['email' => 'admin@example.com']);

    expect($user->is_admin)->toBeTrue();
});

test('is_admin is case-insensitive', function () {
    config()->set('admin.emails', ['Admin@Example.com']);

    $user = User::factory()->create(['email' => 'admin@example.com']);

    expect($user->is_admin)->toBeTrue();
});

test('is_admin is false when user email is not in the admin emails config', function () {
    config()->set('admin.emails', ['admin@example.com']);

    $user = User::factory()->create(['email' => 'someone@example.com']);

    expect($user->is_admin)->toBeFalse();
});

test('is_admin is false when admin emails is empty', function () {
    config()->set('admin.emails', []);

    $user = User::factory()->create(['email' => 'anyone@example.com']);

    expect($user->is_admin)->toBeFalse();
});

test('is_admin is appended to the user JSON', function () {
    config()->set('admin.emails', ['admin@example.com']);

    $user = User::factory()->create(['email' => 'admin@example.com']);

    expect($user->toArray())->toHaveKey('is_admin', true);
});
