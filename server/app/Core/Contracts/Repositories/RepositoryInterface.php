<?php

namespace App\Core\Contracts\Repositories;

use App\Core\Contracts\EntityInterface;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function toEntity(Model $model): EntityInterface;
}
