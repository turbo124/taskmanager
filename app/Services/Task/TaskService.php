<?php

namespace App\Services\Task;

use App\Factory\CustomerFactory;
use App\Factory\TaskFactory;
use App\Models\Invoice;
use App\Repositories\ClientContactRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\InvoiceSum;
use App\Repositories\OrderRepository;
use App\Repositories\TaskRepository;
use App\Services\Task\ConvertLead;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Transformations\TaskTransformable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskCreated;
use App\Services\EntityManager;
use App\Services\ServiceBase;

/**
 * Class TaskService
 * @package App\Services\Task
 */
class TaskService extends ServiceBase
{
    protected $task;

    /**
     * TaskService constructor.
     * @param \App\Models\Task $task
     */
    public function __construct(Task $task)
    {
        parent::__construct($task);
        $this->task = $task;
    }

    /**
     * @return array
     */
    public function sendEmail()
    {
        $send_email = new SendEmail($this->task);

        $this->task = $send_email->execute();

        return $this->task;
    }

    /**
     * @param Request $request
     * @param CustomerRepository $customer_repo
     * @param TaskRepository $task_repo
     * @param bool $is_lead
     * @return mixed
     */
    public function updateLead(
        Request $request,
        CustomerRepository $customer_repo,
        TaskRepository $task_repo,
        $is_lead = true
    ) {
        $update_lead = new UpdateLead($this->task, $request, $customer_repo, $task_repo, $is_lead);

        $this->task = $update_lead->execute();
        return $this->task;
    }

    public function setTaskType($task_type)
    {
        $this->task->task_type = $task_type;

        return $this;
    }

    public function approve(InvoiceRepository $invoice_repo, TaskRepository $task_repo): ?Task
    {
        if ($this->deal->status_id != Deal::STATUS_SENT) {
            return null;
        }

        $this->task->setStatus(Task::STATUS_APPROVED);
        $this->task->save();

        if ($this->task->customer->getSetting('should_convert_deal')) {
            //$invoice = (new ConvertDeal($this->quote, $invoice_repo))->execute();
            //$this->quote->setInvoiceId($invoice->id);
            //$this->quote->save();
        }

        event(new DealWasApproved($this->task));

        // trigger
        $subject = trans('texts.deal_approved_subject');
        $body = trans('texts.deal_approved_body');
        $this->trigger($subject, $body, $task_repo);

        return $this->quote;
    }

    /**
     * @return \App\Models\Task|null
     */
    public function save(): ?Task
    {
        $this->task->save();
        return $this->task;
    }
}
