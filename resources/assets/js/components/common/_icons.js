import { Input } from "reactstrap";
import React from "react";

export const icons = {
    refresh: 'fa-gear',
    checkbox: 'fa-check-square',
    table: 'fa-table',
    columns: 'fa-columns',
    download: 'fa-download',
    email: 'fa-envelope-square',
    mark_sent: 'fa-share-square',
    customer: 'fa-user-circle-o',
    approve: 'fa-check-square',
    clone: 'fa-copy',
    delete: 'fa-trash',
    mark_paid: 'fa-credit-card',
    credit_card: 'fa-credit-card-alt',
    cancel: 'fa-power-off',
    reverse: 'fa-backward',
    archive: 'fa-archive',
    document: 'fa-file',
    products: 'fa-barcode',
    add: 'fa-plus',
    ellipsis: 'fa-ellipsis-h',
    down: 'fa-chevron-down',
    right: 'fa-chevron-right',
    restore: 'fa-window-restore',
    clear: 'fa-times',
    edit: 'fa-edit',
    refund: 'fa-credit-card',
    phone: 'fa-phone',
    link: 'fa-link',
    building: 'fa-building',
    list: 'fa-list-ol',
    map_marker: 'fa-map-marker',
    envelope: 'fa-envelope',
    user: 'fa-user',
    project: 'fa-briefcase',
    task: 'fa-clock-o',
    expense: 'fa-bar-chart-o',
    company: 'fa-building',
    product: 'fa-barcode',
    order: 'fa-shopping-cart',
    calendar: 'fa-calendar',
    percent: 'fa-percent',
    spinner: 'fa-circle-o-notch fa-spin'
}

