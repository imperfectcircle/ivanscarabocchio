<?php

test('the home page renders every main section', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('<html lang="it">', false)
        ->assertSee('id="libro"', false)
        ->assertSee('id="racconti"', false)
        ->assertSee('id="diario"', false)
        ->assertSee('id="eventi"', false)
        ->assertSee('id="rassegna-stampa"', false)
        ->assertSee('id="titolo-newsletter"', false);
});

test('external purchase links do not leak the opener or referrer', function () {
    $response = $this->get(route('home'));

    $html = $response->getContent();
    preg_match_all('/<a\b[^>]*target="_blank"[^>]*>/', $html, $externalLinks);

    expect($externalLinks[0])->not->toBeEmpty();

    foreach ($externalLinks[0] as $link) {
        expect($link)->toContain('rel="noopener noreferrer"');
    }
});
