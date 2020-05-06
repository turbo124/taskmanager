<?php
/**
 * Created by PhpStorm.
 * User: michael.hampton
 * Date: 22/12/2019
 * Time: 13:02
 */

namespace App;


use App\Services\Order\OrderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Laracasts\Presenter\PresentableTrait;
use App\NumberGenerator;
use App\Utils\Number;

/**
 * Class Order
 * @package App
 */
class Order extends Model
{
    use PresentableTrait;
    use SoftDeletes;

    protected $presenter = 'App\Presenters\OrderPresenter';

    const STATUS_DRAFT = 1;
    const STATUS_SENT = 2;
    const STATUS_APPROVED = 4;
    const STATUS_COMPLETE = 3;
    const STATUS_EXPIRED = -1;

    protected $casts = [
        'line_items' => 'object',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
        'is_deleted' => 'boolean',
    ];

    protected $fillable = [
        'number',
        'customer_id',
        'total',
        'balance',
        'sub_total',
        'tax_total',
        'tax_rate',
        'tax_rate_name',
        'discount_total',
        'is_amount_discount',
        'due_date',
        'status_id',
        'created_at',
        'line_items',
        'public_notes',
        'private_notes',
        'terms',
        'footer',
        'partial',
        'partial_due_date',
        'date',
        'balance',
        'task_id',
        'custom_value1',
        'custom_value2',
        'custom_value3',
        'custom_value4',
        'custom_surcharge1',
        'custom_surcharge2',
        'custom_surcharge3',
        'custom_surcharge4',
        'custom_surcharge_tax1',
        'custom_surcharge_tax2',
        'custom_surcharge_tax3',
        'custom_surcharge_tax4',
        'design_id'
    ];

    protected $table = 'product_task';

    public function service(): OrderService
    {
        return new OrderService($this);
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function emails()
    {
        return Email::whereEntity(get_class($this))->whereEntityId($this->id)->get();
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'documentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function invitations()
    {
        return $this->hasMany(OrderInvitation::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /********************** Getters and setters ************************************/

    public function setStatus(int $status)
    {
        $this->status_id = $status;
    }

    public function setInvoiceId($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }

    public function setNumber()
    {
        if (!empty($this->number)) {
            return true;
        }

        $this->number = (new NumberGenerator)->getNextNumberForEntity($this->customer, $this);
        return true;
    }

    public function getFormattedTotal()
    {
        return Number::formatCurrency($this->total, $this->customer);
    }

    public function getFormattedSubtotal()
    {
        return Number::formatCurrency($this->sub_total, $this->customer);
    }

    public function getFormattedBalance()
    {
        return Number::formatCurrency($this->balance, $this->customer);
    }

    public function getDesignId()
    {
        return !empty($this->design_id) ? $this->design_id : $this->customer->getSetting('order_design_id');
    }

    public function getPdfFilename()
    {
        return 'storage/' . $this->account->id . '/' . $this->customer->id . '/orders/' . $this->number . '.pdf';
    }
}
