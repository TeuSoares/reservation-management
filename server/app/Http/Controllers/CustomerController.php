<?php

namespace App\Http\Controllers;

use App\Core\UseCases\Customer\CheckCodeUseCase;
use App\Core\UseCases\Customer\CheckRecordUseCase;
use App\Core\UseCases\Customer\CreateCustomerUseCase;
use App\Core\UseCases\Customer\DeleteCustomerUseCase;
use App\Core\UseCases\Customer\GetAllCustomers;
use App\Core\UseCases\Customer\GetCustomerById;
use App\Core\UseCases\Customer\UpdateCustomerUseCase;
use App\Http\Requests\Customer\CheckCodeRequest;
use App\Http\Requests\Customer\CheckRecordRequest;
use App\Http\Requests\Customer\MutationCustomerRequest;
use App\Support\HttpResponse;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(
        private HttpResponse $httpResponse,
        private GetAllCustomers $getAllCustomersUseCase,
        private GetCustomerById $getCustomerByIdUseCase,
        private CreateCustomerUseCase $createCustomerUseCase,
        private CheckRecordUseCase $checkRecordUseCase,
        private CheckCodeUseCase $checkCodeUseCase,
        private DeleteCustomerUseCase $deleteCustomerUseCase,
        private UpdateCustomerUseCase $updateCustomerUseCase
    ) {}

    public function index(): JsonResponse
    {
        return $this->httpResponse->paginated($this->getAllCustomersUseCase->execute());
    }

    public function show(int $id): JsonResponse
    {
        return $this->httpResponse->data($this->getCustomerByIdUseCase->execute($id));
    }

    public function store(MutationCustomerRequest $request): JsonResponse
    {
        $customer = $this->createCustomerUseCase->execute($request->all());
        return $this->httpResponse->message('Customer created successfully', 201, $customer);
    }

    public function update(MutationCustomerRequest $request, int $id): JsonResponse
    {
        $customer = $this->updateCustomerUseCase->execute($request->all(), $id);
        return $this->httpResponse->message('Customer updated successfully', 200, $customer);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->deleteCustomerUseCase->execute($id);
        return $this->httpResponse->message('Customer deleted successfully', 200);
    }

    public function checkRecord(CheckRecordRequest $request): JsonResponse
    {
        $data = $this->checkRecordUseCase->execute($request->input('cpf'));
        return $this->httpResponse->message('The customer exists', 200, $data);
    }

    public function checkCode(CheckCodeRequest $request): JsonResponse
    {
        $code = $this->checkCodeUseCase->execute($request->input('code'), $request->input('id'));
        return $this->httpResponse->message('The code is valid', 200, ['code' => $code]);
    }
}
