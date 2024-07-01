<?php

uses(
    Tests\TestCase::class,
// Illuminate\Foundation\Testing\RefreshDatabase::class,
);
it('gives back success on ', function (){
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

it('gives back second home test', function (){
    $response = $this->get('/');

    $response->assertStatus(200);
});


it('gives back fifth home test', function (){
    $response = $this->get('/');

    $response->assertStatus(200);
});
