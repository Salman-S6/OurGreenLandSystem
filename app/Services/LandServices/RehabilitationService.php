<?php

namespace App\Services\LandServices;

use App\Models\Rehabilitation;

class RehabilitationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    /**
     * Store a new rehabilitation event in the database.
     */
    public function store(array $data)
    {
    $rehabilitation = new Rehabilitation();
    $rehabilitation->setTranslation('description', 'ar', $data['description_ar'] ?? null);
    $rehabilitation->setTranslation('description', 'en', $data['description_en'] ?? null);

    $rehabilitation->setTranslation('notes', 'ar', $data['notes_ar'] ?? null);
    $rehabilitation->setTranslation('notes', 'en', $data['notes_en'] ?? null);

    $rehabilitation->event = $data['event'];

    $rehabilitation->save();
    }

    /**
     * Get all rehabilitation events.
     */
    public function getAll()
    {
        return Rehabilitation::latest()->get();
    }

    /**
     * Get a single rehabilitation event by ID.
     */
    public function getById($id): Rehabilitation
    {
        return Rehabilitation::findOrFail($id);
    }

    /**
     * Update a rehabilitation event.
     */
    public function update(Rehabilitation $rehabilitation, array $data): Rehabilitation
    {
        $rehabilitation->update($data);
        return $rehabilitation;
    }

    /**
     * Delete a rehabilitation event.
     */
    public function delete(Rehabilitation $rehabilitation): void
    {
        $rehabilitation->delete();
    }
}
