<?php
return [
    'support_ticket_subject' => 'A new Support ticket was sent',
    'quote_approved_subject' => 'Your quote was approved',
    'quote_approved_body' => 'Quote $number has been approved',
    'invoice_paid_subject' => 'We have received your payment',
    'invoice_paid_body' => 'We have received your payment for invoice $number. Many Thanks',
    'new_user_created_subject'     => 'Your new user account has been created.',
    'new_user_created_body' => 'You have been setup as a new user in taskmanager please click on the button below to verify your new account',
    'new_user_created_button' => 'Verify Account',
    'new_user'                             => 'New User',
    'view_payment'              => 'View Payment',
    'notification_payment_paid_subject'         => 'Payment was made by :customer',
    'notification_partial_payment_paid_subject' => 'Partial payment was made by :customer',
    'notification_payment_paid'                 => 'A payment of :total was made by client :customer towards :invoice',
    'notification_partial_payment_paid'         => 'A partial payment of :total was made by client :customer towards :invoice',
    'invoice_number_abbreviated'                     => 'Invoice #',
    'view_deal' => 'View Deal',
    'notification_lead_subject'                => 'New Lead was created by :customer',
    'notification_order_subject'               => 'New Order was created by :customer',
    'notification_lead'                        => 'A new lead was made by customer :customer.',
    'notification_order'                       => 'You have received a new order from :customer.',
    'notification_deal_subject'                => 'Deal was made by :customer',
    'notification_deal'                        => 'A new deal was made by client :customer for :total.',
    'order_message'                            => 'We have received your order. To view your order click the link below.',
    'order_subject'                            => 'Order Confirmation',
    'status_draft'                             => 'Draft',
    'status_sent'                              => 'Sent',
    'status_viewed'                            => 'Viewed',
    'status_partial'                           => 'Partial',
    'status_paid'                              => 'Paid',
    'status_unpaid'                            => 'Unpaid',
    'view_invoice'                             => 'View Invoice',
    'view_client'                              => 'View Client',
    'view_quote'                               => 'View Quote',
    'customer' => 'Customer',
    'total' => 'Total',
    'invoice_number_here'     => 'Invoice # :invoice',
    'entity_number_here'     => ':entity # :entity_number',
    'notification_invoice_paid_subject'        => 'Invoice :invoice was paid by :customer',
    'notification_invoice_sent_subject'        => 'Invoice :invoice was sent to :customer',
    'notification_quote_sent_subject'          => 'Quote :invoice was sent to :customer',
    'notification_credit_sent_subject'         => 'Credit :invoice was sent to :customer',
    'notification_invoice_viewed_subject'      => 'Invoice :invoice was viewed by :customer',
    'notification_credit_viewed_subject'       => 'Credit :credit was viewed by :customer',
    'notification_quote_viewed_subject'        => 'Quote :quote was viewed by :customer',
    'notification_invoice_paid'                => 'A payment of :total was made by customer :customer towards Invoice :invoice.',
    'notification_invoice_sent'                => 'The following customer :customer was emailed Invoice :invoice for :total.',
    'notification_quote_sent'                  => 'The following customer :customer was emailed Quote :invoice for :total.',
    'notification_credit_sent'                 => 'The following customer :customer was emailed Credit :invoice for :total.',
    'notification_invoice_viewed'              => 'The following customer :customer viewed Invoice :invoice for :total.',
    'notification_credit_viewed'               => 'The following customer :customer viewed Credit :credit for :total.',
    'notification_quote_viewed'                => 'The following customer :customer viewed Quote :quote for :total.',
        'notification_quote_sent_subject'          => 'Quote :invoice was sent to :client',
    'notification_quote_viewed_subject'        => 'Quote :invoice was viewed by :client',
    'notification_quote_sent'                  => 'The following client :customer was emailed Quote :invoice for :total.',
    'notification_quote_viewed'                => 'The following client :customer viewed Quote :invoice for :total.',
    'from_slack' => 'From',
    'login' => 'Login',
    'new_account_created'         => 'New Account created',
    'new_account_text'    => 'A new account has been created by :user - :email - from IP address: :ip',
    'download_attachments' => 'Download Attachments',
    'download' => 'Download',
    'partial_due_label'               => 'Partial Due',
    'name'              => 'Name',
    'email_address' => 'Email',
    'phone_number'  => 'Phone Number',
    'contact_name'              => 'Contact Name',
    'city_state_postal'         => 'City/State/Postal',
     'customer_id_number'                                => 'ID Number',
     'vat_number'                               => 'VAT Number',
      'website'                                  => 'Website',
      'customer_name'                              => 'Customer Name',
      'date' => 'Date',
      'discount' => 'Discount',
      'product_name' => 'Product Name',
      'notes' => 'Notes',
      'cost' => 'Cost',
      'quantity' => 'Quantity',
      'tax' => 'Tax',
      'sub_total' => 'Sub Total',
      'address' => 'Address',
      'city_with_zip'         => 'City/State/Postal',
      'zip_with_city'         => 'Postal/City/State',
      'country' => 'Country',
      'company_name' => 'Company Name',
      'address1' => 'Address Line 1',
      'address2' => 'Address Line 2',
      'city' => 'City',
      'town' => 'Town',
      'from' => 'From',
        'to' => 'To',
      'zip' => 'Postcode',
      'logo' => 'Logo',
      'order_terms'                            => 'Order Terms',
      'invoice_terms'                            => 'Invoice Terms',
      'credit_terms'                            => 'Credit Terms',
      'quote_terms'                            => 'Quote Terms',
        'public_notes'                      => 'Public Notes',
    'invoice_amount'                    => 'Invoice Amount',
    'quote_amount'                      => 'Quote Amount',
    'credit_amount'                      => 'Credit Amount',
    'balance_due' => 'Balance Due',
    'po_number' => 'Po Number',
     'due_date'                                 => 'Due Date',
    'invoice_number'                           => 'Invoice Number',
    'credit_number'                           => 'Credit Number',
    'quote_number'                           => 'Quote Number',
    'taxes' => 'Taxes',
    'valid_until' => 'Valid Until',
    'manual' => 'Manual',
    'welcome'      => [
        'templateTitle' => 'Welcome',
        'title'         => 'TamTam Setup Wizard',
        'message'       => 'Easy Installation and Setup Wizard.',
        'next'          => 'Check Requirements',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Step 1 | Server Requirements',
        'title'         => 'Server Requirements',
        'next'          => 'Check Permissions',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions'  => [
        'templateTitle' => 'Step 2 | Permissions',
        'title'         => 'Permissions',
        'next'          => 'Configure Environment',
    ],

    'user'        => [
        'title' => 'User',
        'form'  => [
            'buttons'               => [
                'save' => 'Save'
            ],
            'firstname_label'       => 'First Name',
            'firstname_placeholder' => 'Enter your First Name',
            'lastname_label'        => 'Last Name',
            'lastname_placeholder'  => 'Enter your Last Name',
            'email_label'           => 'Email',
            'email_placeholder'     => 'Enter your Email Address',
            'password_label'        => 'Password',
            'password_placeholder'  => 'Enter your Password',
        ]
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu'    => [
            'templateTitle'  => 'Step 3 | Environment Settings',
            'title'          => 'Environment Settings',
            'desc'           => 'Please select how you want to configure the apps <code>.env</code> file.',
            'wizard-button'  => 'Form Wizard Setup',
            'classic-button' => 'Classic Text Editor',
        ],
        'wizard'  => [
            'templateTitle' => 'Step 3 | Environment Settings | Guided Wizard',
            'title'         => 'Guided <code>.env</code> Wizard',
            'tabs'          => [
                'environment' => 'Environment',
                'database'    => 'Database',
                'application' => 'Application',
            ],
            'form'          => [
                'name_required'                      => 'An environment name is required.',
                'app_name_label'                     => 'App Name',
                'app_name_placeholder'               => 'App Name',
                'app_environment_label'              => 'App Environment',
                'app_environment_label_local'        => 'Local',
                'app_environment_label_developement' => 'Development',
                'app_environment_label_qa'           => 'Qa',
                'app_environment_label_production'   => 'Production',
                'app_environment_label_other'        => 'Other',
                'app_environment_placeholder_other'  => 'Enter your environment...',
                'app_debug_label'                    => 'App Debug',
                'app_debug_label_true'               => 'True',
                'app_debug_label_false'              => 'False',
                'app_log_level_label'                => 'App Log Level',
                'app_log_level_label_debug'          => 'debug',
                'app_log_level_label_info'           => 'info',
                'app_log_level_label_notice'         => 'notice',
                'app_log_level_label_warning'        => 'warning',
                'app_log_level_label_error'          => 'error',
                'app_log_level_label_critical'       => 'critical',
                'app_log_level_label_alert'          => 'alert',
                'app_log_level_label_emergency'      => 'emergency',
                'app_url_label'                      => 'App Url',
                'app_url_placeholder'                => 'App Url',
                'db_connection_failed'               => 'Could not connect to the database.',
                'db_connection_label'                => 'Database Connection',
                'db_connection_label_mysql'          => 'mysql',
                'db_connection_label_sqlite'         => 'sqlite',
                'db_connection_label_pgsql'          => 'pgsql',
                'db_connection_label_sqlsrv'         => 'sqlsrv',
                'db_host_label'                      => 'Database Host',
                'db_host_placeholder'                => 'Database Host',
                'db_port_label'                      => 'Database Port',
                'db_port_placeholder'                => 'Database Port',
                'db_name_label'                      => 'Database Name',
                'db_name_placeholder'                => 'Database Name',
                'db_username_label'                  => 'Database User Name',
                'db_username_placeholder'            => 'Database User Name',
                'db_password_label'                  => 'Database Password',
                'db_password_placeholder'            => 'Database Password',

                'app_tabs' => [
                    'more_info'                => 'More Info',
                    'broadcasting_title'       => 'Broadcasting, Caching, Session, &amp; Queue',
                    'broadcasting_label'       => 'Broadcast Driver',
                    'broadcasting_placeholder' => 'Broadcast Driver',
                    'cache_label'              => 'Cache Driver',
                    'cache_placeholder'        => 'Cache Driver',
                    'session_label'            => 'Session Driver',
                    'session_placeholder'      => 'Session Driver',
                    'queue_label'              => 'Queue Driver',
                    'queue_placeholder'        => 'Queue Driver',
                    'redis_label'              => 'Redis Driver',
                    'redis_host'               => 'Redis Host',
                    'redis_password'           => 'Redis Password',
                    'redis_port'               => 'Redis Port',

                    'mail_label'                  => 'Mail',
                    'mail_driver_label'           => 'Mail Driver',
                    'mail_driver_placeholder'     => 'Mail Driver',
                    'mail_host_label'             => 'Mail Host',
                    'mail_host_placeholder'       => 'Mail Host',
                    'mail_port_label'             => 'Mail Port',
                    'mail_port_placeholder'       => 'Mail Port',
                    'mail_username_label'         => 'Mail Username',
                    'mail_username_placeholder'   => 'Mail Username',
                    'mail_password_label'         => 'Mail Password',
                    'mail_password_placeholder'   => 'Mail Password',
                    'mail_encryption_label'       => 'Mail Encryption',
                    'mail_encryption_placeholder' => 'Mail Encryption',

                    'pusher_label'                  => 'Pusher',
                    'pusher_app_id_label'           => 'Pusher App Id',
                    'pusher_app_id_palceholder'     => 'Pusher App Id',
                    'pusher_app_key_label'          => 'Pusher App Key',
                    'pusher_app_key_palceholder'    => 'Pusher App Key',
                    'pusher_app_secret_label'       => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                ],
                'buttons'  => [
                    'setup_database'    => 'Setup Database',
                    'setup_application' => 'Setup Application',
                    'install'           => 'Install',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Step 3 | Environment Settings | Classic Editor',
            'title'         => 'Classic Environment Editor',
            'save'          => 'Save .env',
            'back'          => 'Use Form Wizard',
            'install'       => 'Save and Install',
        ],
        'success' => 'Your .env file settings have been saved.',
        'errors'  => 'Unable to save the .env file, Please create it manually.',
    ],

    'install'   => 'Install',

    /*
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Laravel Installer successfully INSTALLED on ',
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final'     => [
        'title'         => 'Installation Finished',
        'templateTitle' => 'Installation Finished',
        'finished'      => 'Application has been successfully installed.',
        'migration'     => 'Migration &amp; Seed Console Output:',
        'console'       => 'Application Console Output:',
        'log'           => 'Installation Log Entry:',
        'env'           => 'Final .env File:',
        'exit'          => 'Click here to exit',
    ],

    /*
     *
     * Update specific translations
     *
     */
    'updater'   => [
        /*
         *
         * Shared translations.
         *
         */
        'title'    => 'Laravel Updater',

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome'  => [
            'title'   => 'Welcome To The Updater',
            'message' => 'Welcome to the update wizard.',
        ],

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'           => 'Overview',
            'message'         => 'There is 1 update.|There are :number updates.',
            'install_updates' => 'Install Updates',
        ],

        /*
         *
         * Final page translations.
         *
         */
        'final'    => [
            'title'    => 'Finished',
            'finished' => 'Application\'s database has been successfully updated.',
            'exit'     => 'Click here to exit',
        ],

        'log' => [
            'success_message' => 'Laravel Installer successfully UPDATED on ',
        ],
    ],
];