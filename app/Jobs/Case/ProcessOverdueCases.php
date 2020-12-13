<?php

namespace App\Jobs\Case;

use App\Models\Cases;
use App\Models\Subscription;
use App\Repositories\CaseRepository;
use App\Notifications\Admin\CaseOverdueNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOverdueCases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var CaseRepository
     */
    private CaseRepository $case_repo;

    /**
     * ProcessOverdueCases constructor.
     * @param CaseRepository $case_repo
     */
    public function __construct(CaseRepository $case_repo)
    {
        $this->case_repo = $case_repo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->processOverdueCases();
    }

    private function processOverdueCases()
    {
        $cases = $this->case_repo->getOverdueCases();

        foreach ($cases as $case) {
            $user = !empty($case->assigned_to) ? $case->assignee : $case->user;
            $user->notify(new CaseOverdueNotification($case));

            $this->handleOverdueCases($case);
        }

        return true;
    }

    /**
     * @param Invoice $invoice
     */
    private function handleOverdueCases(Cases $case)
    {
        $event_name = 'LATECASES';
        $class = new \ReflectionClass(Subscription::class);
        $value = $class->getConstant(strtoupper($event_name));

        SendSubscription::dispatchNow($case, $value);
    }
}
