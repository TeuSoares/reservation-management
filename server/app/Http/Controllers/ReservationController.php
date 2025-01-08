<?php

namespace App\Http\Controllers;

use App\Core\UseCases\Reservation\DeleteReservationUseCase;
use App\Core\UseCases\Reservation\GetAllReservationUseCase;
use App\Core\UseCases\Reservation\GetReservationByIdUseCase;
use App\Core\UseCases\Reservation\StoreReservationUseCase;
use App\Core\UseCases\Reservation\UpdateReservationUseCase;
use App\Http\Requests\Reservation\MutationReservationRequest;
use App\Http\Requests\Reservation\ReservationFilterRequest;
use Illuminate\Http\JsonResponse;
use App\Support\HttpResponse;

class ReservationController extends Controller
{
    public function __construct(
        private HttpResponse $httpResponse,
        private GetAllReservationUseCase $getAllReservationUseCase,
        private GetReservationByIdUseCase $getReservationByIdUseCase,
        private StoreReservationUseCase $storeReservationUseCase,
        private UpdateReservationUseCase $updateReservationUseCase,
        private DeleteReservationUseCase $deleteReservationUseCase
    ) {}

    public function index(ReservationFilterRequest $request): JsonResponse
    {
        return $this->httpResponse->paginated($this->getAllReservationUseCase->execute($request->all()));
    }

    public function show(int $id): JsonResponse
    {
        return $this->httpResponse->data($this->getReservationByIdUseCase->execute($id));
    }

    public function store(MutationReservationRequest $request): JsonResponse
    {
        $this->storeReservationUseCase->execute($request->all());
        return $this->httpResponse->message('The reservation was created successfully', 201);
    }

    public function update(MutationReservationRequest $request, int $id): JsonResponse
    {
        $this->updateReservationUseCase->execute($id, $request->all());
        return $this->httpResponse->message('The reservation was updated successfully', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->deleteReservationUseCase->execute($id);
        return $this->httpResponse->message('The reservation was deleted successfully', 200);
    }
}
