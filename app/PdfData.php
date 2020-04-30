<?php

namespace App;

use App\Utils\Number;

class PdfData
{
    private $labels;
    private $values;
    private $data;
    private $entity;
    private $line_items;
    private $design_html = '';

    /**
     * PdfData constructor.
     * @param $entity
     * @param ClientContact $contact
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
       
    }

    public function build($contact = null) {

        $this->data = [];

        if(get_class($this->entity) === 'App\Lead') {
            return $this->buildLead();
        }
       
        return $this->buildInvoice($contact);
    }

    private function buildLead($contact = null)
    {

        $this->buildClientForLead($this->entity)
            ->buildAddress($this->entity, $this->entity)
            ->buildAccount($this->entity->account);

        foreach ($this->data as $key => $value) {
            if (isset($value['label'])) {
                $this->labels[$key . '_label'] = $value['label'];
            }

            if (isset($value['value'])) {
                $this->values[$key] = $value['value'];
            }
        }

        return $this;

    }

    private function buildInvoice($contact = null)
    {

        $contact === null ? $this->entity->customer->contacts->first() : $contact;       
        $customer = $this->entity->customer;

        $this->setDefaults($customer)
            ->buildContact($contact)
            ->setTaxes($customer)
            ->setDate($this->entity->date)
            ->setDueDate($this->entity->due_date)
            ->setNumber($this->entity->number)
            ->setPoNumber($this->entity->po_number)            
            ->buildCustomer($customer)
            ->buildCustomerAddress($customer)
            ->buildAccount($this->entity->account)
            ->setTerms($this->entity->terms)
            ->setDiscount($customer, $this->entity->discount_total)
            ->setSubTotal($customer, $this->entity->sub_total)
            ->setBalance($customer, $this->entity->balance)
            ->setTotal($customer, $this->entity->total)
            ->setNotes($this->entity->public_notes)
            ->setInvoiceCustomValues()
            ->buildProduct()
            ->transformLineItems($customer, $this->entity)
            ->buildTask()
            ;

        foreach ($this->data as $key => $value) {
            if (isset($value['label'])) {
                $this->labels[$key . '_label'] = $value['label'];
            }

            if (isset($value['value'])) {
                $this->values[$key] = $value['value'];
            }
        }

        return $this;

    }

    private function setDefaults(Customer $customer): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$entity_label']       =  ['value' => '', 'label' => trans('texts.' . $class)];
        $this->data['$invoice.partial_due'] = ['value' => Number::formatMoney($this->entity->partial, $customer) ?: '&nbsp;', 'label' => trans('texts.partial_due_label')];
        $this->data['$from']                   = ['value' => '', 'label' => trans('texts.from')];
        $this->data['$to']                     = ['value' => '', 'label' => trans('texts.to')];
        return $this;
    }

    private function findCustomType($entity, $field)
    {
        $custom_fields = $this->account->custom_fields;

        if (!isset($custom_fields->{$entity})) {
            return '';
        }

        $new_array = array_filter($custom_fields->{$entity}, function ($obj) use ($field) {

            if ($field === $obj->name) {
                return $obj;
            }
        });

        if (empty($new_array) || empty($new_array[0]->type)) {
            return '';
        }

        return $new_array[0]->type;
    }

    private function makeCustomFieldKeyValuePair($entity, $field, $value)
    {
        if ($this->findCustomType($entity, $field) == 'date')
            $value = date('d-m-Y', strtotime($value));

        if (!$value)
            $value = '';

        return ['value' => $value, 'field' => $this->makeCustomField($entity, $field)];
    }

    private function makeCustomField($entity, $field): string
    {
        $custom_fields = $this->entity->account->custom_fields;

        if (!isset($custom_fields->{$entity})) {
            return '';
        }

        $new_array = array_filter($custom_fields->{$entity}, function ($obj) use ($field) {

            if ($field === $obj->name) {
                return $obj;
            }
        });

        if (empty($new_array) || empty($new_array[0]->label)) {
            return '';
        }

        return $new_array[0]->label;
    }

    public function buildContact(ClientContact $contact = null): self
    {
        if ($contact === null) {
            return $this;
        }

        $this->data['$contact.full_name'] = ['value' => $contact->present()->name(), 'label' => trans('texts.name')];
        $this->data['$contact.email'] = ['value' => $contact->email, 'label' => trans('texts.email_address')];
        $this->data['$contact.phone'] = ['value' => $contact->phone, 'label' => trans('texts.phone_number')];

        $this->data['$contact_name'] = ['value' => $contact->present()->name(), 'label' => trans('texts.contact_name')];
        $this->data['$contact.custom1'] = ['value' => isset($contact) ? $contact->custom_value1 : '&nbsp;', 'label' => $this->makeCustomField('Contact', 'custom_value1')];
        $this->data['$contact.custom2'] = ['value' => isset($contact) ? $contact->custom_value2 : '&nbsp;', 'label' => $this->makeCustomField('Contact', 'custom_value2')];
        $this->data['$contact.custom3'] = ['value' => isset($contact) ? $contact->custom_value3 : '&nbsp;', 'label' => $this->makeCustomField('Contact', 'custom_value3')];
        $this->data['$contact.custom4'] = ['value' => isset($contact) ? $contact->custom_value4 : '&nbsp;', 'label' => $this->makeCustomField('Contact', 'custom_value4')];
        return $this;
    }

    public function buildCustomer(Customer $customer): self
    {

        $this->data['$customer.id_number'] = ['value' => $customer->id_number ?: '&nbsp;', 'label' => trans('texts.customer_id_number')];
        $this->data['$customer.vat_number'] = ['value' => $customer->vat_number ?: '&nbsp;', 'label' => trans('texts.vat_number')];
        $this->data['$customer.website'] = ['value' => $customer->present()->website() ?: '&nbsp;', 'label' => trans('texts.website')];
        $this->data['$customer.phone'] = ['value' => $customer->present()->phone() ?: '&nbsp;', 'label' => trans('texts.phone_number')];
        $this->data['$customer.email'] = ['value' => isset($customer->primary_contact()->first()->email) ? $customer->primary_contact()->first()->email : 'no contact email on record', 'label' => trans('texts.email_address')];
        $this->data['$customer.name'] = ['value' => $this->entity->present()->clientName() ?: '&nbsp;', 'label' => trans('texts.customer_name')];
        $this->data['$customer1'] = ['value' => $customer->custom_value1 ?: '&nbsp;', 'label' => $this->makeCustomField('Customer', 'custom_value1')];
        $this->data['$customer2'] = ['value' => $customer->custom_value2 ?: '&nbsp;', 'label' => $this->makeCustomField('Customer', 'custom_value2')];
        $this->data['$customer3'] = ['value' => $customer->custom_value3 ?: '&nbsp;', 'label' => $this->makeCustomField('Customer', 'custom_value3')];
        $this->data['$customer4'] = ['value' => $customer->custom_value4 ?: '&nbsp;', 'label' => $this->makeCustomField('Customer', 'custom_value4')];

        return $this;

    }

    public function buildClientForLead(Lead $lead): self
    {

        $this->data['$customer.website'] = ['value' => $lead->present()->website() ?: '&nbsp;', 'label' => trans('texts.website')];
        $this->data['$customer.phone'] = ['value' => $lead->present()->phone() ?: '&nbsp;', 'label' => trans('texts.phone_number')];
        $this->data['$customer.email'] = ['value' => $lead->email, 'label' => trans('texts.email_address')];
        $this->data['$customer.name'] = ['value' => $lead->present()->name() ?: '&nbsp;', 'label' => trans('texts.customer_name')];
        $this->data['$customer1'] = ['value' => $lead->custom_value1 ?: '&nbsp;', 'label' => $this->makeCustomField('Lead', 'custom_value1')];
        $this->data['$customer2'] = ['value' => $lead->custom_value2 ?: '&nbsp;', 'label' => $this->makeCustomField('Lead', 'custom_value2')];
        $this->data['$customer3'] = ['value' => $lead->custom_value3 ?: '&nbsp;', 'label' => $this->makeCustomField('Lead', 'custom_value3')];
        $this->data['$customer4'] = ['value' => $lead->custom_value4 ?: '&nbsp;', 'label' => $this->makeCustomField('Lead', 'custom_value4')];

        return $this;

    }

    public function buildTask(): self
    {
        $this->data['$task.date'] = ['value' => '', 'label' => trans('texts.date')];
        $this->data['$task.discount'] = ['value' => '', 'label' => trans('texts.discount')];
        $this->data['$task.product_key'] = ['value' => '', 'label' => trans('texts.product_name')];
        $this->data['$task.notes'] = ['value' => '', 'label' => trans('texts.notes')];
        $this->data['$task.cost'] = ['value' => '', 'label' => trans('texts.cost')];
        $this->data['$task.quantity'] = ['value' => '', 'label' => trans('texts.quantity')];
        $this->data['$task.tax_name1'] = ['value' => '', 'label' => trans('texts.tax')];
        $this->data['$task.tax_name2'] = ['value' => '', 'label' => trans('texts.tax')];
        $this->data['$task.tax_name3'] = ['value' => '', 'label' => trans('texts.tax')];
        $this->data['$task.line_total'] = ['value' => '', 'label' => trans('texts.sub_total')];
        return $this;
    }

    public function buildProduct(): self
    {
        $this->data['$product.date'] = ['value' => '', 'label' => trans('texts.date')];
        $this->data['$product.discount'] = ['value' => '', 'label' => trans('texts.discount')];
        $this->data['$product.product_key'] = ['value' => '', 'label' => trans('texts.product_name')];
        $this->data['$product.notes'] = ['value' => '', 'label' => trans('texts.notes')];
        $this->data['$product.cost'] = ['value' => '', 'label' => trans('texts.cost')];
        $this->data['$product.quantity'] = ['value' => '', 'label' => trans('texts.quantity')];
        $this->data['$product.tax_name1'] = ['value' => '', 'label' => trans('texts.tax')];
        $this->data['$product.tax_name2'] = ['value' => '', 'label' => trans('texts.tax')];
        $this->data['$product.tax_name3'] = ['value' => '', 'label' => trans('texts.tax')];
        $this->data['$product.line_total'] = ['value' => '', 'label' => trans('texts.sub_total')];
        return $this;
    }

    public function buildAddress($entity, $address) {
        $this->data['$customer.address1'] = ['value' => $address->address_1 ?: '&nbsp;', 'label' => trans('texts.address')];
        $this->data['$customer.address2'] = ['value' => $address->address_2 ?: '&nbsp;', 'label' => trans('texts.address')];
        $this->data['$customer.city_state_postal'] = ['value' => isset($address->city) ? $entity->present()->cityStateZip($address->city, $address->state_code, $address->zip, false) : '&nbsp;', 'label' => trans('texts.city_with_zip')];
        $this->data['$postal_city_state']         = ['value' => $entity->present()->cityStateZip($address->city, $address->state, $entity->postal_code, true) ?: '&nbsp;', 'label' => trans('texts.zip_with_city')];
        $this->data['$customer.country'] = ['value' => isset($address->country->name) ? $address->country->name : 'No Country Set', 'label' => trans('texts.country')];

        return $this;
    }


    public function buildCustomerAddress(Customer $customer): self
    {
        $this->data['$customer.address1'] = ['value' => $customer->present()->address() ?: '&nbsp;', 'label' => trans('texts.address')];

        $addresses = $customer->addresses;
        $billing = null;
        $shipping = null;

        if ($addresses->count() > 0) {
            foreach ($addresses as $address) {
                if ($address->address_type === 1) {
                    $billing = $address;
                } else {
                    $shipping = $address;
                }
            }
        }

        if(!empty($billing)) {
            $this->buildAddress($customer, $billing);
        }
       
        return $this;
    }

    public function buildAccount(Account $account): self
    {
        $this->data['$account.city_state_postal'] = ['value' => $account->present()->cityStateZip($account->settings->city, $account->settings->state, $account->settings->postal_code, false) ?: '&nbsp;', 'label' => trans('texts.city_with_zip')];
        $this->data['$account.postal_city_state'] = ['value' => $account->present()->cityStateZip($account->settings->city, $account->settings->state, $account->settings->postal_code, true) ?: '&nbsp;', 'label' => trans('texts.zip_with_city')];
        $this->data['$account.name'] = ['value' => $account->present()->name() ?: '&nbsp;', 'label' => trans('texts.company_name')];
        $this->data['$account.address1'] = ['value' => $account->settings->address1 ?: '&nbsp;', 'label' => trans('texts.address1')];
        $this->data['$account.address2'] = ['value' => $account->settings->address2 ?: '&nbsp;', 'label' => trans('texts.address2')];
        $this->data['$account.city'] = ['value' => $account->settings->city ?: '&nbsp;', 'label' => trans('texts.city')];
        $this->data['$account.state'] = ['value' => $account->settings->state ?: '&nbsp;', 'label' => trans('texts.town')];
        $this->data['$account.postal_code'] = ['value' => $account->settings->postal_code ?: '&nbsp;', 'label' => trans('texts.zip')];
        $this->data['$account.country'] = ['value' => Country::find($account->settings->country_id)->name ?: '&nbsp;', 'label' => trans('texts.country')];
        $this->data['$account.phone'] = ['value' => $account->settings->phone ?: '&nbsp;', 'label' => trans('texts.phone_number')];
        $this->data['$account.email'] = ['value' => $account->settings->email ?: '&nbsp;', 'label' => trans('texts.email_address')];
        $this->data['$account.vat_number'] = ['value' => $account->settings->vat_number ?: '&nbsp;', 'label' => trans('texts.vat_number')];
        $this->data['$account.id_number'] = ['value' => $account->settings->id_number ?: '&nbsp;', 'label' => trans('texts.customer_id_number')];
        $this->data['$account.website'] = ['value' => $account->settings->website ?: '&nbsp;', 'label' => trans('texts.website')];
        $this->data['$account.address'] = ['value' => $account->present()->address($account->settings) ?: '&nbsp;', 'label' => trans('texts.address')];

        $logo = $account->present()->logo($account->settings);

        $this->data['$account_logo'] = ['value' => "<img src='{$logo}' style='width: 100px; height: 100px;' alt='logo'>" ?: '&nbsp;', 'label' => trans('texts.logo')];
        $this->data['$account1'] = ['value' => $account->settings->custom_value1 ?: '&nbsp;', 'label' => $this->makeCustomField('Account', 'custom_value1')];
        $this->data['$account2'] = ['value' => $account->settings->custom_value2 ?: '&nbsp;', 'label' => $this->makeCustomField('Account', 'custom_value2')];
        $this->data['$account3'] = ['value' => $account->settings->custom_value3 ?: '&nbsp;', 'label' => $this->makeCustomField('Account', 'custom_value3')];
        $this->data['$account4'] = ['value' => $account->settings->custom_value4 ?: '&nbsp;', 'label' => $this->makeCustomField('Account', 'custom_value4')];
        return $this;
    }

    public function setTerms($terms): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$terms'] = ['value' => $terms ?: '&nbsp;', 'label' => trans('texts.'.$class . '_terms')];
        return $this;
    }

    public function setTotal(Customer $customer, $total): self
    {
        $this->data['$entity_label'] = ['value' => '', 'label' => (new \ReflectionClass($this->entity))->getShortName()];
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$'.$class.'.total'] = ['value' => $this->entity->getFormattedTotal() ?: '&nbsp;', 'label' => trans('texts.'.$class . '_amount')];
        return $this;
    }

    public function setBalance(Customer $customer, $balance): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$' . $class . '.balance_due'] = ['value' => $this->entity->getFormattedBalance() ?: '&nbsp;', 'label' => trans('texts.balance_due')];
        $this->data['$balance_due'] = ['value' => $this->entity->getFormattedBalance() ?: '&nbsp;', 'label' => trans('texts.balance_due')];

        return $this;

    }

    public function setSubTotal(Customer $customer, $sub_total): self
    {
        $this->data['$subtotal'] = ['value' => $this->entity->getFormattedSubtotal() ?: '&nbsp;', 'label' => trans('texts.sub_total')];
        //$this->data['$invoice.subtotal'] = &$this->data['$subtotal'];
        return $this;
    }

    public function setDate($date): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$date'] = ['value' => $date ?: '&nbsp;', 'label' => trans('texts.date')];
        $this->data['$'.$class . '.date'] = ['value' => $date ?: '&nbsp;', 'label' => trans('texts.date')];
        return $this;

    }

    public function setInvoiceCustomValues(): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());

        $this->data['$' . $class .'.custom1'] = ['value' => $this->entity->custom_value1 ?: '&nbsp;', 'label' => $this->makeCustomField('Invoice', 'custom_value1')];
        $this->data['$' . $class .'.custom2'] = ['value' => $this->entity->custom_value2 ?: '&nbsp;', 'label' => $this->makeCustomField('Invoice', 'custom_value2')];
        $this->data['$' . $class .'.custom3'] = ['value' => $this->entity->custom_value3 ?: '&nbsp;', 'label' => $this->makeCustomField('Invoice', 'custom_value3')];
        $this->data['$' . $class .'.custom4'] = ['value' => $this->entity->custom_value4 ?: '&nbsp;', 'label' => $this->makeCustomField('Invoice', 'custom_value4')];
        return $this;
    }

    public function setPoNumber($po_number): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$' . $class . '.po_number'] = ['value' => $po_number ?: '&nbsp;', 'label' => trans('texts.po_number')];
        return $this;
    }

    public function setDiscount(Customer $customer, $discount): self
    {
        $this->data['$discount'] = ['value' => Number::formatMoney($discount, $customer) ?: '&nbsp;', 'label' => trans('texts.discount')];
        return $this;
    }

    public function setNumber($number): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$number'] = ['value' => $number ?: '&nbsp;', 'label' => trans('texts.'.$class . '_number')];
        $this->data['$' . $class . '.number'] = ['value' => $number ?: '&nbsp;', 'label' => trans('texts.' . $class . '_number')];
        $this->data['$' . $class . '.' . $class . '_no'] = $number;
        return $this;

    }

    public function setNotes($notes): self
    {
        $this->data['$entity.public_notes'] = ['value' => $notes ?: '&nbsp;', 'label' => trans('texts.public_notes')];
        return $this;
    }

    public function setTaxes(Customer $customer): self
    {
         $this->data['$tax'] = ['value' => $this->makeLineTaxes($customer, 'line_taxes', false, true), 'label' => trans('texts.taxes')];
         $this->data['$line_tax'] = ['value' => $this->makeLineTaxes($customer, 'line_taxes', false, true), 'label' => trans('texts.taxes')];

        return $this;
    }

    private function makeLineTaxes(Customer $customer, $class = 'line_taxes', $line = false, $span = false): string
    {

        $data = '';

        $tax_map = $this->buildTaxMap();

        if (empty($tax_map)) {
            return $data;
        }


        foreach ($tax_map as $tax) {

            if ($line === true) {
                $data .= '<span>' . $tax['name'] . '</span>';
                continue;
            }

            if ($span === true) {
                $data .= '<span>' . Number::formatMoney($tax['total'], $customer) . '</span>';
                continue;
            }

            $data .= '<tr class="' . $class . '">';
            $data .= '<td>' . $tax['name'] . '</td>';
            $data .= '<td>' . Number::formatMoney($tax['total'], $customer) . '</td></tr>';
        }

        return $data;
    }

    public function setDueDate($due_date): self
    {
        $class = strtolower((new \ReflectionClass($this->entity))->getShortName());
        $this->data['$' . $class . '.due_date'] = ['value' => $due_date ?: '&nbsp;', 'label' => trans('texts.due_date')];
        $this->data['$due_date'] = &$this->data['$' . $class . '.due_date'];
        $this->data['$quote.valid_until'] = ['value' => $due_date, 'label' => trans('texts.valid_until')];
        return $this;
    }

    public function buildTable($columns, $user_columns = null, string $table_prefix = null): array
    {
        if(empty($this->line_items)) {
            return [];
        }

        $labels = $this->getLabels();
        $values = $this->getValues();

        $table[$table_prefix] = new \stdClass();

        $table[$table_prefix]->header = '<tr>';
        $table[$table_prefix]->body = '';
        $table_row = '<tr>';

        foreach ($columns as $key => $column) {
            $table[$table_prefix]->header .= '<td class="table_header_td_class">' . $column . '_label</td>';
            $table_row .= '<td class="table_header_td_class">' . $column . '</td>';
        }

        $table_row .= '</tr>';


        foreach ($this->line_items as $key => $item) {
            $tmp = strtr($table_row, $item);
            $tmp = strtr($tmp, $values);
            $table[$table_prefix]->body .= $tmp;
        }

        $table[$table_prefix]->header .= '</tr>';

        $table[$table_prefix]->header = strtr($table[$table_prefix]->header, $labels);

        return $table;
    }

    public function parseLabels($labels, $html): string
    {
        return str_replace(array_keys($labels), array_values($labels), $html);
    }

     public function parseValues($values, $html): string
    {
        return str_replace(array_keys($values), array_values($values), $html);
    }

    private function transformLineItems(Customer $customer, $entity, $table_type = '$product'): self
    {
        if(!isset($entity->line_items) || empty($entity->line_items)) {
            return $this;
        }

        $this->line_items = [];

        foreach ($entity->line_items as $key => $item) {

             $this->line_items[$key][$table_type . '.product_key'] = $item->product_id;

            if(is_numeric($item->product_id)) {
               $product = Product::find($item->product_id);
                $this->line_items[$key][$table_type . '.product_key'] = $product->name;

            }
           
            $this->line_items[$key][$table_type . '.quantity'] = $item->quantity;
            $this->line_items[$key][$table_type . '.notes'] = $item->notes ?: '';
            $this->line_items[$key][$table_type . '.cost'] = Number::formatMoney($item->unit_price, $customer);
            $this->line_items[$key][$table_type . '.line_total'] = Number::formatMoney($item->sub_total, $customer);

            $this->line_items[$key][$table_type . '.discount'] = '';

            if (isset($item->unit_discount) && $item->unit_discount > 0) {
                if ($item->is_amount_discount) {
                    $this->line_items[$key][$table_type . '.discount'] = Number::formatMoney($item->unit_discount, $customer);
                } else {
                    $this->line_items[$key][$table_type . '.discount'] = $item->unit_discount . '%';
                }
            }

            if (isset($item->unit_tax) && $item->unit_tax > 0) {
                $this->line_items[$key][$table_type . '.tax_rate1'] = round($item->unit_tax, 2) . "%";
                $this->line_items[$key][$table_type . '.tax1'] = &$this->line_items[$key][$table_type . '.tax_rate1'];
            }
        }

        return $this;
    }

    private function buildTaxMap()
    {
        if(!isset($this->entity->line_items)) {
            return [];
        }

        $invoice = $this->entity->service()->calculateInvoiceTotals();
        $line_items = $invoice->line_items;
        $tax_collection = collect([]);

        foreach ($line_items as $key => $line_item) {
            $group_tax = ['key' => $key, 'total' => $line_item->tax_total, 'tax_name' => $line_item->tax_rate_name . ' ' . $line_item->unit_tax . '%'];
            $tax_collection->push(collect($group_tax));
        }

        $keys = $tax_collection->pluck('key')->unique();

        foreach ($keys as $key) {
            $tax_name = $tax_collection->filter(function ($value, $k) use ($key) {
                return $value['key'] == $key;
            })->pluck('tax_name')->first();

            $total_line_tax = $tax_collection->filter(function ($value, $k) use ($key) {
                return $value['key'] == $key;
            })->sum('total');

            $tax_map[] = ['name' => $tax_name, 'total' => $total_line_tax];

            return $tax_map;
        }
    }

    public
    function getValues()
    {
        return $this->values;
    }

    public
    function getLabels()
    {
        return $this->labels;
    }

    public function setDesignHtml()
    {

    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
