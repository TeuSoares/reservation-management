<?php

namespace App\Http\Controllers;

use App\Core\UseCases\Reservation\GetAllReservationUseCase;
use App\Core\UseCases\Reservation\GetReservationByIdUseCase;
use App\Http\Requests\Reservation\ReservationFilterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Support\HttpResponse;

class ReservationController extends Controller
{
    public function __construct(
        private HttpResponse $httpResponse,
        private GetAllReservationUseCase $getAllReservationUseCase,
        private GetReservationByIdUseCase $getReservationByIdUseCase
    ) {}

    public function index(ReservationFilterRequest $request): JsonResponse
    {
        return $this->httpResponse->paginated($this->getAllReservationUseCase->execute($request->all()));
    }

    public function show(int $id): JsonResponse
    {
        return $this->httpResponse->data($this->getReservationByIdUseCase->execute($id));
    }

    public function store(Request $request): JsonResponse
    {
        return $this->httpResponse->message('');
    }

    public function update(Request $request): JsonResponse
    {
        return $this->httpResponse->message('');
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->httpResponse->message('');
    }
}
