<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Repositories\CustomerRepository;
use App\Repositories\InvoiceSum;
use App\Repositories\TaskRepository;
use App\Services\EntityManager;
use App\Services\ServiceBase;
use App\Services\Task\ConvertLead;
use Illuminate\Http\Request;

/**
 * Class TaskService
 * @package App\Services\Task
 */
class TaskService extends ServiceBase
{
    protected $task;

    /**
     * TaskService constructor.
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        parent::__construct($task);
        $this->task = $task;
    }

    /**
     * @param string $subject
     * @param string $body
     * @return array
     */
    public function sendEmail($contact = null, $subject = '', $body = '', $template = 'deal')
    {
        return (new TaskEmail($this->task, $subject, $body))->execute();
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
     * @param null $contact
     * @param bool $update
     * @return mixed|string
     */
    public function generatePdf($contact = null, $update = false)
    {
        return (new GeneratePdf($this->task, $contact, $update))->execute();
    }

    /**
     * @return Task|null
     */
    public function save(): ?Task
    {
        $this->task->save();
        return $this->task;
    }
}
