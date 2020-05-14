<?php

namespace App\Utils;

use App\DataMapper\EmailTemplateDefaults;
use App\PdfData;
use App\Traits\MakesInvoiceHtml;
use League\CommonMark\CommonMarkConverter;

class TemplateEngine
{
    use MakesInvoiceHtml;

    public $body;

    public $subject;

    public $template;

    /**
     * @var
     */
    private $objPdf;


    /**
     * TemplateEngine constructor.
     * @param PdfData $objPdf
     * @param $body
     * @param $subject
     * @param $entity
     * @param $entity_id
     * @param $template
     */
    public function __construct(PdfData $objPdf, $body, $subject, $entity, $entity_id, $template)
    {
        $this->body = $body;

        $this->subject = $subject;

        $this->template = $template;

        $this->objPdf = $objPdf;
    }

    public function build()
    {
        $entity_obj = $this->objPdf->getEntity();

        $this->objPdf->build();
        $labels = $this->objPdf->getLabels();
        $values = $this->objPdf->getValues();

        $subject = strlen($this->subject) > 0 ? $this->subject : $entity_obj->account->settings->{$subject_template};
        $body = strlen($this->body) > 0 ? $this->body : $entity_obj->account->settings->{$this->template};

        $subject_template = str_replace("template", "subject", $this->template);
        $subject = $this->objPdf->parseLabels($labels, $subject);
        $body = $this->objPdf->parseValues($values, $body);

        $converter = new CommonMarkConverter(
            [
                'allow_unsafe_links' => false,
            ]
        );

        $body = $converter->convertToHtml($body);

        return $this->render($subject, $body, $entity_obj);
    }

    private function render($subject, $body, $entity_obj)
    {
        $email_style = $entity_obj->account->settings->email_style;
        $wrapper = view('email.template.' . $email_style, ['body' => $body])->render();

        return [
            'subject' => $subject,
            'body'    => $body,
            'wrapper' => $wrapper
        ];
    }
}
