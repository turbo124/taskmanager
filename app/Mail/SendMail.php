<?php

namespace App\Mail;

use App\ClientContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var string
     */
    private $view_link = '';
    private $view_text = '';

    private $entity;
    private $data;
    /**
     * @var string
     */
    private $design = '';

    /**
     * @var string
     */
    private $template = '';
    private $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($entity, $contact)
    {
        $this->entity = $entity;
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $design = "email.template.{$this->design}";

        return $this->from($this->entity->user->email,
            $this->entity->user->present()->name())
            ->text($design, [
                'body'      => $this->body,
                'view_link' => $this->view_link,
                'view_text' => $this->view_text
            ])
            ->view($design, $this->data);
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): self
    {
        $this->subject($subject);
        return $this;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param mixed $footer
     */
    public function setFooter($footer): self
    {
        if (!empty($footer)) {
            $this->view_link = $footer['link'];
            $this->view_text = $footer['text'];
        }

        $this->footer = $footer;
        return $this;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @param mixed $cc
     */
    public function setCc($cc_address): self
    {
        $this->cc($cc_address);
        return $this;
    }

    /**
     * @param mixed $bcc
     */
    public function setBcc($bcc_address): self
    {
        $this->bcc($bcc_address);
        return $this;
    }

    /**
     * @param mixed $template
     */
    public function setDesign($design): self
    {
        $this->design = $design;
        return $this;
    }

    public function setTemplate($template): self
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @param mixed $replyTo
     */
    public function setReplyTo($reply_to_address): self
    {
        $this->replyTo($reply_to_address);
        return $this;
    }

    /**
     * @param mixed $attachment
     */
    public function setAttachments($attachment): self
    {
        if (empty($attachment)) {
            return $this;
        }

        $this->attach($attachment);
        return $this;
    }

    /**
     * @param mixed $attachmentData
     */
    public function setAttachmentData(string $attachmentData, string $filename): self
    {
        $this->attachData($attachmentData, $filename);
        return $this;
    }
}
