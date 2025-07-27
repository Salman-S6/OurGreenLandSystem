<?php

namespace Modules\Resources\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Resources\Enums\SupplierType;
use Modules\Resources\Models\Supplier;

class SupplierService
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
        // 1. تحقق من أن الترجمة المرسلة تطابق واحدة من الأنواع المعروفة
        $supplierTypeEnum = SupplierType::fromTranslations($data['supplier_type']);

        if (! $supplierTypeEnum) {
            throw ValidationException::withMessages([
                'supplier_type' => ['Invalid supplier type translation.']
            ]);
        }

        // 2. إنشاء السجل الجديد
        $supplier = new Supplier();
        $supplier->user_id = $data['user_id'];
        $supplier->phone_number = $data['phone_number'];
        $supplier->license_number = $data['license_number'];

        // 3. استخدام setTranslations لتخزين الترجمة في عمود supplier_type
        $supplier->setTranslations('supplier_type', $data['supplier_type']); // ✅ تخزين كـ JSON

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
