<?php

namespace App\Jobs\Task;

use App\Factory\RecurringQuoteToQuoteFactory;
use App\Models\Task;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\RecurringQuote;
use App\Repositories\TaskRepository;
use App\Repositories\QuoteRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRecurringTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Task
     */
    private Task $task;

    /**
     * @var TaskRepository
     */
    private TaskRepository $task_repo;

    /**
     * SendRecurringTask constructor.
     * @param TaskRepository $task_repo
     */
    public function __construct(TaskRepository $task_repo)
    {
        $this->task_repo = $task_repo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->processRecurringTasks();
    }

    private function processRecurringTasks()
    {
        $recurring_tasks = Task::whereDate('next_send_date', '=', \Illuminate\Support\Carbon::today())
                                     ->whereDate('start_date', '!=', Carbon::today())
                                     ->get();

        foreach ($recurring_tasks as $recurring_task) {
            if (Carbon::parse($recurring_task->recurring_start_date)->gt(Carbon::now()) || Carbon::now()->gt(
                    Carbon::parse($recurring_task->recurring_end_date)
                )) {
                continue;
            }

            $task = $recurring_task->replicate();
            $task = $this->task_repo->save(['recurring_task_id' => $recurring_task->id], $task);

            //$quote->service()->sendEmail(null, trans('texts.quote_subject'), trans('texts.quote_body'));

            $recurring_task->last_sent_date = Carbon::today();
            $recurring_task->next_send_date = Carbon::today()->addDays($recurring_task->recurring_frequency);
            $recurring_task->save();
        }
    }
}
