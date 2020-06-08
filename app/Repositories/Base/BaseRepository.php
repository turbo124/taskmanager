<?php

namespace App\Repositories\Base;

use App\ClientContact;
use App\Factory\InvoiceInvitationFactory;
use App\InvoiceInvitation;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use App\Helpers\Arrays;

class BaseRepository implements BaseRepositoryInterface
{

    protected $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        return $this->model->update($data);
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = ['*'], string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param  $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function findBy(array $data)
    {
        return $this->model->where($data)->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneByOrFail(array $data)
    {
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function delete(): bool
    {
        return $this->model->delete();
    }

    /**
     * @param $entity
     */
    public function archive($entity)
    {
        $entity->delete();

        $entity_class = (new \ReflectionClass($entity))->getShortName();
        $event_class = "App\Events\\" . $entity_class . "\\" . $entity_class . "WasArchived";

        if (class_exists($event_class)) {
            event(new $event_class($entity));
        }

        return true;
    }

    /**
     * @param $entity
     */
    public function restore($entity)
    {
        $entity->restore();
        $entity->is_deleted = false;
        $entity->save();
    }

    /**
     * @param $entity
     * @return |null
     * @throws \ReflectionException
     */
    public function markSent($entity)
    {
        $allowed_statuses[] = $entity::STATUS_DRAFT;

        if (get_class($entity) === 'App\Order') {
            if (!$entity->invoice_id) {
                return null;
            }

            $allowed_statuses[] = $entity::STATUS_COMPLETE;
        }

        if (!in_array($entity->status_id, $allowed_statuses)) {
            return $entity;
        }

        $entity->invitations()->where('sent_date', '=', null)->update(['sent_date' => Carbon::now()]);

        $entity->setStatus($entity::STATUS_SENT);
        $entity->save();

        $service = $entity->service();

        if (method_exists($service, 'send')) {
            $service->send();
        }

        $class = (new \ReflectionClass($entity))->getShortName();
        $event_class = "App\Events\\" . $class . "\\" . $class . "WasMarkedSent";

        if (class_exists($event_class)) {
            event(new $event_class($entity));
        }

        return $entity;
    }

    /**
     * @param $entity
     */
    public function newDelete($entity)
    {
        $entity->is_deleted = true;
        $entity->save();
        $entity->delete();

        $entity_class = (new \ReflectionClass($entity))->getShortName();
        $event_class = "App\Events\\" . $entity_class . "\\" . $entity_class . "WasDeleted";

        if (class_exists($event_class)) {
            event(new $event_class($entity));
        }

        return true;
    }

    /**
     * Paginate arrays
     *
     * @param array $data
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateArrayResults(array $data, int $perPage = 50)
    {
        $page = request()->input('page', 1);
        $offset = ($page * $perPage) - $perPage;
        return new LengthAwarePaginator(
            array_values(array_slice($data, $offset, $perPage, true)), count($data),
            $perPage, $page, [
                'path'  => app('request')->url(),
                'query' => app('request')->query()
            ]
        );
    }

    public function paginateCollection($items, $perPage = 15, $options = [])
    {
        $page = Input::get('page', 1);
        //$items = $items->forPage($page, $perPage); //Filter the page var


        return new LengthAwarePaginator(
            $items->forPage($page, $perPage), count($items) ?: $this->count(), $perPage,
            $page, [
                'path'  => app('request')->url(),
                'query' => app('request')->query(),
            ]
        );
    }

    public function getInvitation($invitation, $resource)
    {
        if (!isset($invitation['key'])) {
            return false;
        }

        $invitation_class = sprintf("App\\%sInvitation", ucfirst($resource));

        $invitation = $invitation_class::whereRaw("BINARY `key`= ?", [$invitation['key']])->first();

        return $invitation;
    }

    /**
     * @param $entity
     * @param $key
     * @param array $data
     * @return bool
     */
    protected function saveInvitations($entity, $key, array $data): bool
    {
        if (empty($data['invitations']) && $entity->invitations->count() === 0) {
            $created = $entity->customer->contacts->pluck('id')->toArray();
            $this->createNewInvitation($created, $key, $entity);

            return true;
        }

        $invitation_class = sprintf("App\\%sInvitation", ucfirst($key));

        $id_key = $key . '_id';

        $old_values = $invitation_class::where($id_key, $entity->id)->get()->pluck('client_contact_id')->toArray();
        $new_values = array_column(collect($data['invitations'])->toArray(), 'client_contact_id');

        if ($deleted = Arrays::keysDeleted($new_values, $old_values)) {
            $invitation_class::whereIn('client_contact_id', $deleted)->forceDelete();
        }

        if ($created = Arrays::keysCreated($new_values, $old_values)) {
            $this->createNewInvitation($created, $key, $entity);
        }

        return true;
    }

    private function createNewInvitation($created, $key, $entity)
    {
        $invitation_factory_class = sprintf("App\\Factory\\%sInvitationFactory", ucfirst($key));
        $id_key = $key . '_id';

        foreach ($created as $contact_id) {
            /* $invitation = $invitation__class::where($id_key, $entity->id)->where('client_contact_id', $contact_id)->first();

            if($invitation) {
                continue;
            } */

            $new_invitation = $invitation_factory_class::create($entity->account_id, $entity->user_id);
            $new_invitation->{$id_key} = $entity->id;
            $new_invitation->client_contact_id = $contact_id;
            $new_invitation->save();
        }

        return true;
    }

    protected function populateDefaults($entity)
    {
        $class = strtolower((new \ReflectionClass($entity))->getShortName());

        if (empty($entity->terms) && !empty($entity->customer->getSetting($class . '_terms'))) {
            $entity->terms = $entity->customer->getSetting($class . '_terms');
        }
        if (empty($entity->footer) && !empty($entity->customer->getSetting($class . '_footer'))) {
            $entity->footer = $entity->customer->getSetting($class . '_footer');
        }
        if (empty($entity->public_notes) && !empty($entity->customer->public_notes)) {
            $entity->public_notes = $entity->customer->public_notes;
        }

        return $entity;
    }

}
