<?php

namespace App\Jobs\Email;

use App\Designs\Custom;
use App\PdfData;
use Illuminate\Support\Carbon;
use App\Designs\Clean;
use App\Designs\PdfColumns;
use App\Design;
use App\Email;
use App\Account;
use App\Invoice;
use App\Jobs\Invoice\CreateUbl;
use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\ClientContact;
use App\Factory\EmailFactory;
use App\Repositories\EmailRepository;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $entity;

    private $subject;

    private $contact;

    private $body;

    private $designer;

    private $footer;

    private $template;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($entity, $subject, $body, $template, $contact = null, array $footer = [])
    {
        $this->entity = $entity;
        $this->contact = $contact;
        $this->subject = $subject;
        $this->body = $body;
        $this->footer = $footer;
        $this->template = $template;
    }

    public function handle()
    {
        $settings = $this->entity->account->settings;

        $objPdf = new PdfData($this->entity);
        $objPdf->build();
        $labels = $objPdf->getLabels();
        $values = $objPdf->getValues();

        $this->subject = $objPdf->parseLabels($labels, $this->subject);
        $body = $objPdf->parseValues($values, $this->body);
        $design_style = $settings->email_style;

        if ($design_style == 'custom') {
            $email_style_custom = $settings->email_style_custom;
            $body = str_replace("$body", $body, $email_style_custom);
        }

        $message = (new SendMail($this->entity, $this->contact))
            ->setData($this->buildMailMessageData($settings, $body, $design_style))
            ->setBody($body)
            ->setFooter($this->footer)
            ->setDesign($design_style)
            ->setTemplate($this->template)
            ->setSubject($this->subject);


        if (strlen($settings->reply_to_email) > 0) {
            $message->setReplyTo($settings->reply_to_email);
        }

        if (strlen($settings->bcc_email) > 0) {
            $message->setBcc($settings->bcc_email);
        }

        if ($settings->pdf_email_attachment && get_class($this->entity) !== 'App\Lead' && get_class(
                $this->entity
            ) !== 'App\Payment') {
            $message->setAttachments(public_path($this->entity->service()->generatePdf($this->contact)));
        }

        foreach ($this->entity->documents as $document) {
            $message->setAttachments($document->generateUrl(), ['as' => $document->name]);
        }

        if ($this->entity instanceof Invoice && $settings->ubl_email_attachment) {
            $ubl_string = CreateUbl::dispatchNow($this->entity);
            $file_name = $this->entity->number . '.xml';
            $message->setAttachmentData($ubl_string, $file_name);
        }

        Mail::to($this->contact->email, $this->contact->present()->name())
            ->send($message);

        $sent_successfully = count(Mail::failures()) === 0;

        $this->toDatabase($this->subject, $body, $sent_successfully);

        return $message;
    }

    private function buildMailMessageData($settings, $body, $design): array
    {
        $data = [
            'view_link' => !empty($this->footer) ? $this->footer['link'] : '',
            'view_text' => !empty($this->footer) ? $this->footer['text'] : '',
            'body'      => $body,
            'design'    => $design,
            'footer'    => $this->footer,
            'title'     => $this->subject,
            'settings'  => $settings,
            'company'   => $this->entity->account,
            'logo'      => $this->entity->account->present()->logo(),
            'signature' => !empty($this->entity->account->settings->email_signature) ? $this->entity->account->settings->email_signature : '',

        ];

        return $data;
    }

    private function toDatabase($subject, $body, $sent_successfully)
    {
        if (empty(auth()->user())) {
            return false;
        }

        $email = EmailFactory::create(auth()->user()->id, auth()->user()->account_user()->account_id);

        (new EmailRepository(new Email))->save(
            [
                'subject'         => $subject,
                'body'            => $body,
                'entity'          => get_class($this->entity),
                'entity_id'       => $this->entity->id,
                'recipient'       => $this->contact->present()->name,
                'recipient_email' => $this->contact->present()->email,
                'sent_at'         => $sent_successfully === true ? Carbon::now() : null
            ],
            $email
        );
    }
}
