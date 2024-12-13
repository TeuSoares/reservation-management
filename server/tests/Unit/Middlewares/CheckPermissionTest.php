<?php

use App\Http\Middleware\CheckPermission;
use App\Infrastructure\Persistence\Repositories\VerifiedCodeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\MockInterface;

beforeEach(function () {
    $mockVerifiedCodeRepository = $this->mock(VerifiedCodeRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('checkVerifiedCode')
            ->andReturn(true);
    });

    $this->checkPermissionMiddleware = new CheckPermission($mockVerifiedCodeRepository);

    $this->request = new Request();
    $this->next = function () {
        return new Response();
    };
});

test('it should pass successfully if the verification code is valid and the origin is correct', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://localhost');
    $request->cookies->set('verification_code', 123456);
    $request->cookies->set('customer_id', 1);

    $response = $this->checkPermissionMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
});

test('it should return 403 if verification code is not valid', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://localhost');
    $request->cookies->set('verification_code', 123456);
    $request->cookies->set('customer_id', 1);

    $mockVerifiedCodeRepository = $this->mock(VerifiedCodeRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('checkVerifiedCode')
            ->once()
            ->andReturn(false);
    });

    $checkPermissionMiddleware = new CheckPermission($mockVerifiedCodeRepository);

    $response = $checkPermissionMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
});

test('it should return 401 if verification code and customer_id is not valid', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://localhost');

    $response = $this->checkPermissionMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
});

test('it should return 403 if origin is not correct', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://example.com');
    $request->cookies->set('verification_code', 123456);
    $request->cookies->set('customer_id', 1);

    $response = $this->checkPermissionMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
});

test('it should pass successfully if a user is logged in', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://localhost');

    $mockedUser = (object) [
        'id' => 1,
        'is_admin' => true,
    ];

    $request->setUserResolver(function () use ($mockedUser) {
        return $mockedUser;
    });

    $response = $this->checkPermissionMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
});
