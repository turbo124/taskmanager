<?php

namespace App\Events\Deal;

use App\Models\Account;
use App\Models\Task;
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

    public $account;

    /**
     * DealWasCreated constructor.
     * @param Task $deal
     */
    public function __construct(Task $deal, Account $account)
    {
        $this->deal = $deal;
        $this->account = $account;
    }
}
