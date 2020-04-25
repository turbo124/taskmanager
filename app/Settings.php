<?php

namespace App;

class Settings
{

    private $entity;
    private $validationFailures = [];
    private $settings = [
        'line_items' => [
            'type_id'            => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'quantity'           => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'unit_price'         => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'product_id'         => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'unit_discount'      => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'is_amount_discount' => ['required' => false, 'default_value' => 1, 'type' => 'bool'],
            'unit_tax'           => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'tax_total'          => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'sub_total'          => ['required' => false, 'default_value' => 1, 'type' => 'float'],
            'date'               => ['required' => false, 'default_value' => 1, 'type' => 'string'],
            'custom_value1'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_value2'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_value3'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_value4'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
        ],
        'gateway'    => [
            'gateway_type_id'    => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'min_limit'          => ['required' => false, 'default_value' => -1, 'type' => 'float'],
            'max_limit'          => ['required' => false, 'default_value' => -1, 'type' => 'float'],
            'fee_amount'         => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_percent'        => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_tax_name1'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'fee_tax_name2'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'fee_tax_name3'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'fee_tax_rate1'      => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_tax_rate2'      => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_tax_rate3'      => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'fee_cap'            => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'adjust_fee_percent' => ['required' => false, 'default_value' => false, 'type' => 'bool'],
        ],
        'account'    => [
            'slack_enabled'                      => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'update_products'                    => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'fill_products'                      => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'default_quantity'                   => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'convert_products'                   => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'show_product_quantity'              => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'show_cost'                          => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'portal_design_id'                   => ['required' => false, 'default_value' => 1, 'type' => 'string'],
            'late_fee_endless_percent'           => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'late_fee_endless_amount'            => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'auto_email_invoice'                 => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'auto_email_quote'                   => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'reminder_send_time'                 => ['required' => false, 'default_value' => 32400, 'type' => 'int'],
            'email_sending_method'               => ['required' => false, 'default_value' => 'default', 'type' => 'string'],
            'gmail_sending_user_id'              => ['required' => false, 'default_value' => '0', 'type' => 'string'],
            'currency_id'                        => ['required' => true, 'default_value' => 2, 'type' => 'string'],
            'counter_number_applied'             => ['required' => false, 'default_value' => 'when_saved', 'type' => 'string'],
            'quote_number_applied'               => ['required' => false, 'default_value' => 'when_saved', 'type' => 'string'],
            'email_subject_custom1'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_custom2'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_custom3'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_custom1'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_custom2'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_custom3'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'enable_reminder1'                   => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'enable_reminder2'                   => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'enable_reminder3'                   => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'num_days_reminder1'                 => ['required' => false, 'default_value' => 0, 'type' => 'int'],
            'num_days_reminder2'                 => ['required' => false, 'default_value' => 0, 'type' => 'int'],
            'num_days_reminder3'                 => ['required' => false, 'default_value' => 0, 'type' => 'int'],
            'schedule_reminder1'                 => ['required' => false, 'default_value' => '', 'type' => 'string'], // (enum: after_invoice_date, before_due_date, after_due_date)
            'schedule_reminder2'                 => ['required' => false, 'default_value' => '', 'type' => 'string'], // (enum: after_invoice_date, before_due_date, after_due_date)
            'schedule_reminder3'                 => ['required' => false, 'default_value' => '', 'type' => 'string'], // (enum: after_invoice_date, before_due_date, after_due_date)
            'late_fee_amount1'                   => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'late_fee_amount2'                   => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'late_fee_amount3'                   => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'endless_reminder_frequency_id'      => ['required' => false, 'default_value' => 0, 'type' => 'integer'],
            'client_online_payment_notification' => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'client_manual_payment_notification' => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'document_email_attachment'          => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'enable_client_portal_password'      => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'enable_email_markup'                => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'enable_client_portal_dashboard'     => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'enable_client_portal'               => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'email_template_statement'           => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_statement'            => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'signature_on_pdf'                   => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'send_portal_password'               => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'quote_footer'                       => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'order_footer'                       => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'page_size'                          => ['required' => false, 'default_value' => 'A4', 'type' => 'string'],
            'font_size'                          => ['required' => false, 'default_value' => 12, 'type' => 'int'],
            'primary_font'                       => ['required' => false, 'default_value' => 'Roboto', 'type' => 'string'],
            'secondary_font'                     => ['required' => false, 'default_value' => 'Roboto', 'type' => 'string'],
            'hide_paid_to_date'                  => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'embed_documents'                    => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'all_pages_header'                   => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'all_pages_footer'                   => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'task_number_pattern'                => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'task_number_counter'                => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'expense_number_pattern'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'expense_number_counter'             => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'vendor_number_pattern'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'vendor_number_counter'              => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'ticket_number_pattern'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'ticket_number_counter'              => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'payment_number_pattern'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'payment_number_counter'             => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'reply_to_email'                     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'bcc_email'                          => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'pdf_email_attachment'               => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'ubl_email_attachment'               => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'email_style'                        => ['required' => false, 'default_value' => 'light', 'type' => 'string'],
            'email_style_custom'                 => ['required' => false, 'default_value' => 'light', 'type' => 'string'],
            'company_gateway_ids'                => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'address1'                           => ['required' => true, 'default_value' => '', 'type' => 'string'],
            'address2'                           => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'city'                               => ['required' => true, 'default_value' => '', 'type' => 'string'],
            'company_logo'                       => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'country_id'                         => ['required' => false, 'default_value' => 225, 'type' => 'string'],
            'customer_number_pattern'            => ['required' => true, 'default_value' => '', 'type' => 'string'],
            'customer_number_counter'            => ['required' => false, 'default_value' => 1, 'type' => 'integer'],
            'credit_number_pattern'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'credit_number_counter'              => ['required' => false, 'default_value' => 1, 'type' => 'integer'],
            'order_number_pattern'               => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'order_number_counter'               => ['required' => false, 'default_value' => 1, 'type' => 'integer'],
            'recurring_invoice_number_pattern'   => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'recurring_invoice_number_counter'   => ['required' => false, 'default_value' => 1, 'type' => 'integer'],
            'recurring_quote_number_pattern'     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'recurring_quote_number_counter'     => ['required' => false, 'default_value' => 1, 'type' => 'integer'],
            'currency_id'                        => ['required' => true, 'default_value' => 2, 'type' => 'string'],
            'custom_value1'                      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_value2'                      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_value3'                      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_value4'                      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_message_dashboard'           => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_message_unpaid_invoice'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_message_paid_invoice'        => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'custom_message_unapproved_quote'    => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'default_task_rate'                  => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'email_signature'                    => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_invoice'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_quote'                => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_credit'               => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_payment'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_lead'                 => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_order'                => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_payment_partial'      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_invoice'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_quote'               => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_credit'              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_payment'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_lead'                => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_order'               => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_payment_partial'     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_reminder1'            => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_reminder2'            => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_reminder3'            => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_subject_reminder_endless'     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_reminder1'           => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_reminder2'           => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_reminder3'           => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email_template_reminder_endless'    => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'enable_client_portal_password'      => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'inclusive_taxes'                    => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'invoice_number_pattern'             => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'invoice_number_counter'             => ['required' => false, 'default_value' => 1, 'type' => 'integer'],
            'invoice_design_id'                  => ['required' => false, 'default_value' => 1, 'type' => 'string'],
            'invoice_fields'                     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'invoice_taxes'                      => ['required' => false, 'default_value' => 0, 'type' => 'int'],
            'enabled_item_tax_rates'             => ['required' => false, 'default_value' => 0, 'type' => 'int'],
            'invoice_footer'                     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'invoice_labels'                     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'invoice_terms'                      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'credit_footer'                      => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'credit_terms'                       => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'order_terms'                        => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'translations'                       => ['required' => false, 'default_value' => [], 'type' => 'array'],
            'name'                               => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'payment_terms'                      => ['required' => false, 'default_value' => -1, 'type' => 'integer'],
            'payment_type_id'                    => ['required' => false, 'default_value' => 0, 'type' => 'string'],
            'phone'                              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'postal_code'                        => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'quote_design_id'                    => ['required' => false, 'default_value' => 1, 'type' => 'string'],
            'credit_design_id'                   => ['required' => false, 'default_value' => 1, 'type' => 'string'],
            'order_design_id'                    => ['required' => false, 'default_value' => 1, 'type' => 'string'],
            'type_id'                            => ['required' => false, 'default_value' => 1, 'type' => 'int'],
            'quote_number_pattern'               => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'quote_number_counter'               => ['required' => false, 'default_value' => 1, 'type' => 'integer'],
            'quote_terms'                        => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'recurring_number_prefix'            => ['required' => false, 'default_value' => 'R', 'type' => 'string'],
            'reset_counter_frequency_id'         => ['required' => false, 'default_value' => 0, 'type' => 'integer'],
            'reset_counter_date'                 => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'require_invoice_signature'          => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'require_quote_signature'            => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'state'                              => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'email'                              => ['required' => true, 'default_value' => '', 'type' => 'string'],
            'vat_number'                         => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'id_number'                          => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'tax_name1'                          => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'tax_name2'                          => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'tax_name3'                          => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'tax_rate1'                          => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'tax_rate2'                          => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'tax_rate3'                          => ['required' => false, 'default_value' => 0, 'type' => 'float'],
            'show_accept_quote_terms'            => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'show_accept_invoice_terms'          => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'timezone_id'                        => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'date_format_id'                     => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'military_time'                      => ['required' => false, 'default_value' => '', 'type' => 'bool'],
            'language_id'                        => ['required' => true, 'default_value' => '', 'type' => 'string'],
            'show_currency_code'                 => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'send_reminders'                     => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'enable_client_portal_tasks'         => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'lock_sent_invoices'                 => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'auto_archive_invoice'               => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'auto_archive_quote'                 => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'auto_convert_quote'                 => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'auto_convert_order'                 => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'auto_archive_order'                 => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'auto_archive_lead'                  => ['required' => false, 'default_value' => true, 'type' => 'bool'],
            'shared_invoice_quote_counter'       => ['required' => false, 'default_value' => false, 'type' => 'bool'],
            'counter_padding'                    => ['required' => false, 'default_value' => 4, 'type' => 'integer'],
            'design'                             => ['required' => false, 'default_value' => 'views/pdf/design1.blade.php', 'type' => 'string'],
            'website'                            => ['required' => false, 'default_value' => '', 'type' => 'string'],
            'pdf_variables'                      => ['required' => false, 'default_value' => [], 'type' => 'object'],
        ]
    ];

