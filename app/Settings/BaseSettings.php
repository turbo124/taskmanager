<?php

namespace App\Settings;

class BaseSettings
{

    protected array $validationFailures = [];

    protected array $account_settings = [
        'display_invoice_terms'           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'display_invoice_signature'       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'display_quote_signature'         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'portal_terms'                    => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'portal_privacy_policy'           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'charge_gateway_to_customer'      => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'create_task_on_order'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'slack_enabled'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'portal_dashboard_message'        => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'late_fee_endless_percent'        => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'allow_backorders'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'allow_partial_orders'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'inventory_enabled'               => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'late_fee_endless_amount'         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'should_email_invoice'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_email_quote'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_email_order'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'reminder_send_time'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 32400,
            'type'             => 'int'
        ],
        'email_sending_method'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'default',
            'type'             => 'string'
        ],
        'currency_id'                     => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 2,
            'type'             => 'string'
        ],
        'counter_number_applied'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'when_saved',
            'type'             => 'string'
        ],
        'quote_number_applied'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'when_saved',
            'type'             => 'string'
        ],
        'enable_reminder1'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'enable_reminder2'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'enable_reminder3'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'num_days_reminder1'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'int'
        ],
        'num_days_reminder2'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'int'
        ],
        'num_days_reminder3'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'int'
        ],
        'schedule_reminder1'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ], // (enum: after_invoice_date, before_due_date, after_due_date)
        'schedule_reminder2'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ], // (enum: after_invoice_date, before_due_date, after_due_date)
        'schedule_reminder3'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ], // (enum: after_invoice_date, before_due_date, after_due_date)
        'late_fee_amount1'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'late_fee_amount2'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'late_fee_amount3'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'endless_reminder_frequency_id'   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'integer'
        ],
        'document_email_attachment'       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'enable_email_markup'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'email_template_statement'        => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_statement'         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'show_signature_on_pdf'           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'quote_footer'                    => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'order_footer'                    => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'page_size'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'A4',
            'type'             => 'string'
        ],
        'font_size'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 12,
            'type'             => 'int'
        ],
        'primary_font'                    => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'Roboto',
            'type'             => 'string'
        ],
        'secondary_font'                  => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'Roboto',
            'type'             => 'string'
        ],
        'embed_documents'                 => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'all_pages_header'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'all_pages_footer'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'task_number_pattern'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'task_number_counter'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'int'
        ],
        'expense_number_pattern'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'expense_number_counter'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'int'
        ],
        'company_number_pattern'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'company_number_counter'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'int'
        ],
        'lead_number_pattern'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'lead_number_counter'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'int'
        ],
        'case_number_pattern'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'case_number_counter'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'int'
        ],
        'payment_number_pattern'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'payment_number_counter'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'int'
        ],
        'reply_to_email'                  => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'bcc_email'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'pdf_email_attachment'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'ubl_email_attachment'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'email_style'                     => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'light',
            'type'             => 'string'
        ],
        'email_style_custom'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'light',
            'type'             => 'string'
        ],
        'address1'                        => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'address2'                        => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'city'                            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'company_logo'                    => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'country_id'                      => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 225,
            'type'             => 'string'
        ],
        'customer_number_pattern'         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'customer_number_counter'         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'integer'
        ],
        'credit_number_pattern'           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'credit_number_counter'           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'integer'
        ],
        'order_number_pattern'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'order_number_counter'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'integer'
        ],
        'recurringinvoice_number_pattern' => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'recurringinvoice_number_counter' => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'integer'
        ],
        'recurringquote_number_pattern'   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'recurringquote_number_counter'   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'integer'
        ],
        'custom_value1'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'custom_value2'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'custom_value3'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'custom_value4'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'default_task_rate'               => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'email_signature'                 => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'inclusive_taxes'                 => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'invoice_number_pattern'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'invoice_number_counter'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'integer'
        ],
        'invoice_design_id'               => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'string'
        ],
        'invoice_footer'                  => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'invoice_labels'                  => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'invoice_terms'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'credit_footer'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'credit_terms'                    => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'order_terms'                     => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'name'                            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'Untitled1',
            'type'             => 'string'
        ],
        'payment_terms'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 30,
            'type'             => 'integer'
        ],
        'payment_type_id'                 => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'string'
        ],
        'phone'                           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'postal_code'                     => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'quote_design_id'                 => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'string'
        ],
        'credit_design_id'                => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'string'
        ],
        'order_design_id'                 => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'string'
        ],
        'type_id'                         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'int'
        ],
        'quote_number_pattern'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'quote_number_counter'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 1,
            'type'             => 'integer'
        ],
        'quote_terms'                     => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'recurring_number_prefix'         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'R',
            'type'             => 'string'
        ],
        'state'                           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email'                           => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'vat_number'                      => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'number'                          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'tax_name1'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'tax_name2'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'tax_name3'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'tax_rate1'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'tax_rate2'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'tax_rate3'                       => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 0,
            'type'             => 'float'
        ],
        'timezone_id'                     => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'date_format_id'                  => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'language_id'                     => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'show_currency_code'              => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'send_reminders'                  => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'should_archive_invoice'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'should_archive_quote'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_convert_quote'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_convert_order'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_archive_order'            => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_archive_lead'             => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_update_inventory'         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => true,
            'type'             => 'bool'
        ],
        'should_update_products'          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'shared_invoice_quote_counter'    => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => false,
            'type'             => 'bool'
        ],
        'counter_padding'                 => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 4,
            'type'             => 'integer'
        ],
        'design'                          => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => 'views/pdf/design1.blade.php',
            'type'             => 'string'
        ],
        'website'                         => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'pdf_variables'                   => [
            'required'         => false,
            'translated_value' => '',
            'default_value'    => [],
            'type'             => 'object'
        ],
        'email_subject_custom1'           => [
            'required'         => false,
            'translated_value' => 'texts.custom1_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_custom2'           => [
            'required'         => false,
            'translated_value' => 'texts.custom2_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_custom3'           => [
            'required'         => false,
            'translated_value' => 'texts.custom3_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_custom1'          => [
            'required'         => false,
            'translated_value' => 'texts.custom1_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_custom2'          => [
            'required'         => false,
            'translated_value' => 'texts.custom2_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_invoice'           => [
            'required'         => false,
            'translated_value' => 'texts.invoice_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_quote'             => [
            'required'         => false,
            'translated_value' => 'texts.quote_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_credit'            => [
            'required'         => false,
            'translated_value' => 'texts.credit_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_payment'           => [
            'required'         => false,
            'translated_value' => 'texts.payment_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_lead'              => [
            'required'         => false,
            'translated_value' => 'texts.lead_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_order_received'    => [
            'required'         => false,
            'translated_value' => 'texts.order_received_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_order_sent'        => [
            'required'         => false,
            'translated_value' => 'texts.order_sent_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_payment_partial'   => [
            'required'         => false,
            'translated_value' => 'texts.partial_payment_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_invoice'          => [
            'required'         => false,
            'translated_value' => 'texts.invoice_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_quote'            => [
            'required'         => false,
            'translated_value' => 'texts.quote_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_credit'           => [
            'required'         => false,
            'translated_value' => 'texts.credit_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_payment'          => [
            'required'         => false,
            'translated_value' => 'texts.payment_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_lead'             => [
            'required'         => false,
            'translated_value' => 'texts.lead_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_order_received'   => [
            'required'         => false,
            'translated_value' => 'texts.order_received_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_order_sent'       => [
            'required'         => false,
            'translated_value' => 'texts.order_sent_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_payment_partial'  => [
            'required'         => false,
            'translated_value' => 'texts.partial_payment_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_reminder1'         => [
            'required'         => false,
            'translated_value' => 'texts.reminder1_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_reminder2'         => [
            'required'         => false,
            'translated_value' => 'texts.reminder2_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_reminder3'         => [
            'required'         => false,
            'translated_value' => 'texts.reminder3_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_subject_reminder_endless'  => [
            'required'         => false,
            'translated_value' => 'texts.endless_subject',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_reminder1'        => [
            'required'         => false,
            'translated_value' => 'texts.reminder1_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_reminder2'        => [
            'required'         => false,
            'translated_value' => 'texts.reminder2_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_reminder3'        => [
            'required'         => false,
            'translated_value' => 'texts.reminder3_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_reminder_endless' => [
            'required'         => false,
            'translated_value' => 'texts.endless_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
        'email_template_custom3'          => [
            'required'         => false,
            'translated_value' => 'texts.custom3_body',
            'default_value'    => '',
            'type'             => 'string'
        ],
    ];

    protected function validate($saved_settings, $actual_settings)
    {
        if (empty($saved_settings)) {
            return false;
        }

        foreach ($actual_settings as $key => $actual_setting) {
            if (!isset($saved_settings->$key)) {
                $saved_settings->{$key} = !empty($actual_setting['translated_value']) ? trans(
                    $actual_setting['translated_value']
                ) : $actual_setting['default_value'];
            }

            // if required and empty
            if (empty($saved_settings->{$key}) && $saved_settings->{$key} !== false && $actual_setting['required'] === true) {
                $this->validationFailures[] = "{$key} is a required field";
            }

            if ($actual_setting['type'] === 'bool' && isset($saved_settings->{$key}) && is_string(
                    $saved_settings->{$key}
                )) {
                if (in_array($saved_settings->{$key}, ['true', 'false'])) {
                    $saved_settings->{$key} = $saved_settings->{$key} === 'true';
                }
            }

            // if value empty and has default value then use default
            if (!is_bool(
                    $saved_settings->{$key}
                ) && $saved_settings->{$key} === '' && !empty($actual_setting['default_value'])) {
                $saved_settings->{$key} = !empty($actual_setting['translated_value']) ? trans(
                    $actual_setting['translated_value']
                ) : $actual_setting['default_value'];
            }

            // cast type
            settype($saved_settings->{$key}, $actual_setting['type']);
        }

        if (count($this->validationFailures) > 0) {
            return false;
        }

        return $saved_settings;
    }
}
