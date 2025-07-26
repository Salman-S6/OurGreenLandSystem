<?php

namespace Modules\CropManagement\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CropPlanUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public  $cropPlan;
    /**
     * Create a new message instance.
     */
    public function __construct($cropPlan) {
        $this->cropPlan=$cropPlan;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Update CropPlan')
        ->view('cropmanagement::emails.update_plan')
        ->with(['cropPlan'=>$this->cropPlan]);
    }
}
