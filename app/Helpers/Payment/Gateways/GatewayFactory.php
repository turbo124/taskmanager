<?php


namespace App\Helpers\Payment\Gateways;


use App\CompanyGateway;
use App\Customer;
use App\Invoice;
use Illuminate\Support\Facades\DB;

class GatewayFactory
{

    public function create(Customer $customer)
    {
        $customer_gateway = $this->getCustomerGateway($customer);
        $company_gateway = $this->getCompanyGateway($customer_gateway->company_gateway_id);

        switch ($company_gateway->gateway_key) {
            case 'd14dd26a37cecc30fdd65700bfb55b23':
                return new Stripe($customer, $customer_gateway, $company_gateway);
            case '3b6621f970ab18887c4f6dca78d3f8bb':
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