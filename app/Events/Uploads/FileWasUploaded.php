<?php

namespace App\Events\Uploads;

use App\Account;
use App\File;
use App\Lead;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class FileWasUploaded
 * @package App\Events\Uploads
 */
class FileWasUploaded
{
    use SerializesModels;

    /**
     * @var File
     */
    public File $file;

    /**
     * FileWasUploaded constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }
}
