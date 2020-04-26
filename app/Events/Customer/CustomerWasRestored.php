<?php

namespace App\Events\Customer;

use App\Customer;
use Illuminate\Queue\SerializesModels;


class CustomerWasRestored
{
    use SerializesModels;

    /**
     * @var Client
     */
    public $client;

    /**
     * Create a new event instance.
     *
     * @param Client $client
     */
    public function __construct(Customer $client)
    {
        $this->client = $client;
    }
}
