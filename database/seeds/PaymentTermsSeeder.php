<?php
use Illuminate\Database\Seeder;
use App\Models\PaymentTerms;

class PaymentTermsSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();
        $paymentTerms = [
            ['name' => 'Immediate', 'number_of_days' => 1],
            ['name' => 'Net 30', 'number_of_days' => 30],
            ['name' => 'Net 45', 'number_of_days' => 45],
            ['name' => 'Net 60', 'number_of_days' => 60],
           
        ];
        foreach ($paymentTerms as $paymentTerm) {
            $record = \App\Models\PaymentTerms::where('name', '=', $paymentType['name'])->first();
            if ($record) {
                $record->name = $paymentTerm['name'];
                $record->gateway_type_id = !empty($paymentType['gateway_type_id']) ? $paymentType['gateway_type_id'] : null;
                $record->save();
            } else {
                \App\Models\PaymentTerms::create($paymentTerm);
            }
        }
    }
}
