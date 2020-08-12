<?php


namespace App\Mail\Admin;

use App\Events\EmailFailedToSend;
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
        $template = get_class($this->entity) !== 'App\Models\Lead' ? $this->entity->customer->getSetting(
            'email_style'
        ) : $this->entity->account->settings->email_style;

        try {
            return $this->to($this->user->email)
                        ->from('tamtamcrm@support.com')
                        ->subject($this->subject)
                        ->markdown(
                            empty($template) ? 'email.admin.new' : 'email.template.' . $template,
                            [
                                'data' => $this->message_array,
                            ]
                        );
        } catch (\Exception $exception) {
            event(new EmailFailedToSend($this->entity, $exception->getMessage()));
        }
    }
}