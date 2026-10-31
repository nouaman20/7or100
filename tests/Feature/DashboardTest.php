<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the French dashboard is available from its dedicated route', function () {
    $response = $this->actingAs(User::factory()->create())->get('/dashboard');

    $response
        ->assertOk()
        ->assertSee('Tableau de bord')
        ->assertSee('MielNature');
});

test('an authenticated administrator can change the global site language', function () {
    $administrator = User::factory()->create();

    $this->actingAs($administrator)
        ->put('/dashboard/site-language', ['locale' => 'ar'])
        ->assertRedirect('/dashboard');

    expect(SiteSetting::locale())->toBe('ar');

    $this->get('/')
        ->assertOk()
        ->assertSee('كنوز طبيعية من المغرب')
        ->assertSee('dir="rtl"', false);
});
