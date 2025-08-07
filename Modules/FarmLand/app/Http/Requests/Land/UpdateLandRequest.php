<?php

namespace Modules\FarmLand\Http\Requests\Land;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Rules\UniqueBoundaryCoordinates;
use Modules\FarmLand\Rules\UniqueGpsCoordinates;

class UpdateLandRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'owner_id'         => 'sometimes|exists:users,id',
        'farmer_id'        => 'sometimes|exists:users,id',
        'soil_type_id'     => 'sometimes|exists:soils,id',


        'area'             => 'nullable|numeric|min:0',
        'damage_level'     => 'nullable|in:low,medium,high',

        'gps_coordinates'         => ['sometimes', 'array', new UniqueGpsCoordinates],
        'gps_coordinates.lat'     => ['sometimes', 'required_with:gps_coordinates.lng', 'numeric'],
        'gps_coordinates.lng'     => ['sometimes', 'required_with:gps_coordinates.lat', 'numeric'],

       
        'boundary_coordinates'         => ['sometimes', 'array', new UniqueBoundaryCoordinates],
        'boundary_coordinates.*'       => ['sometimes', 'array'],
        'boundary_coordinates.*.lat'   => ['sometimes', 'required_with:boundary_coordinates.*.lng', 'numeric'],
        'boundary_coordinates.*.lng'   => ['sometimes', 'required_with:boundary_coordinates.*.lat', 'numeric'],
        
        'region' => ['sometimes', 'string', 'max:255'],
        'rehabilitation_date' => 'sometimes|date',
        ];
    }

}