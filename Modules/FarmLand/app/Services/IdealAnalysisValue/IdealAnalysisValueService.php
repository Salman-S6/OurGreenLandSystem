<?php

namespace Modules\FarmLand\Services\IdealAnalysisValue;

use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\FarmLand\Models\IdealAnalysisValue;

class IdealAnalysisValueService implements BaseCrudServiceInterface
{
    /**
     * Summary of __construct.
     */
    public function __construct()
    {
    }

    /**
     * Get all Ideal Analysis Values.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $data = IdealAnalysisValue::with('crop')->paginate(15);
        return $data;
    }

    /**
     * Get an Ideal Analysis Value.
     *
     * @param mixed $idealAnalysisValue
     * @return Model
     */
    public function get($idealAnalysisValue): Model
    {
        $data = $idealAnalysisValue->load('crop');
        return $data;
    }

    /**
     * Store Ideal Analysis Value.
     *
     * @param mixed $data
     * @return Model
     */
    public function store($data): Model
    {
        $idealAnalysisValue = IdealAnalysisValue::create($data);
        $idealAnalysisValue->load('crop');

        return $idealAnalysisValue;
    }

    /**
     * Update Ideal Analysis Value.
     *
     * @param mixed $data
     * @param mixed $idealAnalysisValue
     * @return Model
     */
    public function update($data, $idealAnalysisValue): Model
    {
        $idealAnalysisValue->update($data);
        $idealAnalysisValue->load('crop');

        return $idealAnalysisValue;
    }

    /**
     * Delete an Ideal Analysis Value.
     *
     * @param mixed $idealAnalysisValue
     * @return bool
     */
    public function destroy($idealAnalysisValue): bool
    {
        return $idealAnalysisValue->delete();
    }
}
