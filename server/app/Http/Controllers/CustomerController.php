<?php

namespace App\Http\Controllers;

use App\Core\UseCases\Customer\CreateCustomerUseCase;
use App\Core\UseCases\Customer\GetAllCustomers;
use App\Core\UseCases\Customer\GetCustomerById;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Support\HttpResponse;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(
        private GetAllCustomers $getAllCustomersUseCase,
        private GetCustomerById $getCustomerByIdUseCase,
        private CreateCustomerUseCase $createCustomerUseCase,
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

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $message = $this->createCustomerUseCase->execute($request->all());
        return $this->httpResponse->message($message, 201);
    }
}