    public function __construct()
    {
        $this->settings['account']['pdf_variables']['default_value'] = $this->getPdfVariables();
    }

    private function validate($saved_settings, $actual_settings, $full_check = false)
    {

        if (empty($saved_settings)) {
            return false;
        }

        foreach ($actual_settings as $key => $actual_setting) {
            if (!array_key_exists($key, $saved_settings)) {

                $saved_settings->{$key} = $actual_setting['default_value'];
            }

            // if required and empty
            if (empty($saved_settings->{$key}) && $saved_settings->{$key} !== false && $actual_setting['required'] === true && $full_check === true) {
                $this->validationFailures[] = "{$key} is a required field";
            }

            if ($actual_setting['type'] === 'bool' && isset($saved_settings->{$key}) && is_string($saved_settings->{$key})) {
                if (in_array($saved_settings->{$key}, ['true', 'false'])) {
                    $saved_settings->{$key} = $saved_settings->{$key} === 'true';
                }
            }

            // if value empty and has default value then use default
            if (!is_bool($saved_settings->{$key}) && $saved_settings->{$key} === '' && !empty($actual_setting['default_value'])) {
                $saved_settings->{$key} = $actual_setting['default_value'];
            }

            // cast type
            settype($saved_settings->{$key}, $actual_setting['type']);
        }

        if (count($this->validationFailures) > 0) {

            echo '<pre>';
            print_r($this->validationFailures);
            die;

            return false;
        }

        return $saved_settings;
    }

