<?php

namespace Modules\FarmLand\Http\Requests\Land;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Rules\UniqueBoundaryCoordinates;
use Modules\FarmLand\Rules\UniqueGpsCoordinates;

class StoreLandRequest extends FormRequest
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
            'owner_id' => 'required|exists:users,id',
            'farmer_id' => 'required|exists:users,id',
            'area' => 'nullable|numeric|min:0',
            'soil_type_id' => 'required|exists:soils,id',
            'damage_level' => 'nullable|in:low,medium,high',
            'gps_coordinates' => ['nullable', 'array', new UniqueGpsCoordinates],
            'gps_coordinates.lat' => ['required_with:gps_coordinates', 'numeric'],
            'gps_coordinates.lng' => ['required_with:gps_coordinates', 'numeric'],
 
            'boundary_coordinates' => ['nullable', 'array', new UniqueBoundaryCoordinates ],
            'boundary_coordinates.*' => ['required', 'array'],
            'boundary_coordinates.*.lat' => ['required', 'numeric'],
            'boundary_coordinates.*.lng' => ['required', 'numeric'],
            'rehabilitation_date' => 'required|date',
        ];
    } 

}