<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $email,$view,$data,$subject;

    public function __construct(string  $email,string $view,string $subject,array $data)
    {
        $this->email = $email;
        $this->view = $view;
        $this->data = $data;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::send($this->view,$this->data, function ($message)  {
            $message
                ->to( $this->email)
                ->subject($this->subject);
        });

    }
}
