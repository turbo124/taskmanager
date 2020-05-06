<?php

namespace App\Console\Commands;

use App\Email;
use App\ClientContact;
use DateTime;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Auth;
use Exception;
use App\Libraries\Utils;
use Illuminate\Support\Carbon;

/**
 * Class EmailFailures.
 */
class EmailFailures extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:failures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend any failed emails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $failed_emails = Email::where('sent_at', '=', '')
                              ->orWhereNull('sent_at')
                              ->get();

        foreach ($failed_emails as $failed_email) {
            $entity_string = $failed_email->entity;
            $entity = $entity_string::where('id', $failed_email->entity_id)->first();

            if (!$entity) {
                continue;
            }

            $contact = $entity_string === 'App\\Lead' ? $entity : ClientContact::where('email', $failed_email->recipient_email)->first();
            $contact = empty($contact) ? null : $contact;

            $entity->service()->sendEmail($contact, $failed_email->subject, $failed_email->body);
            $failed_email->sent_at = Carbon::now();
            $failed_email->save();
        }
    }
}
