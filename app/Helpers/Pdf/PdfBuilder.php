<?php

namespace App\Helpers\Pdf;

use App\Models\Account;
use App\Models\ClientContact;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Traits\Money;

/**
 * Class PdfData
 * @package App
 */
class PdfBuilder
{
    use Money;

    protected $labels;
    protected $values;
    protected $data;
    protected $entity;
    protected $line_items;

    /**
     * PdfData constructor.
     * @param $entity
     * @param ClientContact $contact
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->class = strtolower((new \ReflectionClass($this->entity))->getShortName());
    }

    protected function setDefaults(Customer $customer): self
    {
        $this->data['$entity_label'] = ['value' => '', 'label' => trans('texts.' . $this->class)];
        $this->data['$invoice.partial_due'] = [
            'value' => $this->formatCurrency(
                $this->entity->partial,
                $customer
            ) ?: '&nbsp;',
            'label' => trans('texts.partial_due_label')
        ];
        $this->data['$from'] = ['value' => '', 'label' => trans('texts.from')];
        $this->data['$to'] = ['value' => '', 'label' => trans('texts.to')];
        return $this;
    }

    private function findCustomType($entity, $field)
    {
        $custom_fields = $entity->account->custom_fields;

        if (!isset($custom_fields->{$entity})) {
            return '';
        }

        $new_array = array_filter(
            $custom_fields->{$entity},
            function ($obj) use ($field) {
                if ($field === $obj->name) {
                    return $obj;
                }
            }
        );

        if (empty($new_array) || empty($new_array[0]->type)) {
            return '';
        }

        return $new_array[0]->type;
    }

    /**
     * @param $entity
     * @param $field
     * @return string
     */
    protected function makeCustomField($entity, $field): string
    {
        $custom_fields = $this->entity->account->custom_fields;

        if (!isset($custom_fields->{$entity})) {
            return '';
        }

        $new_array = array_filter(
            $custom_fields->{$entity},
            function ($obj) use ($field) {
                if ($field === $obj->name) {
                    return $obj;
                }
            }
        );

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
        $this->data['$contact.custom1'] = [
            'value' => isset($contact) ? $contact->custom_value1 : '&nbsp;',
            'label' => $this->makeCustomField('Contact', 'custom_value1')
        ];
        $this->data['$contact.custom2'] = [
            'value' => isset($contact) ? $contact->custom_value2 : '&nbsp;',
            'label' => $this->makeCustomField('Contact', 'custom_value2')
        ];
        $this->data['$contact.custom3'] = [
            'value' => isset($contact) ? $contact->custom_value3 : '&nbsp;',
            'label' => $this->makeCustomField('Contact', 'custom_value3')
        ];
        $this->data['$contact.custom4'] = [
            'value' => isset($contact) ? $contact->custom_value4 : '&nbsp;',
            'label' => $this->makeCustomField('Contact', 'custom_value4')
        ];
        return $this;
    }

