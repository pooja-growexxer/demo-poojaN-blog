<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\SendEmailByQueue;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
    protected $data;
  
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        \Log::info($this->data);
        \Log::info('JOB PAge');
    }
  
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Handle job');
        try {
            Mail::to($this->data['email'])->send(new SendEmailByQueue($this->data));
                \Log::info('Sucessful Mail Sent!');
           } catch (\Throwable $th) {
                \Log::info('Mail Faileddd!');
                \Log::error('Error in  Send Mail:' . $th->getMessage());
            } 
       
    }
}
