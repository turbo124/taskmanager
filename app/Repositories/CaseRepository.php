<?php


namespace App\Repositories;


use App\Events\Cases\CaseWasCreated;
use App\Events\Cases\CaseWasUpdated;
use App\Factory\CommentFactory;
use App\Models\Cases;
use App\Repositories\Base\BaseRepository;

class CaseRepository extends BaseRepository
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

    public function createCase(array $data, Cases $case): ?Cases
    {
        $case = $this->save($data, $case);

        $comment = CommentFactory::create($case->user_id, $case->account_id);
        $comment->comment = $data['message'];
        $case->comments()->save($comment);

        event(new CaseWasCreated($case));

        return $case;
    }

    /**
     * @param array $data
     * @param Cases $case
     * @return Cases|null
     */
    public function updateCase(array $data, Cases $case): ?Cases
    {
        $case = $this->save($data, $case);

        event(new CaseWasUpdated($case));

        return $case;
    }

    /**
     * @param array $data
     * @param Cases $case
     */
    public function save(array $data, Cases $case): ?Cases
    {
        $case->fill($data);
        $case->setNumber();
        $case->message = $this->parseTemplateVariables($data['message'], $case);
        $case->save();

        $this->saveInvitations($case, 'case', $data);

        return $case;
    }
}
