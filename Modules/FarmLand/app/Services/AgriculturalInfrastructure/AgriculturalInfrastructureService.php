<?php

namespace Modules\FarmLand\Services\AgriculturalInfrastructure;

use Modules\FarmLand\Models\AgriculturalInfrastructure;

class AgriculturalInfrastructureService
{
    /**
     * Summary of getAll.
     *
     * @return array
     */
    public function getAll(): array
    {
        $data = AgriculturalInfrastructure::with('lands')->paginate(15);
        return [$data];
    }

    /**
     * Summary of getAgricultureInfrastructure.
     *
     * @param mixed $infrastructure
     * @return array
     */
    public function getAgricultureInfrastructure($infrastructure): array
    {
        $data = $infrastructure->load('lands');
        return [$data];
    }

    /**
     * Summary of store.
     *
     * @param mixed $request
     * @return array
     */
    public function store($request): array
    {
        $validatedData = $request->validated();
        $infrastructure = AgriculturalInfrastructure::create($validatedData);

        if (!empty($validatedData['land_ids'])) {
            $infrastructure->lands()->attach($validatedData['land_ids']);
        }
        $data = $infrastructure->load('lands');
        return [$data];
    }

    /**
     * Summary of update.
     *
     * @param mixed $request
     * @param mixed $infrastructure
     * @return array
     */
    public function update($request, $infrastructure): array
    {
        $validatedData = $request->validated();
        $infrastructure->update($validatedData);

        if ($request->has('land_ids')) {
            $infrastructure->lands()->sync($validatedData['land_ids'] ?? []);
        }

        $data = $infrastructure->load('lands');
        return [$data];
    }

    /**
     * Summary of destroy.
     *
     * @param mixed $infrastructure
     * @return array
     */
    public function destroy($infrastructure): mixed
    {
        return $infrastructure->delete();
    }
}
