<?php

namespace App\Http\Controllers;

use App\Core\UseCases\Customer\GetAllCustomers;
use App\Core\UseCases\Customer\GetCustomerById;
use App\Support\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    use HttpResponse;

    public function __construct(
        private GetAllCustomers $getAllCustomersUseCase,
        private GetCustomerById $getCustomerByIdUseCase
    ) {}

    public function index(): JsonResponse
    {
        return $this->paginatedResponse($this->getAllCustomersUseCase->execute());
    }

    public function show(int $id): JsonResponse
    {
        $customer = $this->getCustomerByIdUseCase->execute($id);

        if (!$customer) {
            return $this->errorResponse(['Customer not found'], 404);
        }

        return $this->successResponse(data: $customer);
    }
}
