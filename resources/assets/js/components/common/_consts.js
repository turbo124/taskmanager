import React from 'react'

export const consts = {
    centimeters: 'cm',
    meters: 'mtr',
    inches: 'in',
    milimeters: 'mm',
    ounces: 'oz',
    grams: 'gms',
    pounds: 'lbs',
    foot: 'ft',
    yard: 'yd',
    low_priority: 1,
    medium_priority: 2,
    high_priority: 3,
    order_created_subscription: 1,
    order_deleted_subscription: 2,
    credit_created_subscription: 3,
    credit_deleted_subscription: 4,
    customer_created_subscription: 5,
    customer_deleted_subscription: 6,
    invoice_created_subscription: 7,
    invoice_deleted_subscription: 8,
    payment_created_subscription: 9,
    payment_deleted_subscription: 10,
    quote_created_subscription: 11,
    quote_deleted_subscription: 12,
    lead_created_subscription: 13,
    order_backordered_subscription: 14,
    order_held_subscription: 15,
    invoice_status_past_due: '-1',
    invoice_status_draft: 1,
    invoice_status_sent: 2,
    invoice_status_paid: 3,
    invoice_status_partial: 4,
    invoice_status_cancelled: 5,
    invoice_status_reversed: 6,
    invoice_status_past_due_text: 'Overdue',
    invoice_status_draft_text: 'Draft',
    invoice_status_sent_text: 'Sent',
    invoice_status_partial_text: 'Partial',
    invoice_status_paid_text: 'Paid',
    invoice_status_cancelled_text: 'Cancelled',
    invoice_status_reversed_text: 'Reversed',
    quote_status_expired: '-1',
    quote_status_draft: 1,
    quote_status_sent: 2,
    quote_status_invoiced: 5,
    quote_status_on_order: 6,
    quote_status_approved: 4,
    quote_status_expired_text: 'Expired',
    recurring_invoice_status_draft: 2,
    recurring_invoice_status_active: 3,
    recurring_invoice_status_cancelled: 4,
    recurring_invoice_status_pending: -1,
    recurring_invoice_status_completed: -2,
    recurring_quote_status_draft: 2,
    recurring_quote_status_active: 3,
    recurring_quote_status_cancelled: 4,
    recurring_quote_status_pending: -1,
    recurring_quote_status_completed: -2,
    quote_status_draft_text: 'Draft',
    quote_status_sent_text: 'Sent',
    quote_status_approved_text: 'Approved',
    order_status_draft: 1,
    order_status_cancelled: 8,
    order_status_sent: 2,
    order_status_held: 5,
    order_status_backorder: 6,
    order_status_partial: 7,
    order_status_complete: 3,
    order_status_approved: 4,
    order_status_draft_text: 'Draft',
    order_status_sent_text: 'Sent',
    order_status_approved_text: 'Approved',
    order_status_complete_text: 'Completed',
    credit_status_draft: 1,
    credit_status_sent: 2,
    credit_status_partial: 3,
    credit_status_applied: 4,
    case_status_draft: 1,
    credit_status_draft_text: 'Draft',
    credit_status_sent_text: 'Sent',
    credit_status_partial_text: 'Partial',
    credit_status_applied_text: 'Applied',
    payment_status_pending: 1,
    payment_status_voided: 2,
    payment_status_failed: 3,
    payment_status_completed: 4,
    payment_status_partial_refund: 5,
    payment_status_refunded: 6,
    payment_status_unapplied: 'unapplied',
    payment_status_pending_text: 'Pending',
    payment_status_voided_text: 'Voided',
    payment_status_failed_text: 'Failed',
    payment_status_completed_text: 'Completed',
    payment_status_partial_refund_text: 'Partially Refunded',
    payment_status_refunded_text: 'Refunded',
    expense_status_logged: 1,
    expense_status_pending: 2,
    expense_status_invoiced: 3,
    expense_status_logged_text: 'Logged',
    expense_status_pending_text: 'Pending',
    expense_status_invoiced_text: 'Invoiced',
    notification_payment_success: 'payment_success',
    notification_payment_refunded: 'payment_refunded',
    notification_lead_success: 'lead_success',
    notification_deal_success: 'deal_success',
    notification_payment_failure: 'payment_failure',
    notification_invoice_sent: 'invoice_sent',
    notification_credit_sent: 'credit_sent',
    notification_quote_sent: 'quote_sent',
    notification_invoice_viewed: 'invoice_viewed',
    notification_quote_viewed: 'quote_viewed',
    notification_credit_viewed: 'credit_viewed',
    notification_quote_approved: 'quote_approved',
    notification_order_created: 'order_created',
    notification_order_backordered: 'order_backordered',
    notification_order_held: 'order_held',
    email_design_plain: 'plain',
    email_design_light: 'light',
    email_design_dark: 'dark',
    lock_invoices_off: 'off',
    lock_invoices_sent: 'when_sent',
    lock_invoices_paid: 'when_paid',
    email_design_custom: 'custom',
    reminder_schedule_after_invoice_date: 'after_invoice_date',
    reminder_schedule_before_due_date: 'before_due_date',
    reminder_schedule_after_due_date: 'after_due_date'
}