    public function buildCustomer(Customer $customer): self
    {
        $this->data['$customer.number'] = [
            'value' => $customer->number ?: '&nbsp;',
            'label' => trans('texts.customer_id_number')
        ];
        $this->data['$customer.vat_number'] = [
            'value' => $customer->vat_number ?: '&nbsp;',
            'label' => trans('texts.vat_number')
        ];
        $this->data['$customer.website'] = [
            'value' => $customer->present()->website() ?: '&nbsp;',
            'label' => trans('texts.website')
        ];
        $this->data['$customer.phone'] = [
            'value' => $customer->present()->phone() ?: '&nbsp;',
            'label' => trans('texts.phone_number')
        ];
        $this->data['$customer.email'] = [
            'value' => isset(
                $customer->primary_contact()->first()->email
            ) ? $customer->primary_contact()->first()->email : '',
            'label' => trans('texts.email_address')
        ];
        $this->data['$customer.name'] = [
            'value' => $this->entity->present()->clientName() ?: '&nbsp;',
            'label' => trans('texts.customer_name')
        ];
        $this->data['$customer1'] = [
            'value' => $customer->custom_value1 ?: '&nbsp;',
            'label' => $this->makeCustomField('Customer', 'custom_value1')
        ];
        $this->data['$customer2'] = [
            'value' => $customer->custom_value2 ?: '&nbsp;',
            'label' => $this->makeCustomField('Customer', 'custom_value2')
        ];
        $this->data['$customer3'] = [
            'value' => $customer->custom_value3 ?: '&nbsp;',
            'label' => $this->makeCustomField('Customer', 'custom_value3')
        ];
        $this->data['$customer4'] = [
            'value' => $customer->custom_value4 ?: '&nbsp;',
            'label' => $this->makeCustomField('Customer', 'custom_value4')
        ];

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
        $this->data['$product.tax'] = ['value' => '', 'label' => trans('texts.tax')];
        $this->data['$product.line_total'] = ['value' => '', 'label' => trans('texts.sub_total')];
        return $this;
    }

    public function buildAddress($entity, $address)
    {
        $this->data['$customer.address1'] = [
            'value' => $address->address_1 ?: '&nbsp;',
            'label' => trans('texts.address')
        ];
        $this->data['$customer.address2'] = [
            'value' => $address->address_2 ?: '&nbsp;',
            'label' => trans('texts.address')
        ];
        $this->data['$customer.city_state_postal'] = [
            'value' => isset($address->city) ? $entity->present()->cityStateZip(
                $address->city,
                $address->state_code,
                $address->zip,
                false
            ) : '&nbsp;',
            'label' => trans('texts.city_with_zip')
        ];
        $this->data['$postal_city_state'] = [
            'value' => $entity->present()->cityStateZip(
                $address->city,
                $address->state,
                $entity->postal_code,
                true
            ) ?: '&nbsp;',
            'label' => trans('texts.zip_with_city')
        ];
        $this->data['$customer.country'] = [
            'value' => isset($address->country->name) ? $address->country->name : 'No Country Set',
            'label' => trans('texts.country')
        ];

        return $this;
    }


    public function buildCustomerAddress(Customer $customer): self
    {
        $this->data['$customer.address1'] = [
            'value' => $customer->present()->address() ?: '&nbsp;',
            'label' => trans('texts.address')
        ];

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

        if (!empty($billing)) {
            $this->buildAddress($customer, $billing);
        }

        return $this;
    }

