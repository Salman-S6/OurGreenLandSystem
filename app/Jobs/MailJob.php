<?php

namespace App\Jobs;

use App\Enums\MailTypes;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class MailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public MailTypes $mailType,
        public mixed $data
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        switch ($this->mailType) {
            case MailTypes::VerificationMail:
                if ($this->data instanceof User)
                    $user = $this->data;
                else
                    throw new \Exception("data passed must be type of User");

                $user->sendEmailVerificationNotification();
                break;
        }
    }
}
