<?php

namespace App\Http\Controllers;

use App\ClientContact;
use App\Customer;
use App\Address;
use App\Invoice;
use App\Traits\MakesInvoiceHtml;
use Illuminate\Support\Facades\DB;


class PreviewController extends Controller
{
    use MakesInvoiceHtml;

    public function __construct()
    {
    }

    /**
     * Returns a template filled with entity variables
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function show()
    {

        if (request()->has('entity') &&
            request()->has('entity_id') &&
            strlen(request()->input('entity')) > 1 &&
            strlen(request()->input('entity_id')) > 1 && request()->has('design')) {

            $design_object = json_decode(json_encode(request()->input('design')));

            if (!is_object($design_object)) {
                return response()->json(['message' => 'Invalid custom design object'], 400);
            }

            $design_object->design->product = '';
            $design_object->design->task = '';

            $entity = ucfirst(request()->input('entity'));

            $class = "App\\$entity";

            $entity_obj = $class::whereId(request()->input('entity_id'))->first();

            if (!$entity_obj) {
                return $this->blankEntity();
            }

            $file_path = $entity_obj->service()->getPdf();

            return response()->json(['data' => base64_encode(file_get_contents($file_path))]);

        }

        return $this->blankEntity();

    }

    private function blankEntity()
    {
        DB::beginTransaction();

        $client = factory(Customer::class)->create([
            'user_id'    => auth()->user()->id,
            'account_id' => auth()->user()->account_user()->account_id,
        ]);

        $contact = factory(ClientContact::class)->create([
            'user_id'     => auth()->user()->id,
            'account_id'  => auth()->user()->account_user()->account_id,
            'customer_id' => $client->id,
            'is_primary'  => 1,
            'send_email'  => true,
        ]);

        $address = factory(Address::class)->create([
            'customer_id' => $client->id,
            'address_type'  => 1,
        ]);

        $invoice = factory(Invoice::class)->create([
            'user_id'     => auth()->user()->id,
            'account_id'  => auth()->user()->account_user()->account_id,
            'customer_id' => $client->id,
        ]);

        $file_path = $invoice->service()->getPdf();

        $invoice->forceDelete();
        $contact->forceDelete();
        $client->forceDelete();

        DB::rollBack();

        return response()->json(['data' => base64_encode(file_get_contents($file_path))]);

    }
}