    public function buildAccount(Account $account): self
    {
        $this->data['$account.city_state_postal'] = [
            'value' => $account->present()->cityStateZip(
                $account->settings->city,
                $account->settings->state,
                $account->settings->postal_code,
                false
            ) ?: '&nbsp;',
            'label' => trans('texts.city_with_zip')
        ];
        $this->data['$account.postal_city_state'] = [
            'value' => $account->present()->cityStateZip(
                $account->settings->city,
                $account->settings->state,
                $account->settings->postal_code,
                true
            ) ?: '&nbsp;',
            'label' => trans('texts.zip_with_city')
        ];
        $this->data['$account.name'] = [
            'value' => $account->present()->name() ?: '&nbsp;',
            'label' => trans('texts.company_name')
        ];
        $this->data['$account.address1'] = [
            'value' => $account->settings->address1 ?: '&nbsp;',
            'label' => trans('texts.address1')
        ];
        $this->data['$account.address2'] = [
            'value' => $account->settings->address2 ?: '&nbsp;',
            'label' => trans('texts.address2')
        ];
        $this->data['$account.city'] = [
            'value' => $account->settings->city ?: '&nbsp;',
            'label' => trans('texts.city')
        ];
        $this->data['$account.state'] = [
            'value' => $account->settings->state ?: '&nbsp;',
            'label' => trans('texts.town')
        ];
        $this->data['$account.postal_code'] = [
            'value' => $account->settings->postal_code ?: '&nbsp;',
            'label' => trans('texts.zip')
        ];
        $this->data['$account.country'] = [
            'value' => Country::find($account->settings->country_id)->name ?: '&nbsp;',
            'label' => trans('texts.country')
        ];
        $this->data['$account.phone'] = [
            'value' => $account->settings->phone ?: '&nbsp;',
            'label' => trans('texts.phone_number')
        ];
        $this->data['$account.email'] = [
            'value' => $account->settings->email ?: '&nbsp;',
            'label' => trans('texts.email_address')
        ];
        $this->data['$account.vat_number'] = [
            'value' => $account->settings->vat_number ?: '&nbsp;',
            'label' => trans('texts.vat_number')
        ];
        $this->data['$account.number'] = [
            'value' => $account->settings->number ?: '&nbsp;',
            'label' => trans('texts.customer_id_number')
        ];
        $this->data['$account.website'] = [
            'value' => $account->settings->website ?: '&nbsp;',
            'label' => trans('texts.website')
        ];
        $this->data['$account.address'] = [
            'value' => $account->present()->address($account->settings) ?: '&nbsp;',
            'label' => trans('texts.address')
        ];

        $logo = $account->present()->logo($account->settings);

        $this->data['$account_logo'] = [
            'value' => "<img src='{$logo}' style='width: 100px; height: 100px;' alt='logo'>" ?: '&nbsp;',
            'label' => trans('texts.logo')
        ];
        $this->data['$account1'] = [
            'value' => $account->settings->custom_value1 ?: '&nbsp;',
            'label' => $this->makeCustomField('Account', 'custom_value1')
        ];
        $this->data['$account2'] = [
            'value' => $account->settings->custom_value2 ?: '&nbsp;',
            'label' => $this->makeCustomField('Account', 'custom_value2')
        ];
        $this->data['$account3'] = [
            'value' => $account->settings->custom_value3 ?: '&nbsp;',
            'label' => $this->makeCustomField('Account', 'custom_value3')
        ];
        $this->data['$account4'] = [
            'value' => $account->settings->custom_value4 ?: '&nbsp;',
            'label' => $this->makeCustomField('Account', 'custom_value4')
        ];
        return $this;
    }

    public function setTerms($terms): self
    {
        $this->data['$terms'] = ['value' => $terms ?: '&nbsp;', 'label' => trans('texts.' . $this->class . '_terms')];
        return $this;
    }

    public function setTotal(Customer $customer, $total): self
    {
        $this->data['$entity_label'] = [
            'value' => '',
            'label' => $this->class
        ];

        $this->data['$' . $this->class . '.total'] = [
            'value' => $this->entity->getFormattedTotal() ?: '&nbsp;',
            'label' => trans('texts.' . $this->class . '_amount')
        ];
        return $this;
    }

    public function setBalance(Customer $customer, $balance): self
    {
        if (!isset($this->entity->balance)) {
            return $this;
        }

        $this->data['$' . $this->class . '.balance_due'] = [
            'value' => $this->entity->getFormattedBalance() ?: '&nbsp;',
            'label' => trans('texts.balance_due')
        ];
        $this->data['$balance_due'] = [
            'value' => $this->entity->getFormattedBalance() ?: '&nbsp;',
            'label' => trans('texts.balance_due')
        ];

        return $this;
    }

    public function setSubTotal(Customer $customer, $sub_total): self
    {
        if (!isset($this->entity->sub_total)) {
            return $this;
        }

        $this->data['$subtotal'] = [
            'value' => $this->entity->getFormattedSubtotal() ?: '&nbsp;',
            'label' => trans('texts.sub_total')
        ];

        return $this;
    }

    public function setDate($date): self
    {
        $this->data['$date'] = ['value' => $date ?: '&nbsp;', 'label' => trans('texts.date')];
        $this->data['$' . $this->class . '.date'] = ['value' => $date ?: '&nbsp;', 'label' => trans('texts.date')];
        return $this;
    }

