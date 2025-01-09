<?php

namespace App\Infrastructure\Persistence\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Core\Contracts\Repositories\ReservationRepositoryInterface;
use App\Core\Domain\Entities\Reservation as ReservationEntity;
use App\Infrastructure\Persistence\Models\Reservation;
use Carbon\Carbon;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(
        private Reservation $model,
        private CustomerRepository $customerRepository
    ) {}

    public function getAll(object $params): object
    {
        $id = $params->id ?? null;
        $customer_id = $params->customer_id ?? null;
        $booking_date = $params->booking_date ?? null;
        $canceled = $params->canceled ?? null;

        $query = $this->model
            ->when($id, function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($customer_id, function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($booking_date, function ($query) use ($booking_date) {
                $query->whereDate('booking_date', $booking_date);
            })
            ->when($canceled, function ($query) use ($canceled) {
                $query->where('canceled', $canceled);
            });

        $paginated = $query->paginate(10);

        $paginated->getCollection()->transform(fn($reservation) => $this->toEntity($reservation));

        return (object) $paginated->toArray();
    }

    public function findById(int $id): ?ReservationEntity
    {
        $reservation = $this->model->find($id);

        return $reservation ? $this->toEntity($reservation) : null;
    }

    public function create(array $data): ReservationEntity
    {
        $reservation = $this->model->create($data);

        return $this->toEntity($reservation);
    }

    public function update(int $id, array $data): void
    {
        $reservation = $this->model->findOrFail($id);
        $reservation->update($data);
    }

    public function delete(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    public function toEntity(Model $model): ReservationEntity
    {
        $customerEntity = $this->customerRepository->toEntity($model->customer);

        return new ReservationEntity(
            $model->id,
            $model->customer_id,
            Carbon::parse($model->booking_date),
            $model->number_people,
            $model->canceled,
            $customerEntity
        );
    }
}
