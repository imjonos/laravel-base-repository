<?php

namespace Nos\BaseRepository\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    public function find(int $id): ?Model;
    public function count(): int;
    public function all(): Collection;
    public function query(): ?Builder;
    public function create(array $data): ?Model;

    /**
     * Update a record by ID
     *
     * @param int $id The ID of the record
     * @param array $data Data to update
     * @return bool Whether the update was successful (1+ rows affected)
     */
    public function update(int $id, array $data): bool;
    public function exists(int $id): bool;
    
    /**
     * Delete a record by ID
     *
     * @param int $id The ID of the record
     * @return bool Whether the deletion was successful
     */
    public function delete(int $id): bool;

    /**
     * Insert multiple records
     *
     * @param array $data Array of data to insert
     * @return bool Whether the insertion was successful
     */
    public function insert(array $data): bool;

    /**
     * Update or insert multiple records (upsert)
     *
     * @param array $values Data to insert or update
     * @param array|string $uniqueBy Unique fields (conflict target)
     * @param array|null $update Fields to update on conflict
     * @return int Number of inserted or updated records
     */
    public function upsert(array $values, array|string $uniqueBy, ?array $update = null): int;
}
