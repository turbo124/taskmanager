<?php

namespace App\Events\Invoice;

use App\Invoice;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use robertogallea\LaravelMetrics\Models\Traits\Measurable;
use robertogallea\LaravelMetrics\Models\Interfaces\PerformsMetrics;

/**
 * Class InvoiceWasCreated.
 */
class InvoiceWasCreated implements PerformsMetrics
{
    use SerializesModels;
    use Dispatchable;
    use Measurable;
    use SendSubscription;

    protected $meter = 'invoice-created';

    /**
     * @var Invoice
     */
    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->send($invoice, get_class($this));
    }
}
