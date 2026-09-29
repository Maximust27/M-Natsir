<?php

it('renders the media appearances archive', function () {
    $this->get('/media')
        ->assertOk()
        ->assertSee('MEDIA & APPEARANCES');
});
