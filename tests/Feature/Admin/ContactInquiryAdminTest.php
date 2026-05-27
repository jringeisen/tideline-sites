<?php

use App\Models\ContactInquiry;
use App\Models\User;

beforeEach(function () {
    config()->set('admin.emails', ['admin@example.com']);
    $this->admin = User::factory()->create([
        'email' => 'admin@example.com',
        'email_verified_at' => now(),
    ]);
});

test('guests are redirected from admin contact inquiries', function () {
    $this->get('/admin/contact-inquiries')->assertRedirect();
});

test('non-admin authenticated users get 403 on admin contact inquiries', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)
        ->get('/admin/contact-inquiries')
        ->assertForbidden();
});

test('admin can list inquiries newest-first', function () {
    $old = ContactInquiry::factory()->create(['created_at' => now()->subDay()]);
    $new = ContactInquiry::factory()->create(['created_at' => now()]);

    $response = $this->actingAs($this->admin)
        ->get('/admin/contact-inquiries')
        ->assertOk();

    $rows = $response->viewData('page')['props']['inquiries']['data'];
    expect(collect($rows)->pluck('id')->all())->toBe([$new->id, $old->id]);
});

test('search filter narrows results by name, email, or message', function () {
    ContactInquiry::factory()->create(['name' => 'Alice Lighthouse', 'email' => 'alice@example.com']);
    ContactInquiry::factory()->create(['name' => 'Bob Bayside', 'email' => 'bob@example.com', 'message' => 'lighthouse rental site']);
    ContactInquiry::factory()->create(['name' => 'Carol Cove', 'email' => 'carol@example.com', 'message' => 'kayak tours']);

    $response = $this->actingAs($this->admin)
        ->get('/admin/contact-inquiries?q=lighthouse')
        ->assertOk();

    $rows = $response->viewData('page')['props']['inquiries']['data'];
    expect(count($rows))->toBe(2)
        ->and(collect($rows)->pluck('name')->all())->toContain('Alice Lighthouse', 'Bob Bayside');
});

test('source filter narrows results to seo assessment submissions', function () {
    ContactInquiry::factory()->create(['name' => 'Contact One']);
    $seo = ContactInquiry::factory()->seoAssessment()->create(['name' => 'SEO One']);

    $response = $this->actingAs($this->admin)
        ->get('/admin/contact-inquiries?source=seo_assessment')
        ->assertOk();

    $rows = $response->viewData('page')['props']['inquiries']['data'];
    expect(count($rows))->toBe(1)
        ->and($rows[0]['id'])->toBe($seo->id)
        ->and($rows[0]['source'])->toBe('seo_assessment');
});

test('unread filter hides read inquiries', function () {
    $unread = ContactInquiry::factory()->create();
    ContactInquiry::factory()->create(['read_at' => now()]);

    $response = $this->actingAs($this->admin)
        ->get('/admin/contact-inquiries?unread=1')
        ->assertOk();

    $rows = $response->viewData('page')['props']['inquiries']['data'];
    expect(count($rows))->toBe(1)
        ->and($rows[0]['id'])->toBe($unread->id);
});

test('admin can view an inquiry', function () {
    $inquiry = ContactInquiry::factory()->create(['name' => 'Dana Dune']);

    $this->actingAs($this->admin)
        ->get("/admin/contact-inquiries/{$inquiry->id}")
        ->assertOk();
});

test('the inquiry show page receives the veteran flag', function () {
    $inquiry = ContactInquiry::factory()->veteran()->create();

    $response = $this->actingAs($this->admin)
        ->get("/admin/contact-inquiries/{$inquiry->id}")
        ->assertOk();

    expect($response->viewData('page')['props']['inquiry']['is_veteran'])->toBeTrue();
});

test('viewing an inquiry does not auto-mark it as read', function () {
    $inquiry = ContactInquiry::factory()->create(['read_at' => null]);

    $this->actingAs($this->admin)->get("/admin/contact-inquiries/{$inquiry->id}");

    expect($inquiry->fresh()->read_at)->toBeNull();
});

test('admin can mark an inquiry as read', function () {
    $inquiry = ContactInquiry::factory()->create(['read_at' => null]);

    $this->actingAs($this->admin)
        ->patch("/admin/contact-inquiries/{$inquiry->id}/read")
        ->assertRedirect();

    expect($inquiry->fresh()->read_at)->not->toBeNull();
});

test('admin can mark an inquiry as unread', function () {
    $inquiry = ContactInquiry::factory()->create(['read_at' => now()]);

    $this->actingAs($this->admin)
        ->patch("/admin/contact-inquiries/{$inquiry->id}/unread")
        ->assertRedirect();

    expect($inquiry->fresh()->read_at)->toBeNull();
});

test('admin can mark an inquiry as spam', function () {
    $inquiry = ContactInquiry::factory()->create(['is_spam' => false]);

    $this->actingAs($this->admin)
        ->patch("/admin/contact-inquiries/{$inquiry->id}/spam")
        ->assertRedirect();

    expect($inquiry->fresh()->is_spam)->toBeTrue();
});

test('admin can mark an inquiry as not spam', function () {
    $inquiry = ContactInquiry::factory()->spam()->create();

    $this->actingAs($this->admin)
        ->patch("/admin/contact-inquiries/{$inquiry->id}/not-spam")
        ->assertRedirect();

    expect($inquiry->fresh()->is_spam)->toBeFalse();
});

test('the inquiry show page receives the spam flag and ip address', function () {
    $inquiry = ContactInquiry::factory()->spam()->create(['ip_address' => '203.0.113.42']);

    $props = $this->actingAs($this->admin)
        ->get("/admin/contact-inquiries/{$inquiry->id}")
        ->assertOk()
        ->viewData('page')['props']['inquiry'];

    expect($props['is_spam'])->toBeTrue()
        ->and($props['ip_address'])->toBe('203.0.113.42');
});

test('non-admin cannot mark inquiries as spam', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $inquiry = ContactInquiry::factory()->create();

    $this->actingAs($user)
        ->patch("/admin/contact-inquiries/{$inquiry->id}/spam")
        ->assertForbidden();

    expect($inquiry->fresh()->is_spam)->toBeFalse();
});

test('admin can delete an inquiry', function () {
    $inquiry = ContactInquiry::factory()->create();

    $this->actingAs($this->admin)
        ->delete("/admin/contact-inquiries/{$inquiry->id}")
        ->assertRedirect('/admin/contact-inquiries');

    expect(ContactInquiry::find($inquiry->id))->toBeNull();
});

test('non-admin cannot mark inquiries read or delete them', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $inquiry = ContactInquiry::factory()->create();

    $this->actingAs($user)
        ->patch("/admin/contact-inquiries/{$inquiry->id}/read")
        ->assertForbidden();

    $this->actingAs($user)
        ->delete("/admin/contact-inquiries/{$inquiry->id}")
        ->assertForbidden();

    expect($inquiry->fresh())->not->toBeNull();
});
