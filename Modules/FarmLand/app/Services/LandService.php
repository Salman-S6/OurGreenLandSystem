<?php

namespace Modules\FarmLand\Services;

use App\Services\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\FarmLand\Models\Land;

class LandService extends BaseCrudService
{
    public function __construct(Land $model)
    {
        parent::__construct($model);
    }

    /**
     * Get lands sorted by rehabilitation priority (high → low), with caching.
     */
    public function getPrioritizedLands()
    {
            return Land::with(['farmer', 'soilType'])
                ->prioritized()
                ->get();
    }


}
