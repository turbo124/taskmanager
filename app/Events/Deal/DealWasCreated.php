<?php

namespace App\Events\Deal;

use App\Account;
use App\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use robertogallea\LaravelMetrics\Models\Traits\Measurable;
use robertogallea\LaravelMetrics\Models\Interfaces\PerformsMetrics;


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
