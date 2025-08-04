<?php

namespace Modules\Resources\Services;

use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Resources\Enums\SupplierType;
use Modules\Resources\Models\Supplier;

class SupplierService implements BaseCrudServiceInterface
{

    /**
     * Retrieve all suppliers with optional filters (e.g., by user_id).
     *
     * @param array $filters
     * @return Collection|iterable<Model>
     */
    public function getAll(array $filters = []): iterable
    {
        $query = Supplier::query()->with(['user', 'ratings'])->latest();

        // Optional filter by user_id
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->get();
    }

    /**
     * Store a new supplier with translated fields if applicable.
     *
     * @param array $data
     * @return Supplier
     */
    public function store(array $data): Supplier
    {
     try {
        $supplierTypeEnum = SupplierType::fromTranslations($data['supplier_type']);

        if (! $supplierTypeEnum) {
            throw ValidationException::withMessages([
                'supplier_type' => ['Invalid supplier type translation.']
            ]);
        }

        $supplier = new Supplier();
        $supplier->user_id = $data['user_id'];
        $supplier->phone_number = $data['phone_number'];
        $supplier->license_number = $data['license_number'];

        $supplier->setTranslations('supplier_type', $data['supplier_type']); 
        $supplier->save();

        return $supplier;

    } catch (\Exception $e) {
        Log::error('Failed to create supplier: ' . $e->getMessage());
        throw $e;
    }
}
    /**
     * Get a single supplier with its relations.
     *
     * @param Model $model
     * @return Supplier
     */
    public function get(Model $model): Supplier
    {
        /** @var Supplier $supplier */
        $supplier = $model;
        return $supplier->load(['user', 'ratings']);
    }

    /**
     * Update a supplier with the given data.
     *
     * @param array $data
     * @param Model $model
     * @return Supplier
     */
    public function update(array $data, Model $model): Supplier
    {
        /** @var Supplier $supplier */
        $supplier = $model;
        $supplier->update($data);
        return $supplier;
    }

    /**
     * Delete a supplier from the system.
     *
     * @param Model $model
     * @return bool
     */
    public function destroy(Model $model): bool
    {
        /** @var Supplier $supplier */
        $supplier = $model;
        return $supplier->delete();
    }
}
