<?php

namespace App\Providers;

use App\Events\Account\AccountWasDeleted;
use App\Events\Invoice\InvoiceWasDeleted;
use App\Events\Invoice\InvoiceWasCancelled;
use App\Events\Invoice\InvoiceWasReversed;
use App\Listeners\Invoice\InvoiceDeletedActivity;
use App\Events\Customer\CustomerWasCreated;
use App\Events\Deal\DealWasCreated;
use App\Events\Invoice\InvoiceWasCreated;
use App\Events\Invoice\InvoiceWasEmailed;
use App\Events\Invoice\InvoiceWasMarkedSent;
use App\Events\Invoice\InvoiceWasPaid;
use App\Listeners\Invoice\InvoicePaidActivity;
use App\Events\Invoice\InvoiceWasUpdated;
use App\Events\Quote\QuoteWasApproved;
use App\Events\Quote\QuoteWasArchived;
use App\Events\Quote\QuoteWasCreated;
use App\Events\Quote\QuoteWasDeleted;
use App\Events\Quote\QuoteWasEmailed;
use App\Events\Quote\QuoteWasMarkedSent;
use App\Events\Quote\QuoteWasRestored;
use App\Events\Quote\QuoteWasUpdated;

use App\Events\Order\OrderWasDispatched;
use App\Events\Order\OrderWasArchived;
use App\Events\Order\OrderWasCreated;
use App\Events\Order\OrderWasDeleted;
use App\Events\Order\OrderWasRestored;
use App\Events\Order\OrderWasUpdated;

use App\Events\Credit\CreditWasArchived;
use App\Events\Credit\CreditWasCreated;
use App\Events\Credit\CreditWasDeleted;
use App\Events\Credit\CreditWasEmailed;
use App\Events\Credit\CreditWasMarkedSent;
use App\Events\Credit\CreditWasUpdated;
use App\Events\Lead\LeadWasCreated;
use App\Events\Misc\InvitationWasViewed;
use App\Events\Payment\PaymentWasCreated;
use App\Events\Payment\PaymentWasDeleted;
use App\Events\PaymentWasRefunded;
use App\Events\PaymentWasVoided;
use App\Events\User\UserWasCreated;
use App\Events\User\UserWasDeleted;
use App\Listeners\Customer\CustomerCreatedActivity;
use App\Listeners\Payment\PaymentCreatedActivity;
use App\Listeners\Payment\PaymentDeletedActivity;
use App\Listeners\Payment\PaymentRefundedActivity;
use App\Listeners\Payment\PaymentVoidedActivity;
use App\Listeners\Deal\DealNotification;
use App\Listeners\Document\DeleteAccountDocuments;
use App\Listeners\Quote\QuoteApprovedActivity;
use App\Listeners\Quote\QuoteCreatedActivity;
use App\Listeners\Quote\QuoteArchivedActivity;
use App\Listeners\Quote\QuoteDeletedActivity;
use App\Listeners\Order\OrderArchivedActivity;
use App\Listeners\Order\OrderCreatedActivity;
use App\Listeners\Order\OrderDeletedActivity;
use App\Listeners\Order\OrderMarkedSentActivity;
use App\Listeners\Order\OrderDispatchedActivity;
use App\Listeners\Credit\CreditApprovedActivity;
use App\Listeners\Credit\CreditCreatedActivity;
use App\Listeners\Credit\CreditArchivedActivity;
use App\Listeners\Credit\CreditDeletedActivity;
use App\Listeners\Credit\CreditMarkedSentActivity;
use App\Listeners\Invoice\InvoiceCreatedActivity;
use App\Listeners\Invoice\InvoiceMarkedSentActivity;
use App\Listeners\Invoice\InvoiceEmailActivity;
use App\Listeners\Invoice\InvoiceEmailedNotification;
use App\Listeners\Invoice\InvoiceEmailFailedActivity;
use App\Listeners\Invoice\InvoiceUpdatedActivity;
use App\Listeners\Lead\LeadNotification;
use App\Listeners\Misc\InvitationViewedListener;
use App\Listeners\Order\OrderNotification;
use App\Listeners\Payment\PaymentNotification;
use App\Listeners\NewUserNotification;
use App\Listeners\User\DeletedUserActivity;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserWasCreated::class             => [
            NewUserNotification::class,
        ],
        UserWasDeleted::class             => [
            DeletedUserActivity::class,
        ],
        // Clients
        CustomerWasCreated::class           => [
            CustomerCreatedActivity::class
        ],
        //payments
        PaymentWasCreated::class          => [
            PaymentCreatedActivity::class,
            PaymentNotification::class,
        ],
        PaymentWasDeleted::class          => [
            PaymentDeletedActivity::class,
        ],
        PaymentWasRefunded::class         => [
            PaymentRefundedActivity::class,
        ],
        PaymentWasVoided::class           => [
            PaymentVoidedActivity::class,
        ],
        //Invoices
        InvoiceWasMarkedSent::class       => [
            InvoiceMarkedSentActivity::class,
        ],
        InvoiceWasUpdated::class          => [
            InvoiceUpdatedActivity::class
        ],
        InvoiceWasCreated::class          => [
            InvoiceCreatedActivity::class
        ],
        InvoiceWasPaid::class             => [
            InvoicePaidActivity::class,
        ],
        InvoiceWasEmailed::class          => [
            InvoiceEmailActivity::class,
            InvoiceEmailedNotification::class,
        ],
        InvoiceWasDeleted::class          => [
            InvoiceDeletedActivity::class,
        ],
        InvoiceWasReversed::class         => [
        ],
        InvoiceWasCancelled::class        => [
        ],
        InvitationWasViewed::class        => [
            InvitationViewedListener::class
        ],
        // quotes
        QuoteWasApproved::class           => [
            QuoteApprovedActivity::class
        ],
        QuoteWasCreated::class           => [
            QuoteCreatedActivity::class
        ],
        QuoteWasDeleted::class           => [
            QuoteDeletedActivity::class
        ],
        QuoteWasArchived::class           => [
            QuoteArchivedActivity::class
        ],
        QuoteWasMarkedSent::class        => [
            QuoteMarkedSentActivity::class
        ],
        //orders
        OrderWasDispatched::class           => [
            OrderDispatchedActivity::class
        ],
        OrderWasCreated::class           => [
            OrderCreatedActivity::class
        ],
        OrderWasDeleted::class           => [
            OrderDeletedActivity::class
        ],
        OrderWasArchived::class           => [
            OrderArchivedActivity::class
        ],
        OrderWasMarkedSent::class        => [
            OrderMarkedSentActivity::class
        ],
        // credits
        CreditWasCreated::class           => [
            CreditCreatedActivity::class
        ],
        CreditWasDeleted::class           => [
            CreditDeletedActivity::class
        ],
        CreditWasArchived::class           => [
            CreditArchivedActivity::class
        ],
        CreditWasMarkedSent::class        => [
            CreditMarkedSentActivity::class
        ],
        LeadWasCreated::class             => [
            LeadNotification::class
        ],
        OrderWasCreated::class            => [
            OrderNotification::class
        ],
        DealWasCreated::class             => [
            DealNotification::class
        ],
        AccountWasDeleted::class          => [
            DeleteAccountDocuments::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
