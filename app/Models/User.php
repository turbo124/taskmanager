<?php

namespace App\Models;

use App\Collection;
use App\Models\File;
use App\Models;
use App\Models\AccountUser;
use App\Models\Upload;
use App\Util\Jobs\FileUploader;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Event;
use App\Traits\HasPermissionsTrait;
use App\Models\Message;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Department;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Account;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements JWTSubject
{

    use Notifiable, SoftDeletes, HasPermissionsTrait, PresentableTrait;
    use HasRelationships;

    protected $presenter = 'App\Presenters\UserPresenter';

    protected $with = ['accounts'];

    public $account;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'profile_photo',
        'username',
        'email',
        'password',
        'role_id',
        'job_description',
        'dob',
        'phone_number',
        'gender',
        'auth_token',
        'account',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'is_active'
    ];

    public function events()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsToMany
     */
    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function department_manager()
    {
        return $this->morphTo();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    /**
     * Returns the current company
     *
     * @return Collection
     */
    public function account()
    {
        return $this->getAccount();
    }

    public function account_users()
    {
        return $this->hasMany(Models\AccountUser::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function account_user()
    {
        if (!$this->id) {
            $this->id = auth()->user()->id;
        }

        return Models\AccountUser::join('company_tokens', 'company_tokens.account_id', '=', 'account_user.account_id')
                                 ->where('company_tokens.user_id', '=', $this->id)
                                 ->where('company_tokens.is_web', '=', true)
                                 ->where('company_tokens.token', '=', $this->auth_token)->select('account_user.*')->first();
    }

    /**
     * Returns a boolean of the administrator status of the user
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->account_user->is_admin;
    }

    public function isOwner(): bool
    {
        return $this->account_user->is_owner;
    }

    /**
     * @return BelongsToMany
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class)->using(Models\AccountUser::class)
                    ->withPivot('permissions', 'settings', 'is_admin', 'is_owner', 'is_locked');
    }


    // Example, just to showcase the API.
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public static function notificationDefaults()
    {
        $notification = new \stdClass;
        $notification->email = [];

        return $notification;
    }

    public function attachUserToAccount(Account $account, $is_admin, array $notifications = [])
    {
        $this->accounts()->attach(
            $account->id,
            [
                'account_id'    => $account->id,
                'is_owner'      => $is_admin,
                'is_admin'      => $is_admin,
                'notifications' => !empty($notifications) ? $notifications : $this->notificationDefaults()
            ]
        );
        return true;
    }
}
