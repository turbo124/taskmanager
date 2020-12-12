<?php

namespace App\Services\Mail;

class BaseMailHandler
{
    public function storeAttachments(Stream $stream, Email $email)
    {
        $attachments = [];

        foreach ($email->attachments() as $attachment) {
            $attachments[] = $stream->addDocuments($this->attach($stream, $attachment));
        }

        return $attachments;
    }

    /**
     * Stores the attachments on the server
     * @return array ['name', 'path']
     */
    public function attach(Stream $stream, $attachment)
    {
        $filename = $this->getFileName($attachment);

        $this->storeFileOnServer($stream, $filename, $attachment);

        return [
            'name' => $filename,
            'path' => $stream->token . '/' . $filename
        ];
    }

    /**
     * Generates a filename for our attachment.
     * @return string (filename.ext)
     */
    public function getFileName($attachment)
    {
        return uniqid() . '.' . File::extension($attachment->getFilename());
    }

    /**
     * Stores the actual file: streamtoken/filename.ext
     * @return boolean
     */
    public function storeFileOnServer(Stream $stream, $filename, $attachment)
    {
        return Storage::put($stream->token . '/' . $filename, $attachment->getContent());
    }
}
