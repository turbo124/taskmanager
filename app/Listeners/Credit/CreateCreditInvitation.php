<?php

namespace App\Listeners\Credit;

use App\Factory\CreditInvitationFactory;
use App\Factory\InvoiceInvitationFactory;
use App\InvoiceInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class CreateCreditInvitation implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $credit = $event->credit;

        $contacts = $credit->client->contacts;

        $contacts->each(function ($contact) use ($credit) {
            $invitation = InvoiceInvitation::whereAccountId($credit->account_id)->whereClientContactId($contact->id)
                ->whereCreditId($credit->id)->first();

            if (!$invitation && $contact->send_credit) {
                $ii = CreditInvitationFactory::create($credit->account_id, $credit->user_id);
                $ii->credit_id = $credit->id;
                $ii->client_contact_id = $contact->id;
                $ii->save();
            } elseif ($invitation && !$contact->send_credit) {
                $invitation->delete();
            }
        });
    }
}
