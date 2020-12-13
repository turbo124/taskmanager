<?php


namespace App\Repositories;


use App\Events\Cases\CaseWasCreated;
use App\Events\Cases\CaseWasUpdated;
use App\Events\Cases\RecurringQuoteWasUpdated;
use App\Factory\CommentFactory;
use App\Models\Account;
use App\Models\Cases;
use App\Models\CaseTemplate;
use App\Models\User;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Interfaces\CaseRepositoryInterface;
use App\Requests\SearchRequest;
use App\Search\CaseSearch;
use Carbon\Carbon;

class CaseRepository extends BaseRepository implements CaseRepositoryInterface
{
    /**
     * CaseRepository constructor.
     * @param Cases $case
     */
    public function __construct(Cases $case)
    {
        parent::__construct($case);
        $this->model = $case;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     * @return Cases
     */
    public function findCaseById(int $id): Cases
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new CaseSearch($this))->filter($search_request, $account);
    }

    public function createCase(array $data, Cases $case): ?Cases
    {
        $case = $this->save($data, $case);

        $comment = CommentFactory::create($case->user_id, $case->account_id);
        $comment->comment = $case->message;
        $case->comments()->save($comment);

        $this->sendEmail($case, Cases::STATUS_DRAFT);

        event(new CaseWasCreated($case));

        return $case;
    }

    /**
     * @param array $data
     * @param Cases $case
     */
    public function save(array $data, Cases $case): ?Cases
    {
        $case->message = $this->parseTemplateVariables($data['message'], $case);
        $case->fill($data);
        $case->setNumber();
        $case->save();

        $this->saveInvitations($case, $data);

        return $case;
    }

    private function sendEmail(Cases $case, int $status)
    {
        $template = CaseTemplate::where('send_on', '=', $status)->first();

        if (!empty($template)) {
            $case->service()->sendEmail(
                null,
                $template->name,
                $this->parseTemplateVariables($template->description, $case)
            );
        }

        return true;
    }

    /**
     * @param array $data
     * @param Cases $case
     * @return Cases|null
     */
    public function updateCase(array $data, Cases $case, User $user): ?Cases
    {
        if ($case->status_id === Cases::STATUS_DRAFT && (int)$data['status_id'] === Cases::STATUS_OPEN) {
            $case->date_opened = Carbon::now();
            $case->opened_by = $user->id;

            $this->sendEmail($case, Cases::STATUS_OPEN);
        }

        if ($case->status_id === Cases::STATUS_OPEN && (int)$data['status_id'] === Cases::STATUS_CLOSED) {
            $case->date_closed = Carbon::now();
            $case->closed_by = $user->id;

            $this->sendEmail($case, Cases::STATUS_CLOSED);
        }

        $case = $this->save($data, $case);

        event(new CaseWasUpdated($case));

        return $case;
    }

    public function getOverdueCases()
    {
        return Cases::whereDate('due_date', '<', Carbon::today()->subDay()->toDateString())
                      ->where('is_deleted', '=', false)
                      ->whereIn(
                          'status_id',
                          [Cases::STATUS_OPEN]
                      )->get();
    }
}
