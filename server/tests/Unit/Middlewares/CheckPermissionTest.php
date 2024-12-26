<?php

use App\Http\Middleware\CheckPermission;
use App\Infrastructure\Persistence\Repositories\VerificationCodeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\MockInterface;

beforeEach(function () {
    $mockVerificationCodeRepository = $this->mock(VerificationCodeRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('checkVerifiedCode')
            ->andReturn(true);
    });

    $this->checkPermissionMiddleware = new CheckPermission($mockVerificationCodeRepository);

    $this->request = new Request();
    $this->next = function () {
        return new Response();
    };
});

test('it should return 403 if verification code is not valid', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://localhost');
    $request->cookies->set('verification_code', 123456);
    $request->id = 1;

    $mockVerificationCodeRepository = $this->mock(VerificationCodeRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('checkVerifiedCode')
            ->once()
            ->andReturn(false);
    });

    $checkPermissionMiddleware = new CheckPermission($mockVerificationCodeRepository);

    $response = $checkPermissionMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
});

test('it should return 401 if verification code and customer_id is not valid', function () {
    $request = $this->request;

    $request->headers->set('origin', 'http://localhost');

    $response = $this->checkPermissionMiddleware->handle($request, $this->next);

    $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
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
