<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// routes/api.php

Route::group(['middleware' => ['jwt.auth', 'api-header']], function () {

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('status/{task_type}', 'TaskStatusController@index');
        Route::get('dashboard', 'DashboardController@index');
        Route::get('activity', 'ActivityController@index');

        //support
        Route::post('support/messages/send', 'Support\Messages\SendingController');

        // company ledger
        Route::get('company_ledger', 'CompanyLedgerController@index')->name('company_ledger.index');

        // tokens
        Route::resource('tokens', 'TokenController');// name = (tokens. index / create / show / update / destroy / edi

        // subscription
          /*Subscription and Webhook routes */
        Route::post('hooks', 'SubscriptionController@subscribe');
        Route::delete('hooks/{subscription_id}', 'SubscriptionController@unsubscribe');
        Route::resource('subscriptions', 'SubscriptionController');

        //design
        Route::resource('designs', 'DesignController');// name = (payments. index / create / show / update / destroy / edit

        // messages
        Route::get('messages/customers', 'MessageController@getCustomers');
        Route::get('messages/{customer_id}', 'MessageController@index');
        Route::post('messages', 'MessageController@store');

        //companies
        Route::get('companies', 'CompanyController@index');
        Route::post('companies/restore/{id}', 'CompanyController@restore');
        Route::post('companies', 'CompanyController@store');
        Route::delete('companies/archive/{company_id}', 'CompanyController@archive');
        Route::delete('companies/{company_id}', 'CompanyController@destroy');
        Route::get('companies/{company_id}', 'CompanyController@show');
        Route::put('companies/{company_id}', 'CompanyController@update');
        Route::get('industries', 'CompanyController@getIndustries');

//categories
        Route::get('categories', 'CategoryController@index');
        Route::post('categories', 'CategoryController@store');
        Route::delete('categories/{category_id}', 'CategoryController@destroy');
        Route::get('categories/{category_id}', 'CategoryController@edit');
        Route::put('categories/{category_id}', 'CategoryController@update');

// comments
        Route::get('comments/{task_id}', 'CommentController@index');
        Route::delete('comments/{comment_id}', 'CommentController@destroy');
        Route::put('comments/{comment_id}', 'CommentController@update');
        Route::post('comments', 'CommentController@store');

// events
        Route::get('events', 'EventController@index');
        Route::delete('events/archive/{event_id}', 'EventController@archive');
        Route::delete('events/{event_id}', 'EventController@destroy');
        Route::put('events/{event_id}', 'EventController@update');
        Route::get('events/{event_id}', 'EventController@show');
        Route::post('events', 'EventController@store');
        Route::get('events/tasks/{task_id}', 'EventController@getEventsForTask');
        Route::get('events/users/{user_id}', 'EventController@getEventsForUser');
        Route::get('event-types', 'EventController@getEventTypes');
        Route::post('events/filterEvents',
            'EventController@filterEvents');
        Route::post('event/status/{event_id}', 'EventController@updateEventStatus');
        Route::post('events/restore/{id}', 'EventController@restore');

// products
        Route::get('products', 'ProductController@index');
        Route::post('products', 'ProductController@store');
        Route::post('products/bulk', 'ProductController@bulk');
        Route::delete('products/archive/{product_id}', 'ProductController@archive');
        Route::delete('products/{product_id}', 'ProductController@destroy');
        Route::post('products/removeImages', 'ProductController@removeThumbnail');
        Route::put('products/{product_id}', 'ProductController@update');
        Route::get('products/tasks/{task_id}/{status}', 'OrderController@getOrderForTask');
        Route::post('products/filterProducts', 'ProductController@filterProducts');
        Route::get('product/{slug}', 'ProductController@getProduct');
        //Route::get('products/{product_id}', 'ProductController@show');
        Route::post('products/restore/{id}', 'ProductController@restore');

// projects
        Route::get('projects', 'ProjectController@index');
        Route::get('projects', 'ProjectController@index');
        Route::post('projects', 'ProjectController@store');
        Route::get('projects/{id}', 'ProjectController@show');
        Route::put('projects/{project}', 'ProjectController@update');
        Route::delete('projects/archive/{project_id}', 'ProjectController@archive');
        Route::delete('projects/{project_id}', 'ProjectController@destroy');
        Route::post('projects/restore/{id}', 'ProjectController@restore');

        //order
        Route::put('order/{order_id}', 'OrderController@update');
        Route::post('order', 'OrderController@store');
        Route::post('order/{order}/{action}', 'OrderController@action')->name('invoices.action');

// uploads
        Route::post('uploads', 'UploadController@store');
        Route::get('uploads/{entity}/{entity_id}', 'UploadController@index');
        Route::delete('uploads/file_id', 'UploadController@destroy');

// task status
        Route::get('taskStatus/search', 'TaskStatusController@search');
        Route::get('taskStatus', 'TaskStatusController@index');
        Route::post('taskStatus', 'TaskStatusController@store');
        Route::put('taskStatus/{project}', 'TaskStatusController@update');
        Route::delete('taskStatus/{status_id}', 'TaskStatusController@destroy');
    });

    // invoice

    Route::post('invoice', 'InvoiceController@store')->middleware('role:null,invoicecontroller.store');
    Route::delete('invoice/archive/{invoice_id}', 'InvoiceController@archive');
    Route::delete('invoice/{invoice_id}', 'InvoiceController@destroy');
    Route::post('invoice/restore/{id}', 'InvoiceController@restore')->middleware('role:null,invoicecontroller.index');;
    Route::post('invoice/bulk', 'InvoiceController@bulk');

    Route::get('invoice', 'InvoiceController@index')->middleware('role:null,invoicecontroller.index');
    Route::get('invoice/task/{task_id}',
        'InvoiceController@getInvoiceLinesForTask')->middleware('role:null,invoicelinecontroller.getinvoicelinesfortask');
    Route::get('invoice/{invoice_id}', 'InvoiceController@show')->middleware('role:null,invoicecontroller.show');
   
    Route::put('invoice/{invoice_id}', 'InvoiceController@update')->middleware('role:null,invoicecontroller.update');
    Route::get('invoice/getInvoicesByStatus/{status}',
        'InvoiceController@getInvoicesByStatus')->middleware('role:null,invoicecontroller.index');
    Route::post('invoice/{invoice}/{action}', 'InvoiceController@action')->name('invoices.action');


    //recurring invoice
    Route::post('recurring-invoice',
        'RecurringInvoiceController@store')->middleware('role:null,invoicecontroller.store');
    Route::post('recurring-invoice/bulk',
        'RecurringInvoiceController@bulk')->middleware('role:null,invoicecontroller.bulk');
    Route::put('recurring-invoice/{id}',
        'RecurringInvoiceController@update')->middleware('role:null,invoicecontroller.store');
    Route::delete('recurring-invoice/archive/{id}',
        'RecurringInvoiceController@archive')->middleware('role:null,invoicecontroller.store');
    Route::delete('recurring-invoice/{id}',
        'RecurringInvoiceController@destroy')->middleware('role:null,invoicecontroller.store');
    Route::get('recurring-invoice',
        'RecurringInvoiceController@index')->middleware('role:null,invoicecontroller.store');
    Route::post('recurringInvoice/restore/{id}', 'RecurringInvoiceController@restore');

    //recurring quote
    Route::put('recurring-quote/{id}',
        'RecurringQuoteController@update')->middleware('role:null,invoicecontroller.store');
    Route::get('recurring-quote', 'RecurringQuoteController@index')->middleware('role:null,invoicecontroller.store');
    Route::post('recurring-quote', 'RecurringQuoteController@store')->middleware('role:null,invoicecontroller.store');
    Route::post('recurring-quote/bulk',
        'RecurringQuoteController@bulk')->middleware('role:null,invoicecontroller.bulk');
    Route::delete('recurring-quote/archive/{id}',
        'RecurringQuoteController@archive')->middleware('role:null,invoicecontroller.store');
    Route::delete('recurring-quote/{id}',
        'RecurringQuoteController@destroy')->middleware('role:null,invoicecontroller.store');
    Route::post('recurringQuote/restore/{id}', 'RecurringQuoteController@restore');

    //credit
    Route::post('credit', 'CreditController@store');
    Route::delete('credits/archive/{credit_id}', 'CreditController@archive');
    Route::delete('credits/{credit_id}', 'CreditController@destroy');
    Route::get('credits', 'CreditController@index');
    Route::put('credit/{credit_id}', 'CreditController@update');
    Route::post('credits/restore/{id}', 'CreditController@restore');
    Route::post('credit/{credit}/{action}', 'CreditController@action')->name('credits.action');

    //expenses
    Route::post('expense', 'ExpenseController@store');
    Route::delete('expenses/archive/{expense_id}', 'ExpenseController@archive');
    Route::delete('expenses/{expense_id}', 'ExpenseController@destroy');
    Route::get('expenses', 'ExpenseController@index');
    Route::put('expense/{expense_id}', 'ExpenseController@update');
    Route::post('expenses/restore/{id}', 'ExpenseController@restore');

    // quotes
    Route::get('quotes/convert/{invoice_id}',
        'QuoteController@convert')->middleware('role:null,invoicecontroller.show');
    Route::delete('quote/archive/{quote_id}', 'QuoteController@archive');
    Route::delete('quote/{quote_id}', 'QuoteController@destroy');
    Route::get('quotes/approve/{invoice_id}',
        'QuoteController@approve')->middleware('role:null,invoicecontroller.show');
    Route::post('quote', 'QuoteController@store')->middleware('role:null,invoicecontroller.store');

    Route::put('quote/{quote_id}', 'QuoteController@update')->middleware('role:null,invoicecontroller.update');
    Route::get('quote', 'QuoteController@index')->middleware('role:null,invoicecontroller.index');
    Route::get('quote/{quote_id}', 'QuoteController@show')->middleware('role:null,invoicecontroller.show');
    Route::post('quote/{quote}/{action}', 'QuoteController@action')->name('quotes.action');
    Route::get('quotes/task/{task_id}',
        'QuoteController@getQuoteLinesForTask')->middleware('role:null,invoicelinecontroller.getinvoicelinesfortask');
    Route::post('quotes/restore/{id}', 'QuoteController@restore');

    //accounts
    Route::post('accounts', 'AccountController@store')->middleware('role:null,invoicecontroller.store');
    Route::post('accounts/fields',
        'AccountController@saveCustomFields')->middleware('role:null,invoicecontroller.store');
    Route::put('accounts/{id}', 'AccountController@update')->middleware('role:null,invoicecontroller.store');
    Route::get('accounts/fields/getAllCustomFields',
        'AccountController@getAllCustomFields')->middleware('role:null,invoicecontroller.store');
    Route::get('accounts/fields/{entity}', 'AccountController@getCustomFields');
    Route::get('accounts', 'AccountController@index');
    Route::get('accounts/{id}', 'AccountController@show');
    Route::get('dates', 'AccountController@getDateFormats');
    Route::delete('account/{account_id}', 'AccountController@destroy');

    // email
    Route::post('emails', 'EmailController@send')->name('email.send');
    
    Route::post('account/change',
        'AccountController@changeAccount')->middleware('role:null,invoicecontroller.store');

    // group settings
    Route::get('groups', 'GroupSettingController@index');
    Route::delete('groups/archive/{group_id}', 'GroupSettingController@archive');
    Route::delete('groups/{group_id}', 'GroupSettingController@destroy');
    Route::put('groups/{group_id}', 'GroupSettingController@update');
    Route::post('groups', 'GroupSettingController@store');
    Route::post('groups/restore/{id}', 'GroupSettingController@restore');

    //template
    Route::post('template', 'TemplateController@show');

    // company gateways
    Route::get('company_gateways', 'CompanyGatewayController@index');
    Route::get('company_gateways/{id}', 'CompanyGatewayController@show');
    Route::put('company_gateways/{id}', 'CompanyGatewayController@update');
    Route::post('company_gateways/', 'CompanyGatewayController@store');

    // tax rates
    Route::get('taxRates', 'TaxRateController@index')->middleware('role:null,invoicecontroller.index');
    Route::post('taxRates', 'TaxRateController@store');
    Route::delete('taxRates/archive/{taxRate_id}', 'TaxRateController@archive');
    Route::delete('taxRates/{taxRate_id}', 'TaxRateController@destroy');
    Route::get('taxRates/{taxRate_id}', 'TaxRateController@edit');
    Route::put('taxRates/{taxRate_id}', 'TaxRateController@update');
    Route::post('taxRate/restore/{id}', 'TaxRateController@restore');

    //payments
    Route::get('payments', 'PaymentController@index')->middleware('role:null,invoicecontroller.index');
    Route::get('payments/{payment_id}', 'PaymentController@show')->middleware('role:null,invoicecontroller.index');
    Route::post('payments', 'PaymentController@store');
    Route::post('payments/bulk', 'PaymentController@bulk')->middleware('role:null,paymentcontroller.bulk');
    Route::delete('payments/archive/{payment_id}', 'PaymentController@archive');
    Route::delete('payments/{payment_id}', 'PaymentController@destroy');
    Route::put('payments/{payment_id}', 'PaymentController@update');
    Route::put('refund/{payment_id}', 'PaymentController@refund');
    Route::post('payments/{payment}/{action}', 'PaymentController@action')->name('payments.action');
    Route::post('payments/restore/{id}', 'PaymentController@restore');

    //payment method
    Route::get('paymentType', 'PaymentTypeController@index');

