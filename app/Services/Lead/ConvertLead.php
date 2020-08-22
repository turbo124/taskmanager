<?php

namespace App\Services\Lead;

use App\Factory\Lead\CloneLeadToAddressFactory;
use App\Factory\Lead\CloneLeadToContactFactory;
use App\Factory\Lead\CloneLeadToCustomerFactory;
use App\Factory\Lead\CloneLeadToTaskFactory;
use App\Factory\Lead\CloneLeadToDealFactory;
use App\Models\Lead;
use App\Models\Task;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class ConvertLead
 * @package App\Services\Task
 */
class ConvertLead
{
    private $lead;

    /**
     * ConvertLead constructor.
     * @param Task $task
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function execute()
    {
        if ($this->lead->task_status === Lead::STATUS_COMPLETED) {
            return false;
        }

        try {
            DB::beginTransaction();
            $customer = CloneLeadToCustomerFactory::create($this->lead, $this->lead->user, $this->lead->account);

            if (!$customer->save()) {
                DB::rollback();
                return null;
            }

            $address = CloneLeadToAddressFactory::create($this->lead, $customer);

            if (!$address->save()) {
                DB::rollback();
                return null;
            }

            $client_contact =
                CloneLeadToContactFactory::create($this->lead, $customer, $this->lead->user, $this->lead->account);


            if (!$client_contact->save()) {
                DB::rollback();
                return null;
            }

           if(!$this->createTask()) {
               return null;
           }

            if(!$this->createDeal()) {
               return null;
           }

            $this->lead->task_status = Lead::STATUS_COMPLETED;
            $this->lead->status_id = Lead::STATUS_COMPLETED;

            if (!$this->lead->save()) {
                DB::rollback();
                return null;
            }

            DB::commit();

            return $this->lead;
        } catch (Exception $e) {
            DB::rollback();
            return null;
        }
    }

    private function createTask(): bool
    {
         $task = CloneLeadToTaskFactory::create($this->lead, $customer, $this->lead->user, $this->lead->account);

            $date = new DateTime(); // Y-m-d
            $date->add(new DateInterval('P30D'));
            $due_date = $date->format('Y-m-d');

            $task->due_date = $due_date;

            if (!$task->save()) {
                DB::rollback();
                return false;
            }

           return true;
    }

    private function createDeal(): bool
    {
         $deal = CloneLeadToDealFactory::create($this->lead, $customer, $this->lead->user, $this->lead->account);

            $date = new DateTime(); // Y-m-d
            $date->add(new DateInterval('P30D'));
            $due_date = $date->format('Y-m-d');

            $deal->due_date = $due_date;

            if (!$deal->save()) {
                DB::rollback();
                return false;
            }

         return true;
    }
}
