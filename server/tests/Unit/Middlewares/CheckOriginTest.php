<?php

use App\Http\Middleware\CheckOrigin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->checkOriginMiddleware = new CheckOrigin;

    $this->request = new Request();
    $this->next = function () {
        return new Response();
    };
});

test('it should pass successfully if the verification code is valid and the origin is correct', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://localhost');

    $response = $this->checkOriginMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
});

test('it should return 403 if origin is not correct', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://example.com');

    $response = $this->checkOriginMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
});
