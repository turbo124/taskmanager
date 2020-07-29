<?php


namespace App\Mail\Admin;


use App\Models\User;
use Illuminate\Mail\Mailable;

class AdminMailer extends Mailable
{

    /**
     * @var \App\Models\User
     */
    protected User $user;

    protected $message;

    public $subject;

    /**
     * @var array
     */
    protected array $message_array;

    protected $entity;

    protected function execute()
    {
        $template = get_class($this->entity) !== 'App\Model\Lead' ? $this->entity->customer->getSetting(
            'email_style'
        ) : $this->entity->account->settings->email_style;

        return $this->to($this->user->email)
                    ->from('tamtamcrm@support.com')
                    ->subject($this->subject)
                    ->markdown(
                        empty($template) ? 'email.admin.new' : 'email.template.' . $template,
                        [
                            'data' => $this->message_array,
                        ]
                    );
    }
}