// customers
 Route::get('customers', 'CustomerController@index');
    Route::get('customers/dashboard',
        'CustomerController@dashboard')->middleware('role:null,customercontroller.dashboard');
    Route::get('customers/{customer_id}', 'CustomerController@show')->middleware('role:null,customercontroller.show');
    Route::put('customers/{customer_id}',
        'CustomerController@update')->middleware('role:null,customercontroller.update');
    Route::post('customers', 'CustomerController@store')->middleware('role:null,customercontroller.store');
    Route::post('customers/bulk', 'CustomerController@bulk')->middleware('role:null,customercontroller.bulk');
    Route::delete('customers/archive/{customer_id}',
        'CustomerController@archive')->middleware('role:null,customercontroller.destroy');
    Route::delete('customers/{customer_id}',
        'CustomerController@destroy')->middleware('role:null,customercontroller.destroy');
    Route::get('customer-types',
        'CustomerController@getCustomerTypes')->middleware('role:null,customercontroller.show');
    Route::post('customers/restore/{id}', 'CustomerController@restore');

// tasks
    Route::post('tasks/restore/{id}', 'TaskController@restore');
    Route::put('tasks/{task_id}', 'TaskController@update')->middleware('role:null,taskcontroller.update');
    Route::post('tasks', 'TaskController@store')->middleware('role:null,taskcontroller.store');
    Route::get('tasks/getTasksForProject/{project_id}',
        'TaskController@getTasksForProject')->middleware('role:null,taskcontroller.gettasksforproject');
    Route::put('tasks/complete/{task}',
        'TaskController@markAsCompleted')->middleware('role:null,taskcontroller.markascompleted');
    Route::delete('tasks/{task}', 'TaskController@destroy')->middleware('role:null,taskcontroller.destroy');
  
    Route::put('tasks/status/{task_id}',
        'TaskController@updateStatus')->middleware('role:null,taskcontroller.updatestatus');
    Route::get('deals', 'TaskController@getDeals')->middleware('role:null,taskcontroller.getdeals');
    Route::get('tasks', 'TaskController@index')->middleware('role:null,taskcontroller.index');
    Route::get('tasks/subtasks/{task_id}',
        'TaskController@getSubtasks')->middleware('role:null,taskcontroller.getsubtasks');
    Route::get('tasks/products/{task_id}',
        'TaskController@getProducts')->middleware('role:null,taskcontroller.getproducts');
    Route::get('tasks/products', 'TaskController@getTasksWithProducts')->middleware('role:null,view-invoice');
    Route::get('tasks/source-types', 'TaskController@getSourceTypes');
    Route::get('tasks/task-types', 'TaskController@getTaskTypes')->middleware('role:null,view-invoice');
    Route::get('tasks/convertToDeal/{task_id}', 'TaskController@convertToDeal')->middleware('role:null,view-invoice');
    Route::put('tasks/timer/{task_id}', 'TaskController@updateTimer')->middleware('role:null,taskcontroller.update');
    Route::delete('tasks/archive/{task_id}', 'TaskController@archive');

    // leads
    Route::get('leads', 'LeadController@index');
    Route::put('lead/{lead_id}', 'LeadController@update')->middleware('role:null,taskcontroller.update');
    Route::delete('leads/archive/{lead_id}', 'LeadController@archive');
    Route::delete('leads/{lead}', 'LeadController@destroy')->middleware('role:null,taskcontroller.destroy');
    Route::post('leads/restore/{id}', 'LeadController@restore');

    Route::group(['middleware' => ['role:Manager']], function () {

        // users
        Route::delete('users/archive/{user_id}', 'UserController@archive');
        Route::delete('users/{user_id}', 'UserController@destroy');
        Route::post('users', 'UserController@store');
        Route::get('users/dashboard', 'UserController@dashboard');
        Route::get('users/edit/{user_id}', 'UserController@edit');
        Route::put('users/{user_id}', 'UserController@update');
        Route::get('users', 'UserController@index');
        Route::post('user/upload', 'UserController@upload');
        Route::post('user/bulk', 'UserController@bulk');
        Route::get('user/profile/{username}', 'UserController@profile');
        Route::get('users/department/{department_id}', 'UserController@filterUsersByDepartment');
        Route::post('users/restore/{id}', 'UserController@restore');

        // permissions
        Route::get('permissions', 'PermissionController@index');
        Route::post('permissions', 'PermissionController@store');
        Route::delete('permissions/{permission_id}', 'PermissionController@destroy');
        Route::get('permissions/{permission_id}', 'PermissionController@edit');
        Route::put('permissions/{permission_id}', 'PermissionController@update');

// roles
        Route::get('roles', 'RoleController@index');
        Route::post('roles', 'RoleController@store');
        Route::delete('roles/{role_id}', 'RoleController@destroy');
        Route::get('roles/{role_id}', 'RoleController@edit');
        Route::put('roles/{role_id}', 'RoleController@update');

//departments
        Route::get('departments', 'DepartmentController@index');
        Route::post('departments', 'DepartmentController@store');
        Route::delete('departments/{department_id}', 'DepartmentController@destroy');
        Route::get('departments/{department_id}', 'DepartmentController@edit');
        Route::put('departments/{department_id}', 'DepartmentController@update');

        Route::get('countries', 'CountryController@index');
        Route::get('currencies', 'CurrencyController@index');

        Route::resource('timer', 'TimerController');
    });
});

