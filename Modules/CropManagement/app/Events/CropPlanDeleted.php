<?php

namespace Modules\CropManagement\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CropPlanDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $cropPlan;
    /**
     * Create a new event instance.
     */
    public function __construct($cropPlan) {
        $this->cropPlan=$cropPlan;
    }


}
