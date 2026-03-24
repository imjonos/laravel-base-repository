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

        if (empty($uniqueBy)) {
           return 0;
        }

        $result = $this->getModel()->upsert($values, $uniqueBy, $update ?? []);

        if ($result > 0 && $this->isHasScoutSearchableTrait()){
            $whereValues = collect($values)->map(function ($item) use  ($uniqueBy) {
                $values = [];
                $uniqueByValues = (is_array($uniqueBy)) ? $uniqueBy : [$uniqueBy];
                foreach ($uniqueByValues as $value) {
                    $itemValue = $item[$value] ?? null;
                    if (is_null($itemValue)) {
                        continue;
                    }

                    $values[] = $itemValue;
                }

                return $values;
            })->toArray();

            $this->query()->whereIn($uniqueBy, $whereValues)->searchable();
        }

        return $result;
    }

    public function update(int $id, array $data): bool
    {
        if ($id < 1) {
            return false;
        }

        if (empty($data)) {
            return false;
        }

        $result = $this->getModel()->where('id', $id)->update($data) > 0;

        if (!$result) {
            return false;
        }

        if ($this->isHasScoutSearchableTrait()) {
            $this->find($id)?->searchable();
        }

        return true;
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
        if ($id < 1) {
            return false;
        }

        if (!$this->isHasScoutSearchableTrait()) {
            return $this->getModel()->whereKey($id)->delete() > 0;
        }

        return $this->find($id)?->delete() ?? false;
    }

    public function query(): Builder
    {
        return $this->getModel()->newQuery();
    }

    private function isHasScoutSearchableTrait(): bool
    {
        return in_array(
            \Laravel\Scout\Searchable::class,
            class_uses_recursive($this->class)
        );
    }
}
