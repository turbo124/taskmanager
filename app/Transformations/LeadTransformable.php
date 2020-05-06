<?php

namespace App\Transformations;

use App\Address;
use App\ClientContact;
use App\Customer;
use App\Email;
use App\Lead;

trait LeadTransformable
{
    /**
     * @param Lead $lead
     * @return array
     */
    protected function transformLead(Lead $lead)
    {

        return [
            'id'               => (int)$lead->id,
            'website'          => $lead->website ?: '',
            'industry_id'      => (int)$lead->industry_id,
            'created_at'       => $lead->created_at,
            'first_name'       => $lead->first_name,
            'last_name'        => $lead->last_name,
            'title'            => $lead->title,
            'description'      => $lead->description,
            'valued_at'        => $lead->valued_at,
            'source_type'      => $lead->source_type,
            'task_status'      => $lead->task_status,
            'address_1'        => $lead->address_1,
            'address_2'        => $lead->address_2,
            'city'             => $lead->city,
            'zip'              => $lead->zip,
            'email'            => $lead->email,
            'phone'            => $lead->phone,
            'company_name'     => $lead->company_name,
            'job_title'        => $lead->job_title,
            'assigned_user_id' => (int)$lead->assigned_user_id,
            'deleted_at'       => $lead->deleted_at,
            'website'          => $lead->website ?: '',
            'private_notes'    => $lead->private_notes ?: '',
            'public_notes'     => $lead->public_notes ?: '',
            'emails'           => $this->transformLeadEmails($lead->emails()),
        ];

    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformLeadEmails($emails)
    {

        if ($emails->count() === 0) {
            return [];
        }

        return $emails->map(function (Email $email) {
            return (new EmailTransformable())->transformEmail($email);
        })->all();
    }
}
