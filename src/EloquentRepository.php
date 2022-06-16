<?php

namespace Nos\BaseRepository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Nos\BaseRepository\Interfaces\EloquentRepositoryInterface;

abstract class EloquentRepository implements EloquentRepositoryInterface
{
    protected string $class = Model::class;
    private ?Model $model = null;

    public function all(): Collection
    {
        return $this->getModel()->all();
    }

    public function getModel(): Model
    {
        if (!$this->model) {
            $this->model = new $this->class();
        }

        return $this->model;
    }

    public function create(array $data): ?Model
    {
        return $this->getModel()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->getModel()->findOrFail($id)->update($data);
    }

    public function find(int $id): ?Model
    {
        return $this->getModel()->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        return $this->getModel()->findOrFail($id)->delete();
    }

    public function query(): ?Builder
    {
        return $this->getModel()->newQuery();
    }
}
