<?php


namespace Modules\Resources\Services;

use App\Interfaces\BaseCrudServiceInterface;
use Modules\Resources\Models\SupplierRating;
use Illuminate\Database\Eloquent\Model;

class SupplierRatingService implements BaseCrudServiceInterface
{


    /**
     * Get all supplier ratings with optional filters and relations.
     *
     * @param array $filters
     * @return iterable<Model>
     */
    public function getAll(array $filters = []): iterable
    {
    return SupplierRating::all();
    }

    /**
     * Get a specific supplier rating.
     *
     * @param Model $model
     * @return Model
     */
    public function get(Model $model): SupplierRating
    {
                /** @var SupplierRating $model */
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
         return SupplierRating::create($data);
    }

    /**
     * Update an existing supplier rating.
     *
     * @param array $data
     * @param Model $model
     * @return Model
     */
    public function update(array $data, Model $model): SupplierRating
    {
        /** @var SupplierRating $rating */
        $rating = $model;
        $rating->update($data);

    return $rating->fresh(['supplier', 'reviewer']) ?? $rating;
    }

    /**
     * Delete a supplier rating.
     *
     * @param Model $rating
     * @return bool
     */
    public function destroy(Model $rating) :bool
    {
    /** @var SupplierRating $rating */
    return (bool) $rating->delete();
    }
}
