<?php

namespace Nos\BaseRepository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    public function count(): int
    {
        return $this->getModel()->count();
    }

    public function create(array $data): ?Model
    {
        return $this->getModel()->create($data);
    }

    public function insert(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        return $this->getModel()->newModelQuery()->insert($data);
    }

    public function upsert(array $values, array|string $uniqueBy, ?array $update = null): int
    {
        if (empty($values)) {
            return 0;
        }

        return $this->getModel()->upsert($values, $uniqueBy, $update ?? []);
    }

    public function update(int $id, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        return $this->getModel()->where('id', $id)->update($data) > 0;
    }

    public function exists(int $id): bool
    {
        return $this->getModel()->whereKey($id)->exists();
    }

    public function find(int $id): ?Model
    {
        return $this->getModel()->find($id);
    }

    public function delete(int $id): bool
    {
        return $this->getModel()->whereKey($id)->delete() > 0;
    }

    public function query(): Builder
    {
        return $this->getModel()->newQuery();
    }
}
