<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories;

use App\Events\Lead\LeadWasCreated;
use App\Events\Lead\LeadWasUpdated;
use App\Models\Lead;
use App\Models\Message;
use App\Repositories\Base\BaseRepository;

/**
 * Description of MessageRepository
 *
 * @author michael.hampton
 */
class LeadRepository extends BaseRepository
{

    /**
     * MessageRepository constructor.
     * @param Message $message
     */
    public function __construct(Lead $lead)
    {
        parent::__construct($lead);
        $this->model = $lead;
    }

    /**
     * @param Lead $lead
     * @param array $data
     * @return Lead|null
     */
    public function createLead(Lead $lead, array $data): ?Lead
    {
        $lead = $this->save($lead, $data);

        event(new LeadWasCreated($lead));

        return $lead;
    }

    /**
     * Create the message
     *
     * @param array $data
     *
     * @return Message
     */
    public function save(Lead $lead, array $data): Lead
    {
        $lead = $lead->fill($data);
        $lead->setNumber();
        $lead->save();
        return $lead;
    }

    /**
     * @param Lead $lead
     * @param array $data
     * @return Lead|null
     */
    public function updateLead(Lead $lead, array $data): ?Lead
    {
        $lead = $this->save($lead, $data);

        event(new LeadWasUpdated($lead));

        return $lead;
    }

    public function getLeads()
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     * @return Lead
     */
    public function findLeadById(int $id): Lead
    {
        return $this->findOneOrFail($id);
    }

    public function getModel()
    {
        return $this->model;
    }

}
