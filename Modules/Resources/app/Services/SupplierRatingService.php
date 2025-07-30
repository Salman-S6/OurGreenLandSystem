<?php


namespace Modules\Resources\Services;

use App\Interfaces\BaseCrudServiceInterface;
use Modules\Resources\Models\SupplierRating;
use Illuminate\Database\Eloquent\Model;

class SupplierRatingService implements BaseCrudServiceInterface
{
    protected SupplierRating $model;

    public function __construct(SupplierRating $model)
    {
        $this->model = $model;
    }

    /**
     * Get all supplier ratings with optional filters and relations.
     *
     * @param array $filters
     * @return iterable<Model>
     */
    public function getAll(array $filters = []): iterable
    {
        $query = $this->model->query();

        if (isset($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        return $query->with(['supplier', 'reviewer'])->latest()->get();
    }

    /**
     * Get a specific supplier rating.
     *
     * @param Model $model
     * @return Model
     */
    public function get(Model $model): Model
    {
        return $model->load(['supplier', 'reviewer']);
    }

    /**
     * Create a new supplier rating.
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing supplier rating.
     *
     * @param array $data
     * @param Model $model
     * @return Model
     */
    public function update(array $data, Model $model): Model
    {
        $model->update($data);
        return $model;
    }

    /**
     * Delete a supplier rating.
     *
     * @param Model $model
     * @return bool
     */
    public function destroy(Model $model): bool
    {
        return $model->delete();
    }
}
