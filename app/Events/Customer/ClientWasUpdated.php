<?php

namespace App\Events\Client;

use App\Customer;
use Illuminate\Queue\SerializesModels;

/**
 * Class ClientWasUpdated.
 */
class ClientWasUpdated
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
