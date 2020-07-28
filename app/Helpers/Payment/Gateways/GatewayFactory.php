<?php


namespace App\Helpers\Payment\Gateways;


use App\Models\CompanyGateway;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class GatewayFactory
{

    public function create(Customer $customer)
    {
        $customer_gateway = $this->getCustomerGateway($customer);
        $company_gateway = $this->getCompanyGateway($customer_gateway->company_gateway_id);

        switch ($company_gateway->gateway_key) {
            case '13bb8d58':
                return new Stripe($customer, $customer_gateway, $company_gateway);
            case '8ab2dce2':
                return new Authorize($customer, $customer_gateway, $company_gateway);
        }
    }

    private function getCustomerGateway(Customer $customer)
    {
        return DB::table('client_gateway_tokens')
                 ->where('account_id', $customer->account_id)
                 ->where('customer_id', $customer->id)
                 ->where('is_default', 1)
                 ->first();
    }

    private function getCompanyGateway($gateway_id)
    {
        return CompanyGateway::where('id', $gateway_id)->first();
    }
}