Route::group(['middleware' => 'api-header'], function () {

    // login
    Route::get('login', 'LoginController@showLogin');
    Route::post('login', 'LoginController@doLogin');
    Route::get('logout', 'LoginController@doLogout');

    Route::post('passwordReset/create', 'Auth\PasswordResetController@create');
    Route::get('passwordReset/find/{token}', 'PasswordResetController@find');
    Route::post('passwordReset/reset', 'Auth\PasswordResetController@reset');

    // unprotected routes for website
    Route::get("category-list", 'CategoryController@getRootCategories');
    Route::get("categories/children/{slug}", 'CategoryController@getChildCategories');
    Route::get("category/form/{id}", 'CategoryController@getForm');
    Route::get("category/{slug}", 'CategoryController@getCategory');
    Route::post('tasks/products/{task_id}', 'TaskController@addProducts');
    Route::post("categories/products/{id}", 'ProductController@getProductsForCategory');
    Route::post('tasks/deal', 'TaskController@createDeal');
    Route::post('lead', 'LeadController@store');
    Route::get('lead/convert/{id}', 'LeadController@convert');
   
    Route::get('invoice/pdf/{key}', 'InvoiceController@markViewed');
    Route::get('quote/pdf/{key}', 'QuoteController@markViewed');
    Route::get('credit/pdf/{key}', 'CreditController@markViewed');
    Route::get('order/pdf/{key}', 'OrderController@markViewed');

    Route::post('invoice/download', 'InvoiceController@downloadPdf');
    Route::post('quote/download', 'QuoteController@downloadPdf');
    Route::post('order/download', 'OrderController@downloadPdf');
    Route::post('credit/download', 'CreditController@downloadPdf');

    Route::post('payment/completePayment', 'PaymentController@completePayment');
    Route::post('recurring-invoice/cancel', 'RecurringInvoiceController@requestCancellation');
    Route::post('quote/bulk', 'QuoteController@bulk');
    Route::post('order/bulk', 'OrderController@bulk');
    Route::get('products/{product_id}', 'ProductController@show');

    Route::post('preview', 'PreviewController@show');
});
