<?php

namespace App\Events\Uploads;

use App\Models\Account;
use App\Models\File;
use App\Models\Lead;
use App\Traits\SendSubscription;
use Illuminate\Queue\SerializesModels;

/**
 * Class FileWasUploaded
 * @package App\Events\Uploads
 */
class FileWasDeleted
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
