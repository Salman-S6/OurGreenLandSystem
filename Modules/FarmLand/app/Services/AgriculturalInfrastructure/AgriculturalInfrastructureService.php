<?php

namespace Modules\FarmLand\Services\AgriculturalInfrastructure;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\FarmLand\Models\AgriculturalInfrastructure;

class AgriculturalInfrastructureService implements BaseCrudServiceInterface
{
    /**
     * Summary of __construct.
     *
     */
    public function __construct()
    {
    }

    /**
     * Get all Agricultural Infrastructures.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $data = AgriculturalInfrastructure::with('lands')->paginate(15);
        return $data;
    }

    /**
     * Get a Agricultural Infrastructure.
     *
     * @param mixed $infrastructure
     * @return Model
     */
    public function get($infrastructure): Model
    {
        $infrastructure->load('lands');
        return $infrastructure;
    }

    /**
     * Store Agricultural Infrastructure.
     *
     * @param mixed $data
     * @return Model
     */
    public function store($data): Model
    {
        $infrastructure = AgriculturalInfrastructure::create($data);

        if (!empty($data['land_is'])) {
            $infrastructure->lands()->attach($data['land_is']);
        }
        $infrastructure->load('lands');
        return $infrastructure;
    }

    /**
     * Update Agricultural Infrastructure.
     *
     * @param mixed $data
     * @param mixed $infrastructure
     * @return Model
     */
    public function update($data, $infrastructure): Model
    {
        $infrastructure->update($data);

        if ($data->has('land_ids')) {
            $infrastructure->lands()->sync($data['land_is'] ?? []);
        }

        $infrastructure->load('lands');
        return $infrastructure;
    }

    /**
     * Delete a Agricultural Infrastructure.
     *
     * @param mixed $infrastructure
     * @return bool
     */
    public function destroy($infrastructure): bool
    {
        return $infrastructure->delete();
    }
}
