<?php

namespace Modules\FarmLand\Services\AgriculturalInfrastructure;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\FarmLand\Models\AgriculturalInfrastructure;

class AgriculturalInfrastructureService implements BaseCrudServiceInterface
{
    /**
     * Get all Agricultural Infrastructures.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $cacheKey = "agricultural_infrastructures_all";
        return Cache::remember($cacheKey, now()->addHours(1), function () {
            return AgriculturalInfrastructure::with('lands')->paginate(15);
        });
    }

    /**
     * Get a Agricultural Infrastructure.
     *
     * @param mixed $infrastructure
     * @return Model
     */
    public function get($infrastructure): Model
    {
        $cacheKey = "infrastructure_{$infrastructure->id}";
        return Cache::remember($cacheKey, now()->addHours(1), function () use ($infrastructure) {
            return $infrastructure->load('lands');
        });
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

        if (!empty($data['land_ids'])) {
            $infrastructure->lands()->attach($data['land_ids']);
        }
        $infrastructure->load('lands');
        Cache::forget("agricultural_infrastructures_all");
        Cache::forget("infrastructure_{$infrastructure->id}");

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

        if (isset($data['land_ids'])) {
            $infrastructure->lands()->sync($data['land_ids'] ?? []);
        }
        $infrastructure->load('lands');
        Cache::forget("agricultural_infrastructures_all");
        Cache::forget("infrastructure_{$infrastructure->id}");

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
        Cache::forget("agricultural_infrastructures_all");
        Cache::forget("infrastructure_{$infrastructure->id}");
        return $infrastructure->delete();
    }
}
