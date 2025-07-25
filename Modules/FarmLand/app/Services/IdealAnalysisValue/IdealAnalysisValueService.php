<?php

namespace Modules\FarmLand\Services\IdealAnalysisValue;

use Modules\FarmLand\Models\IdealAnalysisValue;

class IdealAnalysisValueService
{
    /**
     * Summary of getAll.
     *
     * @return array
     */
    public function getAll(): array
    {
        $data = IdealAnalysisValue::with('crop')->paginate(15);
        return [$data];
    }

    /**
     * Summary of getIdealAnalysisValue.
     *
     * @param mixed $idealAnalysisValue
     * @return array
     */
    public function getIdealAnalysisValue($idealAnalysisValue): array
    {
        $data = $idealAnalysisValue->load('crop');
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
        $idealAnalysisValue = IdealAnalysisValue::create($request->validated());

        $data = $idealAnalysisValue->load('crop');
        return [$data];
    }

    /**
     * Summary of update.
     *
     * @param mixed $request
     * @param mixed $idealAnalysisValue
     * @return array
     */
    public function update($request, $idealAnalysisValue): array
    {
        $idealAnalysisValue->update($request->validated());

        $data = $idealAnalysisValue->load('crop');
        return [$data];
    }

    /**
     * Summary of destroy.
     *
     * @param mixed $idealAnalysisValue
     * @return array
     */
    public function destroy($idealAnalysisValue): mixed
    {
        return $idealAnalysisValue->delete();
    }
}
