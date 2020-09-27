<?php
use Illuminate\Database\Seeder;
use App\Models\PaymentTerm;

class PaymentTermsSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();
        $paymentTerms = [
            ['name' => 'Apply Credit'],
            ['name' => 'Bank Transfer', 'gateway_type_id' => 2],
            ['name' => 'Cash'],
            ['name' => 'Debit', 'gateway_type_id' => 1],
            ['name' => 'PayPal', 'gateway_type_id' => 3],
            ['name' => 'Check'],
        ];
        foreach ($paymentTerms as $paymentTerm) {
            $record = \App\Models\PaymentTerm::where('name', '=', $paymentType['name'])->first();
            if ($record) {
                $record->name = $paymentTerm['name'];
                $record->gateway_type_id = !empty($paymentType['gateway_type_id']) ? $paymentType['gateway_type_id'] : null;
                $record->save();
            } else {
                \App\Models\PaymentTerm::create($paymentTerm);
            }
        }
    }
}
