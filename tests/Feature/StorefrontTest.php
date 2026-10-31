<?php

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the French storefront is available from the home page', function () {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Trésors naturels du Maroc')
        ->assertSee("Miel d'Eucalyptus");
});

test('the storefront uses the language selected by the administrator', function () {
    SiteSetting::query()->create(['key' => 'site_locale', 'value' => 'ar']);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('كنوز طبيعية من المغرب')
        ->assertSee('dir="rtl"', false);
});
