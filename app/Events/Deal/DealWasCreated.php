<?php

namespace App\Events\Deal;

use App\Models\Deal;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use robertogallea\LaravelMetrics\Models\Interfaces\PerformsMetrics;
use robertogallea\LaravelMetrics\Models\Traits\Measurable;


/**
 * Class PaymentWasCreated.
 */
class DealWasCreated implements PerformsMetrics
{
    use SerializesModels;
    use Dispatchable;
    use Measurable;

    protected $meter = 'deal-created';

    /**
     * @var array $payment
     */
    public $deal;

    /**
     * DealWasCreated constructor.
     * @param Deal $deal
     */
    public function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }
}