export const translations = {
    low: 'Low',
    medium: 'Medium',
    high: 'High',
    priority: 'Priority',
    select_option: 'Select Option',
    select_event: 'Select Event',
    order_created: 'Order Created',
    order_deleted: 'Order Deleted',
    credit_created: 'Credit Created',
    credit_deleted: 'Credit Deleted',
    customer_created: 'Customer Created',
    customer_deleted: 'Customer Deleted',
    invoice_created: 'Invoice Created',
    invoice_deleted: 'Invoice Deleted',
    payment_created: 'Payment Created',
    payment_deleted: 'Payment Deleted',
    order_backordered: 'Order Backordered',
    quote_created: 'Quote Created',
    quote_deleted: 'Quote Deleted',
    lead_created: 'Lead Created',
    subject: 'Subject',
    add_case: 'Add Case',
    edit_case: 'Edit Case',
    message: 'Message',
    target_url: 'Target URL',
    event: 'Event',
    maximum_5_features: 'You can only add 5 features for a product',
    features: 'Features',
    attributes: 'Attributes',
    order_filfilled: 'The Order has been fulfilled and items have been removed from backorder',
    order_held: 'The Order has been held',
    order_unheld: 'The order has been removed from hold and put back to its initial status',
    fulfill: 'Fulfill Order',
    hold_order: 'Hold Order',
    unhold_order: 'Unhold Order',
    code: 'Code',
    add_payment_term: 'Add Payment Term',
    edit_payment_term: 'Edit Payment Term',
    number_of_days: 'Number of days',
    budgeted: 'Budgeted',
    budgeted_hours: 'Budgeted Hours',
    applied: 'Applied',
    backordered: 'Backorder',
    held: 'Held',
    draft: 'Draft',
    select_status: 'Select Status',
    cancelled: 'Cancelled',
    reversed: 'Reversed',
    logged: 'Logged',
    pending: 'Pending',
    invoiced: 'Invoiced',
    complete: 'Complete',
    dispatched: 'Dispatched',
    voided: 'Voided',
    failed: 'Failed',
    partial_refund: 'Partially Refunded',
    expired: 'Expired',
    payment_date: 'Payment Date',
    currency: 'Currency',
    exchange_rate: 'Exchange Rate',
    converted: 'Converted',
    pdf: 'PDF',
    discount: 'Discount',
    subtotal: 'Subtotal',
    tax: 'Tax',
    total: 'Total',
    paid_to_date: 'Paid To Date',
    balance: 'Balance',
    billing_address: 'Billing Address',
    shipping_address: 'Shipping Address',
    number: 'Number',
    amount_type: 'Amount Type',
    edit_promocode: 'Edit Promocode',
    scope: 'Scope Type',
    redeemable: 'Redeemable Amount',
    amount_to_create: 'Amount To Create',
    scope_value: 'Scope Value',
    valued_at: 'Valued At',
    edit_company: ' Edit Brand',
    add_company: 'Add Company',
    address: 'Address',
    address_1: 'Address 1',
    address_2: 'Address 2',
    city: 'City',
    town: 'Town',
    vat_number: 'VAT Number',
    postcode: 'Postcode',
    country: 'Country',
    transaction_reference: 'Transaction Reference',
    payment_type: 'Payment Type',
    start_date: 'Start Date',
    end_date: 'End Date',
    frequency: 'Frequency (in days)',
    quote: 'Quote',
    public_notes: 'Public Notes',
    private_notes: 'Private Notes',
    edit_group: 'Edit Group',
    add_lead: 'Add Lead',
    edit_lead: 'Edit Lead',
    add_expense: 'Add Expense',
    edit_credit: 'Edit Credit',
    edit_expense: 'Edit Expense',
    edit_invoice: 'Edit Invoice',
    company: 'Company',
    add_invoice: 'Add Invoice',
    edit_quote: 'Edit Quote',
    add_quote: 'Add Quote',
    edit_customer: 'Edit Customer',
    add_customer: 'Add Customer',
    mark_sent: 'Mark Sent',
    edit_product: 'Edit Product',
    add_product: 'Add Product',
    edit_project: 'Edit Project',
    add_project: 'Add Project',
    edit_task: 'Edit Task',
    add_order: 'Add Order',
    edit_order: 'Edit Order',
    on_order: 'On Order',
    edit_recurring_invoice: 'Edit Recurring Invoice',
    edit_recurring_quote: 'Edit Recurring Quote',
    add_recurring_invoice: 'Add Recurring Invoice',
    add_recurring_quote: 'Add Recurring Quote',
    add_token: 'Add Token',
    edit_token: 'Edit Token',
    add_tax_rate: 'Add Tax Rate',
    edit_tax_rate: 'Edit Tax Rate',
    edit_user: 'Edit User',
    add_user: 'Add User',
    notifications: 'Notifications',
    permissions: 'Permissions',
    add_task: 'Add Task',
    details: 'Details',
    edit_payment: 'Edit Payment',
    add_payment: 'Add Payment',
    documents: 'Documents',
    settings: 'Settings',
    notes: 'Notes',
    items: 'Items',
    actions: 'Actions',
    email: 'Email',
    contacts: 'Contacts',
    close: 'Close',
    save: 'Save',
    convert_lead: 'Convert to deal',
    refund: 'Refund',
    refund_payment: 'Refund Payment',
    cancelled_invoice: 'Successfully cancelled invoice',
    paid: 'Paid',
    approved: 'Successfully approved',
    emailed: 'Successfully sent email',
    downloaded: 'Successfully downloaded pdf file',
    refunded: 'Refunded',
    overdue: 'Overdue',
    sent: 'Sent',
    cancelled_invoices: 'Successfully cancelled invoices',
    reversed_invoice: 'Successfully reversed invoice',
    reversed_invoices: 'Successfully reversed invoices',
    reverse: 'Reverse',
    cancel: 'Cancel',
    full_name: 'Full Name',
    city_state_postal: 'City/State/Postal',
    postal_city_state: 'Postal/City/State',
    custom1: 'First Custom',
    custom2: 'Second Custom',
    custom3: 'Third Custom',
    custom4: 'Fourth Custom',
    optional: 'Optional',
    license: 'License',
    purge_data: 'Purge Data',
    purge_successful: 'Successfully purged company data',
    purge_data_message:
      'Warning: This will permanently erase your data, there is no undo.',
    invoice_balance: 'Invoice Balance',
    delete: 'Delete',
    delete_message: 'Do you want to delete this?',
    archive_message: 'Do you want to archive this?',
    refresh: 'Refresh',
    saved_design: 'Successfully saved design',
    client_details: 'Client Details',
    company_address: 'Company Address',
    invoice_details: 'Invoice Details',
    quote_details: 'Quote Details',
    credit_details: 'Credit Details',
    product_columns: 'Product Columns',
    task_columns: 'Task Columns',
    add_field: 'Add Field',
    all_events: 'All Events',
    archive: 'Archive',
    none: 'None',
    owned: 'Owned',
    payment_success: 'Payment Success',
    payment_failure: 'Payment Failure',
    invoice_sent: 'Invoice Sent',
    quote_sent: 'Quote Sent',
    mark_paid: 'Mark Paid',
    approve: 'Approve',
    download: 'Download',
    send_email: 'Send Email',
    credit_sent: 'Credit Sent',
    invoice_viewed: 'Invoice Viewed',
    quote_viewed: 'Quote Viewed',
    credit_viewed: 'Credit Viewed',
    quote_approved: 'Quote Approved',
    receive_all_notifications: 'Receive All Notifications',
    remove: 'Remove',
    cancel_account: 'Delete Account',
    cancel_account_message:
      'Warning: This will permanently delete your account, there is no undo.',
    delete_company: 'Delete Company',
    delete_company_message:
      'Warning: This will permanently delete your company, there is no undo.',
    enable_modules: 'Enable Modules',
    converted_quote: 'Successfully converted quote',
    credit_design: 'Credit Design',
    invoice: 'Invoice',
    brand: 'Brand',
    category: 'Category',
    quantity: 'Quantity',
    price: 'Price',
    cost: 'Cost',
    sku: 'Sku',
    is_featured: 'Is Featured',
    thumbnails: 'Thumbnails',
    cover: 'Cover Image',
    add_role: 'Add Role',
    edit_role: 'Edit Role',
    first_name: 'First Name',
    last_name: 'Last Name',
    department: 'Department',
    username: 'Username',
    po_number: 'PO Number',
    recurring: 'Is Recurring',
    recurring_due_date: 'Recurring Due Date',
    partial: 'Partial',
    partial_due_date: 'Partial Due Date',
    phone_number: 'Phone Number',
    password: 'Password',
    job_description: 'Job Description',
    website: 'Website',
    includes: 'Includes',
    header: 'Header',
    load_design: 'Load Design',
    css_framework: 'CSS Framework',
    custom_designs: 'Custom Designs',
    designs: 'Designs',
    new_design: 'New Design',
    edit_design: 'Edit Design',
    created_design: 'Successfully created design',
    updated_design: 'Successfully updated design',
    images: 'Images',
    variations: 'Variations',
    add_subscription: 'Add Subscription',
    add_attribute: 'Add Attribute',
    edit_attribute: 'Edit Attribute',
    edit_subscription: 'Edit Subscription',
    name: 'Name',
    add_department: 'Add Department',
    edit_department: 'Edit Department',
    body: 'Body',
    footer: 'Footer',
    product: 'Product',
    task: 'Task',
    preview: 'Preview',
    amount: 'Amount',
    title: 'Title',
    description: 'Description',
    customer: 'Customer',
    assigned_user: 'Assigned User',
    date: 'Date',
    due_date: 'Due Date',
    terms: 'Terms',
    start_time: 'Start Time',
    end_time: 'End Time',
    duration: 'Duration',
    expiry_date: 'Expiry Date',
    active: 'Active',
    deleted: 'Deleted',
    archived: 'Archived',
    viewed: 'Viewed',
    clone_to_invoice: 'Clone To Invoice',
    clone_quote_to_invoice: 'Clone Quote To Invoice',
    action_completed: 'Action Completed successfully!',
    expenses: 'Expenses',
    tasks: 'Tasks',
    projects: 'Projects',
    payments: 'Payments',
    invoices: 'Invoices',
    orders: 'Orders',
    overview: 'Overview'
}
