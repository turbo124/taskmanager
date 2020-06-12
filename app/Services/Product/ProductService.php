<?php

namespace App\Services\RecurringInvoice;

use App\Invoice;
use App\RecurringInvoice;
use App\Repositories\CreditRepository;
use App\Repositories\PaymentRepository;
use App\Payment;
use App\Services\Customer\CustomerService;
use App\Services\Invoice\HandleCancellation;
use App\Services\Invoice\HandleReversal;
use App\Services\Invoice\ApplyNumber;
use App\Services\Invoice\MarkSent;
use App\Services\Invoice\UpdateBalance;
use Illuminate\Support\Carbon;
use App\Services\Invoice\ApplyPayment;
use App\Services\Invoice\CreateInvitations;
use App\Services\ServiceBase;

class ProductService extends ServiceBase
{
    private $product;

    /**
     * ProductService constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct($product);
        $this->product = $product;
    }

    public function createProduct(ProductRepository $product_repo, array $data): Product
    {
        return (new CreateProduct($this->product))->execute();
    }
}