    public function setInvoiceCustomValues(): self
    {
        $this->data['$' . $this->class . '.custom1'] = [
            'value' => $this->entity->custom_value1 ?: '&nbsp;',
            'label' => $this->makeCustomField('Invoice', 'custom_value1')
        ];
        $this->data['$' . $this->class . '.custom2'] = [
            'value' => $this->entity->custom_value2 ?: '&nbsp;',
            'label' => $this->makeCustomField('Invoice', 'custom_value2')
        ];
        $this->data['$' . $this->class . '.custom3'] = [
            'value' => $this->entity->custom_value3 ?: '&nbsp;',
            'label' => $this->makeCustomField('Invoice', 'custom_value3')
        ];
        $this->data['$' . $this->class . '.custom4'] = [
            'value' => $this->entity->custom_value4 ?: '&nbsp;',
            'label' => $this->makeCustomField('Invoice', 'custom_value4')
        ];
        return $this;
    }

    public function setPoNumber($po_number): self
    {
        $this->data['$' . $this->class . '.po_number'] = [
            'value' => $po_number ?: '&nbsp;',
            'label' => trans('texts.po_number')
        ];
        return $this;
    }

    public function setDiscount(Customer $customer, $discount): self
    {
        $this->data['$discount'] = [
            'value' => $this->formatCurrency($discount, $customer) ?: '&nbsp;',
            'label' => trans('texts.discount')
        ];
        return $this;
    }

    public function setShippingCost(Customer $customer, $shipping): self
    {
        $shipping = empty($shipping) ? 0 : $shipping;

        $this->data['$shipping_cost'] = [
            'value' => $this->formatCurrency($shipping, $customer) ?: '&nbsp;',
            'label' => trans('texts.shipping')
        ];
        return $this;
    }

    public function setVoucherCode($voucher_code): self
    {
        if (empty($voucher_code)) {
            return $this;
        }

        $this->data['$voucher'] = [
            'value' => $voucher_code ?: '&nbsp;',
            'label' => trans('texts.voucher')
        ];

        return $this;
    }

    public function setNumber($number): self
    {
        $this->data['$number'] = [
            'value' => $number ?: '&nbsp;',
            'label' => trans('texts.' . $this->class . '_number')
        ];
        $this->data['$' . $this->class . '.number'] = [
            'value' => $number ?: '&nbsp;',
            'label' => trans('texts.' . $this->class . '_number')
        ];
        $this->data['$' . $this->class . '.' . $this->class . '_no'] = $number;
        return $this;
    }

    public function setNotes($notes): self
    {
        $this->data['$entity.public_notes'] = ['value' => $notes ?: '&nbsp;', 'label' => trans('texts.public_notes')];
        return $this;
    }

    public function setTaxes(Customer $customer): self
    {
        $this->data['$tax'] = [
            'value' => $this->makeLineTaxes($customer),
            'label' => trans('texts.taxes')
        ];
        $this->data['$line_tax'] = [
            'value' => $this->makeLineTaxes($customer),
            'label' => trans('texts.taxes')
        ];

        return $this;
    }

    /**
     * @param Customer $customer
     * @return string
     */
    private function makeLineTaxes(Customer $customer): string
    {
        $data = '';

        $tax_map = $this->buildTaxMap();

        if (empty($tax_map)) {
            return $data;
        }

        foreach ($tax_map as $tax) {
            $data .= '<span>' . $this->formatCurrency($tax['total'], $customer) . '</span>';
        }

        return $data;
    }

    /**
     * @param $due_date
     * @return $this
     * @throws \ReflectionException
     */
    public function setDueDate($due_date): self
    {
        $this->data['$' . $this->class . '.due_date'] = [
            'value' => $due_date ?: '&nbsp;',
            'label' => trans('texts.due_date')
        ];
        $this->data['$due_date'] = &$this->data['$' . $this->class . '.due_date'];
        $this->data['$quote.valid_until'] = ['value' => $due_date, 'label' => trans('texts.valid_until')];
        return $this;
    }

