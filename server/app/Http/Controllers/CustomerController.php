<?php

namespace App\Http\Controllers;

use App\Core\UseCases\Customer\GetAllCustomers;
use App\Core\UseCases\Customer\GetCustomerById;
use App\Support\HttpResponse;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(
        private GetAllCustomers $getAllCustomersUseCase,
        private GetCustomerById $getCustomerByIdUseCase,
        private HttpResponse $httpResponse
    ) {}

    public function index(): JsonResponse
    {
        return $this->httpResponse->paginated($this->getAllCustomersUseCase->execute());
    }

    public function show(int $id): JsonResponse
    {
        return $this->httpResponse->data($this->getCustomerByIdUseCase->execute($id));
    }
}
