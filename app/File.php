<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task_id',
        'name',
        'file_path',
        'user_id',
        'account_id'
    ];

    /**
     * @var array
     */
    public static $types = [
        'png'  => [
            'mime' => 'image/png',
        ],
        'ai'   => [
            'mime' => 'application/postscript',
        ],
        'svg'  => [
            'mime' => 'image/svg+xml',
        ],
        'jpeg' => [
            'mime' => 'image/jpeg',
        ],
        'tiff' => [
            'mime' => 'image/tiff',
        ],
        'pdf'  => [
            'mime' => 'application/pdf',
        ],
        'gif'  => [
            'mime' => 'image/gif',
        ],
        'psd'  => [
            'mime' => 'image/vnd.adobe.photoshop',
        ],
        'txt'  => [
            'mime' => 'text/plain',
        ],
        'doc'  => [
            'mime' => 'application/msword',
        ],
        'xls'  => [
            'mime' => 'application/vnd.ms-excel',
        ],
        'ppt'  => [
            'mime' => 'application/vnd.ms-powerpoint',
        ],
        'xlsx' => [
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
        'docx' => [
            'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'pptx' => [
            'mime' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ],
    ];

    /**
     * @var array
     */
    public static $extraExtensions = [
        'jpg' => 'jpeg',
        'tif' => 'tiff',
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    function generateUrl($absolute = false)
    {
        $url = public_path($this->file_path);

        if ($url && $absolute) {
            return url($url);
        }

        if ($url) {
            return $url;
        }

        return null;
    }
}
