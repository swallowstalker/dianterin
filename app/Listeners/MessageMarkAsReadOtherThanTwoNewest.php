<?php

namespace App\Listeners;

use App\Events\MessageIsCreated;
use App\Message;
use App\MessageOwnedByUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class MessageMarkAsReadOtherThanTwoNewest
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
     * @param  MessageIsCreated  $event
     * @return void
     */
    public function handle(MessageIsCreated $event)
    {
        $receiverID = $event->messageOwnedByUser->receiver;

        $twoNewestMessage = MessageOwnedByUser::where("receiver", $receiverID)
            ->orderBy("id", "DESC")
            ->take(2)->pluck("id")->toArray();

        if (count($twoNewestMessage) > 0) {
            MessageOwnedByUser::where("receiver", $receiverID)
                ->whereNotIn("id", $twoNewestMessage)
                ->update(["status" => true]);
        }

    }
}