    /**
     * @param $columns
     * @param null $user_columns
     * @param string|null $table_prefix
     * @return array
     */
    public function buildTable($columns, $user_columns = null, string $table_prefix = null): array
    {
        if (empty($this->line_items)) {
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

    /**
     * @param $labels
     * @param $html
     * @return string
     */
    public function parseLabels($labels, $html): string
    {
        return str_replace(array_keys($labels), array_values($labels), $html);
    }

    /**
     * @param $values
     * @param $html
     * @return string
     */
    public function parseValues($values, $html): string
    {
        return str_replace(array_keys($values), array_values($values), $html);
    }

    /**
     * @param Customer $customer
     * @param $entity
     * @param string $table_type
     * @return $this
     */
    protected function transformLineItems(Customer $customer, $entity, $table_type = '$product'): self
    {
        if (!isset($entity->line_items) || empty($entity->line_items)) {
            return $this;
        }

        $this->line_items = [];

        foreach ($entity->line_items as $key => $item) {
            $this->line_items[$key][$table_type . '.product_key'] = $item->product_id;

            if (is_numeric($item->product_id) && $item->type_id === 1) {
                $product = Product::find($item->product_id);

                $this->line_items[$key][$table_type . '.product_key'] = $product->name;

                if (!empty($item->attribute_id)) {
                    $product_attribute = ProductAttribute::find($item->attribute_id);

                    $product_attribute_values = array_column($product_attribute->attributesValues->toArray(), 'value');
                    $this->line_items[$key][$table_type . '.product_key'] .= ' (' . implode(
                            ',',
                            $product_attribute_values
                        ) . ')';
                }
            }

            $this->line_items[$key][$table_type . '.quantity'] = $item->quantity;
            $this->line_items[$key][$table_type . '.notes'] = $item->notes ?: '';
            $this->line_items[$key][$table_type . '.cost'] = $this->formatCurrency($item->unit_price, $customer);
            $this->line_items[$key][$table_type . '.line_total'] = $this->formatCurrency($item->sub_total, $customer);

            $this->line_items[$key][$table_type . '.discount'] = '';

            if (isset($item->unit_discount) && $item->unit_discount > 0) {
                if ($item->is_amount_discount) {
                    $this->line_items[$key][$table_type . '.discount'] = $this->formatCurrency(
                        $item->unit_discount,
                        $customer
                    );
                } else {
                    $this->line_items[$key][$table_type . '.discount'] = $item->unit_discount . '%';
                }
            }

            if (isset($item->unit_tax) && $item->unit_tax > 0) {
                $this->line_items[$key][$table_type . '.tax'] = round($item->unit_tax, 2) . "%";
                $this->line_items[$key][$table_type . '.tax1'] = &$this->line_items[$key][$table_type . '.tax_rate1'];
            }
        }

        return $this;
    }

    private function buildTaxMap()
    {
        if (!isset($this->entity->line_items)) {
            return [];
        }

        $invoice = $this->entity->service()->calculateInvoiceTotals();
        $line_items = $invoice->line_items;
        $tax_collection = collect([]);

        foreach ($line_items as $key => $line_item) {
            $group_tax = [
                'key'      => $key,
                'total'    => $line_item->tax_total,
                'tax_name' => $line_item->tax_rate_name . ' ' . $line_item->unit_tax . '%'
            ];
            $tax_collection->push(collect($group_tax));
        }

        $keys = $tax_collection->pluck('key')->unique();

        foreach ($keys as $key) {
            $tax_name = $tax_collection->filter(
                function ($value, $k) use ($key) {
                    return $value['key'] == $key;
                }
            )->pluck('tax_name')->first();

            $total_line_tax = $tax_collection->filter(
                function ($value, $k) use ($key) {
                    return $value['key'] == $key;
                }
            )->sum('total');

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

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
