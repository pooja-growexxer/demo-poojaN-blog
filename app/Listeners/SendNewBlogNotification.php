<?php

namespace App\Listeners;

use App\Events\BlogCreated;
use App\Mail\NewBlogNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewBlogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BlogCreated  $event
     * @return void
     */
    public function handle(BlogCreated $event)
    {
        Mail::to('evlyneangel@gmail.com')->send(new NewBlogNotification($event->blog));
    }
}
