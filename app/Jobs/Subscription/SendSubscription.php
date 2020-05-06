<?php

namespace App\Jobs\Subscription;

use App\Subscription;
use App\Traits\EntityDataBuilder;
use App\Repositories\SubscriptionRepository;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SendSubscription
{
    use EntityDataBuilder;
    use Dispatchable;

    protected $event;
    protected $entity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($entity, $event)
    {
        $this->event = $event;
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscription = (new SubscriptionRepository(new Subscription))->findSubscriptionByEvent($this->event, $this->entity->account);

        if(empty($subscription) || $subscription->count() === 0) {
            return true;
        }

        $data = $this->buildEntityData($this->entity);

        if (empty($data)) {

            return false;
        }

        $this->sendData($data, $subscription);

        return true;
    }

    private function sendData(array $data, Subscription $subscription)
    {
        return true;

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $subscription->target_url, [
            'headers'     => [
                //'Authorization' => 'Bearer ' . 'Mu9tNULggxB9QFRyDytg9RYdpG8GsQJ9LGBBTYWSzlKAkJgaK7hs0xrV9F4qKrM7',
                'Accept' => 'application/json',
            ],
            'form_params' => $data
        ]);
    }
}
