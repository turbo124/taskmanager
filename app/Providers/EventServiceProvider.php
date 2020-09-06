<?php

namespace App\Providers;

use App\Events\Credit\CreditWasArchived;
use App\Events\Credit\CreditWasCreated;
use App\Events\Credit\CreditWasDeleted;
use App\Events\Credit\CreditWasMarkedSent;
use App\Events\Credit\CreditWasRestored;
use App\Events\Credit\CreditWasUpdated;
use App\Events\Customer\CustomerWasArchived;
use App\Events\Customer\CustomerWasCreated;
use App\Events\Customer\CustomerWasDeleted;
use App\Events\Customer\CustomerWasRestored;
use App\Events\Customer\CustomerWasUpdated;
use App\Events\Deal\DealWasCreated;
use App\Events\EmailFailedToSend;
use App\Events\Invoice\InvoiceWasArchived;
use App\Events\Invoice\InvoiceWasCancelled;
use App\Events\Invoice\InvoiceWasCreated;
use App\Events\Invoice\InvoiceWasDeleted;
use App\Events\Invoice\InvoiceWasEmailed;
use App\Events\Invoice\InvoiceWasMarkedSent;
use App\Events\Invoice\InvoiceWasPaid;
use App\Events\Invoice\InvoiceWasRestored;
use App\Events\Invoice\InvoiceWasReversed;
use App\Events\Invoice\InvoiceWasUpdated;
use App\Events\Lead\LeadWasArchived;
use App\Events\Lead\LeadWasCreated;
use App\Events\Misc\InvitationWasViewed;
use App\Events\Order\OrderWasArchived;
use App\Events\Order\OrderWasBackordered;
use App\Events\Order\OrderWasCreated;
use App\Events\Order\OrderWasDeleted;
use App\Events\Order\OrderWasDispatched;
use App\Events\Order\OrderWasHeld;
use App\Events\Order\OrderWasMarkedSent;
use App\Events\Order\OrderWasRestored;
use App\Events\Order\OrderWasUpdated;
use App\Events\Payment\PaymentFailed;
use App\Events\Payment\PaymentWasArchived;
use App\Events\Payment\PaymentWasCreated;
use App\Events\Payment\PaymentWasDeleted;
use App\Events\Payment\PaymentWasRefunded;
use App\Events\Payment\PaymentWasRestored;
use App\Events\Payment\PaymentWasUpdated;
use App\Events\Quote\PurchaseOrderWasApproved;
use App\Events\Quote\QuoteWasArchived;
use App\Events\Quote\QuoteWasCreated;
use App\Events\Quote\QuoteWasDeleted;
use App\Events\Quote\QuoteWasMarkedSent;
use App\Events\Quote\QuoteWasRestored;
use App\Events\Quote\QuoteWasUpdated;
use App\Events\Uploads\FileWasDeleted;
use App\Events\Uploads\FileWasUploaded;
use App\Events\User\UserEmailChanged;
use App\Events\User\UserWasCreated;
use App\Events\User\UserWasDeleted;
use App\Listeners\Credit\CreditArchivedActivity;
use App\Listeners\Credit\CreditCreatedActivity;
use App\Listeners\Credit\CreditDeletedActivity;
use App\Listeners\Credit\CreditMarkedSentActivity;
use App\Listeners\Credit\CreditRestoredActivity;
use App\Listeners\Credit\CreditUpdatedActivity;
use App\Listeners\Customer\CustomerArchivedActivity;
use App\Listeners\Customer\CustomerCreatedActivity;
use App\Listeners\Customer\CustomerDeletedActivity;
use App\Listeners\Customer\CustomerRestoredActivity;
use App\Listeners\Customer\CustomerUpdatedActivity;
use App\Listeners\Deal\DealNotification;
use App\Listeners\Entity\EntityEmailFailedToSend;
use App\Listeners\Entity\EntityViewedListener;
use App\Listeners\Invoice\InvoiceArchivedActivity;
use App\Listeners\Invoice\InvoiceCancelledActivity;
use App\Listeners\Invoice\InvoiceCreatedActivity;
use App\Listeners\Invoice\InvoiceDeletedActivity;
use App\Listeners\Invoice\InvoiceEmailActivity;
use App\Listeners\Invoice\InvoiceEmailedNotification;
use App\Listeners\Invoice\InvoiceMarkedSentActivity;
use App\Listeners\Invoice\InvoicePaidActivity;
use App\Listeners\Invoice\InvoiceRestoredActivity;
use App\Listeners\Invoice\InvoiceReversedActivity;
use App\Listeners\Invoice\InvoiceUpdatedActivity;
use App\Listeners\Lead\LeadArchivedActivity;
use App\Listeners\Lead\LeadCreatedActivity;
use App\Listeners\Lead\LeadNotification;
use App\Listeners\NewUserNotification;
use App\Listeners\Order\OrderArchivedActivity;
use App\Listeners\Order\OrderBackorderedActivity;
use App\Listeners\Order\OrderBackorderedNotification;
use App\Listeners\Order\OrderCreatedActivity;
use App\Listeners\Order\OrderDeletedActivity;
use App\Listeners\Order\OrderDispatchedActivity;
use App\Listeners\Order\OrderHeldActivity;
use App\Listeners\Order\OrderHeldNotification;
use App\Listeners\Order\OrderMarkedSentActivity;
use App\Listeners\Order\OrderNotification;
use App\Listeners\Order\OrderRestoredActivity;
use App\Listeners\Order\OrderUpdatedActivity;
use App\Listeners\Payment\PaymentArchivedActivity;
use App\Listeners\Payment\PaymentCreatedActivity;
use App\Listeners\Payment\PaymentDeletedActivity;
use App\Listeners\Payment\PaymentFailedActivity;
use App\Listeners\Payment\PaymentFailedNotification;
use App\Listeners\Payment\PaymentNotification;
use App\Listeners\Payment\PaymentRefundedActivity;
use App\Listeners\Payment\PaymentRefundedNotification;
use App\Listeners\Payment\PaymentRestoredActivity;
use App\Listeners\Payment\PaymentUpdatedActivity;
use App\Listeners\Quote\QuoteApprovedActivity;
use App\Listeners\Quote\QuoteArchivedActivity;
use App\Listeners\Quote\QuoteCreatedActivity;
use App\Listeners\Quote\QuoteDeletedActivity;
use App\Listeners\Quote\QuoteMarkedSentActivity;
use App\Listeners\Quote\QuoteRestoredActivity;
use App\Listeners\Quote\QuoteUpdatedActivity;
use App\Listeners\Quote\SendQuoteApprovedNotification;
use App\Listeners\User\DeletedUserActivity;
use App\Listeners\User\SendUserEmailChangedEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserWasCreated::class       => [
            NewUserNotification::class,
        ],
        UserWasDeleted::class       => [
            DeletedUserActivity::class,
        ],
        UserEmailChanged::class     => [
            SendUserEmailChangedEmail::class
        ],
        // Customers
        CustomerWasCreated::class   => [
            CustomerCreatedActivity::class
        ],
        CustomerWasArchived::class  => [
            CustomerArchivedActivity::class
        ],
        CustomerWasRestored::class  => [
            CustomerRestoredActivity::class
        ],
        CustomerWasDeleted::class   => [
            CustomerDeletedActivity::class
        ],
        CustomerWasUpdated::class   => [
            CustomerUpdatedActivity::class
        ],
        //payments
        PaymentWasCreated::class    => [
            PaymentCreatedActivity::class,
            PaymentNotification::class,
        ],
        PaymentWasUpdated::class    => [
            PaymentUpdatedActivity::class
        ],
        PaymentWasArchived::class   => [
            PaymentArchivedActivity::class,
        ],
        PaymentWasRestored::class   => [
            PaymentRestoredActivity::class,
        ],
        PaymentWasDeleted::class    => [
            PaymentDeletedActivity::class,
        ],
        PaymentWasRefunded::class   => [
            PaymentRefundedActivity::class,
            PaymentRefundedNotification::class
        ],
        PaymentFailed::class        => [
            PaymentFailedActivity::class,
            PaymentFailedNotification::class
        ],
        //Invoices
        InvoiceWasMarkedSent::class => [
            InvoiceMarkedSentActivity::class,
        ],
        InvoiceWasArchived::class   => [
            InvoiceArchivedActivity::class
        ],
        InvoiceWasRestored::class   => [
            InvoiceRestoredActivity::class
        ],
        InvoiceWasUpdated::class    => [
            InvoiceUpdatedActivity::class
        ],
        InvoiceWasCreated::class    => [
            InvoiceCreatedActivity::class
        ],
        InvoiceWasPaid::class       => [
            InvoicePaidActivity::class,
        ],
        InvoiceWasEmailed::class    => [
            InvoiceEmailActivity::class,
            InvoiceEmailedNotification::class,
        ],
        InvoiceWasDeleted::class    => [
            InvoiceDeletedActivity::class,
        ],
        InvoiceWasReversed::class   => [
            InvoiceReversedActivity::class
        ],
        InvoiceWasCancelled::class  => [
            InvoiceCancelledActivity::class
        ],
        InvitationWasViewed::class  => [
            EntityViewedListener::class
        ],
        // quotes
        PurchaseOrderWasApproved::class => [
            QuoteApprovedActivity::class,
            SendQuoteApprovedNotification::class
        ],
        QuoteWasCreated::class      => [
            QuoteCreatedActivity::class
        ],
        QuoteWasUpdated::class      => [
            QuoteUpdatedActivity::class
        ],
        QuoteWasDeleted::class      => [
            QuoteDeletedActivity::class
        ],
        QuoteWasArchived::class     => [
            QuoteArchivedActivity::class
        ],
        QuoteWasRestored::class     => [
            QuoteRestoredActivity::class
        ],
        QuoteWasMarkedSent::class   => [
            QuoteMarkedSentActivity::class
        ],
        //orders
        OrderWasDispatched::class   => [
            OrderDispatchedActivity::class
        ],
        OrderWasDeleted::class      => [
            OrderDeletedActivity::class
        ],
        OrderWasBackordered::class  => [
            OrderBackorderedActivity::class,
            OrderBackorderedNotification::class
        ],
        OrderWasHeld::class         => [
            OrderHeldActivity::class,
            OrderHeldNotification::class
        ],
        OrderWasArchived::class     => [
            OrderArchivedActivity::class
        ],
        OrderWasRestored::class     => [
            OrderRestoredActivity::class
        ],
        OrderWasUpdated::class      => [
            OrderUpdatedActivity::class
        ],
        OrderWasMarkedSent::class   => [
            OrderMarkedSentActivity::class
        ],
        // credits
        CreditWasCreated::class     => [
            CreditCreatedActivity::class
        ],
        CreditWasDeleted::class     => [
            CreditDeletedActivity::class
        ],
        CreditWasArchived::class    => [
            CreditArchivedActivity::class
        ],
        CreditWasRestored::class    => [
            CreditRestoredActivity::class
        ],
        CreditWasUpdated::class     => [
            CreditUpdatedActivity::class
        ],
        CreditWasMarkedSent::class  => [
            CreditMarkedSentActivity::class
        ],
        LeadWasCreated::class       => [
            LeadCreatedActivity::class,
            LeadNotification::class
        ],
        LeadWasArchived::class      => [
            LeadArchivedActivity::class
        ],
        OrderWasCreated::class      => [
            OrderCreatedActivity::class,
            OrderNotification::class
        ],
        DealWasCreated::class       => [
            DealNotification::class
        ],
        FileWasUploaded::class      => [
        ],
        FileWasDeleted::class       => [
        ],
        EmailFailedToSend::class    => [
            EntityEmailFailedToSend::class
        ]
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
