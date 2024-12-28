<?php

use function Pest\Laravel\getJson;

it('deveria retorna status code 200', function () {
    getJson('/')->assertOk();
});
