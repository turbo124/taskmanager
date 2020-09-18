<?php


namespace App\Helpers;


class Invitations
{
    public function generateInvitations($entity, $key, $data, $extra_key = null)
    {
        if (empty($data['invitations'])) {
            return true;
        }

        $invitation_class = sprintf("App\Models\\%sInvitation", ucfirst($key));

        $id_key = $extra_key !== null ? $extra_key . '_id' : $key . '_id';

        $old_values = $invitation_class::where($id_key, $entity->id)->get()->pluck('contact_id')->toArray();
        $new_values = array_column(collect($data['invitations'])->toArray(), 'contact_id');

        if ($deleted = Arrays::keysDeleted($new_values, $old_values)) {
            $invitation_class::whereIn('contact_id', $deleted)->forceDelete();
        }

        if ($created = Arrays::keysCreated($new_values, $old_values)) {
            $this->createNewInvitation($created, $key, $entity, $extra_key);
        }

        return true;
    }

    public function createNewInvitation($created, $key, $entity, $extra_key = null)
    {
        $invitation_factory_class = sprintf("App\\Factory\\%sInvitationFactory", ucfirst($key));

        $id_key = $extra_key !== null ? $extra_key . '_id' : $key . '_id';

        foreach ($created as $contact_id) {
            /* $invitation = $invitation__class::where($id_key, $entity->id)->where('contact_id', $contact_id)->first();

            if($invitation) {
                continue;
            } */

            $new_invitation = $invitation_factory_class::create($entity->account, $entity->user);
            $new_invitation->{$id_key} = $entity->id;
            $new_invitation->contact_id = $contact_id;
            $new_invitation->save();
        }

        return true;
    }
}