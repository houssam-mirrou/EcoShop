<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $user = $event->order->user;

        Mail::raw("Bonjour {$user->name}, votre commande #{$event->order->id} a bien été validée.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Confirmation de votre commande EcoShop');
        });

        Log::info("Email envoyé à {$user->email} pour la commande {$event->order->id}");
    }
}
