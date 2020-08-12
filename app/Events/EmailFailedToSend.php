<?php

namespace App\Events;

use App\Models\Invoice;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvoiceWasUpdated.
 */
class EmailFailedToSend
{
    use SerializesModels;

    /**
     * @var Invoice
     */
    public $entity;

    /**
     * @var array|string
     */
    public string $errors;

    /**
     * Create a new event instance.
     *
     * @param $entity
     */
    public function __construct($entity, string $errors = '')
    {
        $this->entity = $entity;
        $this->errors = $errors;
    }
}