    public function getAccountDefaults()
    {
        return (object)array_filter(array_combine(array_keys($this->settings['account']), array_column($this->settings['account'], 'default_value')));
    }

    public function saveAccountSettings($settings)
    {
        try {

            $settings = $this->validate($settings, $this->settings['account']);

            if (!$settings) {
                return false;
            }

            return $settings;
        } catch (\Exception $e) {
            echo $e->getMessage();
            die('here');
        }
    }

    public function saveGatewaySettings($settings)
    {
        try {

            $settings = $this->validate($settings[0], $this->settings['gateway']);

            if (!$settings) {
                return false;
            }

            return [0 => $settings];
        } catch (\Exception $e) {
            echo $e->getMessage();
            die('here');
        }
    }

    public function saveLineItems($settings)
    {
        try {

            foreach ($settings as $key => $setting) {
                $settings[$key] = $this->validate((object)$setting, $this->settings['line_items']);
            }

            if (count($this->validationFailures) > 0) {
                return false;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            die('here');
        }

        return $settings;
    }

    /**
     * @param $client_settings
     * @return object
     */
    public function buildCustomerSettings($client_settings, $account_settings)
    {
        if (!$client_settings) {
            return $account_settings;
        }

        foreach ($account_settings as $key => $value) {
            /* pseudo code
            if the property exists and is a string BUT has no length, treat it as TRUE
            */
            if (((property_exists($client_settings, $key) && is_string($client_settings->{$key}) &&
                    (iconv_strlen($client_settings->{$key}) < 1))) ||
                !isset($client_settings->{$key}) && property_exists($account_settings, $key)) {
                $client_settings->{$key} = $account_settings->{$key};
            }
        }

        return $client_settings;
    }

    public function buildGroupSettings()
    {

    }

    private function getPdfVariables()
    {
        $variables = [
            'client_details'  => [
                '$client.name',
                '$client.id_number',
                '$client.vat_number',
                '$client.address1',
                '$client.address2',
                '$client.city_state_postal',
                '$client.country',
                '$contact.email',
            ],
            'company_details' => [
                '$company.name',
                '$company.id_number',
                '$company.vat_number',
                '$company.website',
                '$company.email',
                '$company.phone',
            ],
            'company_address' => [
                '$company.address1',
                '$company.address2',
                '$company.city_state_postal',
                '$company.country',
            ],
            'invoice' => [
                '$invoice.invoice_number',
                '$invoice.po_number',
                '$invoice.invoice_date',
                '$invoice.due_date',
                '$invoice.balance_due',
                '$invoice.invoice_total',
            ],
            'quote'   => [
                '$quote.quote_number',
                '$quote.po_number',
                '$quote.quote_date',
                '$quote.valid_until',
                '$quote.balance_due',
                '$quote.quote_total',
            ],
            'credit'  => [
                '$credit.credit_number',
                '$credit.po_number',
                '$credit.credit_date',
                '$credit.credit_balance',
                '$credit.credit_amount',
            ],
            'product_columns' => [
                '$product.product_key',
                '$product.notes',
                '$product.cost',
                '$product.quantity',
                '$product.discount',
                '$product.tax',
                '$product.line_total',
            ],
            'task_columns'    => [
                '$task.product_key',
                '$task.notes',
                '$task.cost',
                '$task.quantity',
                '$task.discount',
                '$task.tax',
                '$task.line_total',
            ],
        ];

        return json_decode(json_encode($variables));
    }
}
