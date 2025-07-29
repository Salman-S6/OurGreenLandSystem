<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Interfaces\BaseCrudServiceInterface;
use Modules\CropManagement\Http\Requests\PestDiseaseCase\StorePestDiseaseCaseRequest;
use Modules\CropManagement\Http\Requests\PestDiseaseCase\UpdatePestDiseaseCaseRequest;
use Modules\CropManagement\Interfaces\Crops\PestDiseaseCasesInterface;
use Modules\CropManagement\Models\PestDiseaseCase;

class PestDiseaseCaseController extends Controller
{
    protected PestDiseaseCasesInterface  $pestDiseaseCaseService;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Interfaces\Crops\PestDiseaseCasesInterface $pestDiseaseCaseService
     */
    public function __construct(PestDiseaseCasesInterface $pestDiseaseCaseService)
    {
        $this->pestDiseaseCaseService = $pestDiseaseCaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cases = $this->pestDiseaseCaseService->getAll();

        return ApiResponse::success(
            [$cases],
            'Successfully retrieved pest and disease cases',
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePestDiseaseCaseRequest $request)
    {
        $case = $this->pestDiseaseCaseService->store($request->validated());

        return ApiResponse::success(
            [$case],
            'Successfully created pest/disease case',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PestDiseaseCase $pestDiseaseCase)
    {
        $case = $this->pestDiseaseCaseService->get($pestDiseaseCase);

        return ApiResponse::success(
            [$case],
            'Successfully retrieved pest/disease case',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePestDiseaseCaseRequest $request, PestDiseaseCase $pestDiseaseCase)
    {
        $case = $this->pestDiseaseCaseService->update($request->validated(), $pestDiseaseCase);

        return ApiResponse::success(
            [$case],
            'Successfully updated pest/disease case',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PestDiseaseCase $pestDiseaseCase)
    {
        $deleted = $this->pestDiseaseCaseService->destroy($pestDiseaseCase);

        return ApiResponse::success(
            ['deleted' => $deleted],
            'Successfully deleted pest/disease case',
            200
        );
    }
}
