import axios from 'axios'
import moment from 'moment'
import BaseModel, { EntityStats, LineItem } from './BaseModel'
import { consts } from '../utils/_consts'

export const invoice_pdf_fields = ['$invoice.invoice_number', '$invoice.po_number', '$invoice.invoice_date', '$invoice.due_date',
    '$invoice.balance_due', '$invoice.invoice_total', '$invoice.partial_due', '$invoice.invoice1', '$invoice.invoice2', '$invoice.invoice3',
    '$invoice.invoice4', '$invoice.surcharge1', '$invoice.surcharge2', '$invoice.surcharge3', '$invoice.surcharge4'
]

export default class RecurringInvoiceModel extends BaseModel {
    constructor (data = null, customers = []) {
        super()
        this.customers = customers
        this._url = '/api/recurring-invoice'
        this.entity = 'RecurringInvoice'

        this._file_count = 0

        if (data !== null && data.files) {
            this.fileCount = data.files
        }

        this._fields = {
            start_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            expiry_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            frequency: '',
            grace_period: 0,
            date_to_send: '',
            last_sent_date: '',
            is_mobile: window.innerWidth <= 768,
            modalOpen: false,
            is_amount_discount: false,
            deleted_at: null,
            assigned_to: '',
            invitations: [],
            invoices: [],
            emails: [],
            customer_id: '',
            project_id: '',
            user_id: null,
            account_id: JSON.parse(localStorage.getItem('appState')).user.account_id,
            contacts: [],
            quantity: '',
            number: null,
            id: null,
            line_items: [],
            address: {},
            customerName: '',
            tax_rate_name: '',
            tax_rate: 0,
            tax_rate_name_2: '',
            tax_rate_name_3: '',
            tax_2: 0,
            tax_3: 0,
            company_id: '',
            status_id: null,
            tasks: [],
            errors: [],
            total: 0,
            discount_total: 0,
            tax_total: 0,
            sub_total: 0,
            data: [],
            date: moment(new Date()).format('YYYY-MM-DD'),
            partial: 0,
            partial_due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            has_partial: false,
            auto_billing_enabled: this.settings.autobilling_enabled,
            number_of_occurrances: 1,
            public_notes: '',
            private_notes: '',
            terms: '',
            footer: '',
            visible: 'collapse',
            custom_value1: '',
            custom_value2: '',
            custom_value3: '',
            custom_value4: '',
            transaction_fee_tax: false,
            shipping_cost_tax: false,
            transaction_fee: 0,
            shipping_cost: 0,
            gateway_fee: 0,
            gateway_percentage: false,
            tax: 0,
            total_custom_values: 0,
            total_custom_tax: 0,
            discount: 0,
            recurring: '',
            activeTab: '1',
            currency_id: this.settings.currency_id.toString().length ? this.settings.currency_id : consts.default_currency,
            po_number: '',
            design_id: '',
            success: false,
            showSuccessMessage: false,
            showErrorMessage: false,
            loading: false,
            changesMade: false
        }

        this.approved = 4
        this.reversed = consts.invoice_status_reversed
        this.cancelled = consts.invoice_status_cancelled
        this.paid = consts.invoice_status_paid
        this.sent = consts.invoice_status_sent
        this.partial = consts.invoice_status_partial

        this.customer = null

        if (data !== null) {
            this._fields = { ...this.fields, ...data }

            this.updateCustomer()
        }

        if (this.customer && this.customer.currency_id.toString().length) {
            const currency = JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === this.customer.currency_id)
            this.exchange_rate = currency[0].exchange_rate
        }

        this.exchange_rate = this.currency ? this.currency.exchange_rate : 1

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.account = user_account[0]
    }

    cloneInvoice (invoice) {
        this._fields = { ...this.fields, ...invoice }
        this.fields.id = null
        this.fields.invoice_id = invoice.id
        this.fields.gateway_fee_applied = 0
        this.fields.gateway_fee = null
        this.fields.gateway_percentage = null
        this.fields.number = null
        this.fields.status_id = consts.recurring_invoice_status_draft
        this.fields.modalOpen = true
    }

    get exchange_rate () {
        return this.fields.exchange_rate
    }

    set exchange_rate (exchange_rate) {
        this.fields.exchange_rate = exchange_rate
    }

    get isNew () {
        return !this.fields.id || !this.fields.id.toString().length || parseInt(this.fields.id) <= 0
    }

    get customer () {
        return this._customer
    }

    set customer (customer) {
        this._customer = customer
    }

    get id () {
        return this.fields.id
    }

    get fields () {
        return this._fields
    }

    get isDraft () {
        return parseInt(this.fields.status_id) === consts.recurring_invoice_status_draft
    }

    get isStopped () {
        return parseInt(this.fields.status_id) === consts.recurring_invoice_status_stopped
    }

    get isPending () {
        return parseInt(this.fields.status_id) === consts.recurring_invoice_status_pending
    }

    get isActive () {
        return parseInt(this.fields.status_id) === consts.recurring_invoice_status_active
    }

    get isCompleted () {
        return parseInt(this.fields.status_id) === consts.recurring_invoice_status_completed
    }

    get isDeleted () {
        return this.fields.deleted_at && this.fields.deleted_at.length > 0
    }

    get isEditable () {
        return !this.isCompleted
    }

    get fileCount () {
        return this._file_count || 0
    }

    set fileCount (files) {
        this._file_count = files ? files.length : 0
    }

    get invitations () {
        return this.fields.invitations
    }

    get invitation_link () {
        return `http://${this.account.account.subdomain}portal/recurring-invoices/$key`
    }

    get customer_id () {
        return this.fields.customer_id
    }

    set customer_id (customer_id) {
        this.fields.customer_id = customer_id
        this.updateCustomer()
    }

    get invoices () {
        return this.fields.invoices
    }

    get contacts () {
        const index = this.customers.findIndex(customer => customer.id === this.fields.customer_id)
        const customer = this.customers[index]
        return customer.contacts ? customer.contacts : []
    }

    get url () {
        return this._url
    }

    updateCustomer () {
        if (this.customers.length && this._fields.customer_id) {
            const customer = this.customers.filter(customer => customer.id === parseInt(this._fields.customer_id))
            this.customer = customer[0]
        }
    }

    buildInvitations (contact, add = false) {
        const invitations = this.fields.invitations

        // check if the check box is checked or unchecked
        if (add) {
            // add the numerical value of the checkbox to options array
            invitations.push({ contact_id: contact })
        } else {
            // or remove the value from the unchecked checkbox from the array
            const index = invitations.findIndex(contact => contact.contact_id === contact)
            invitations.splice(index, 1)
        }

        return invitations
    }

    buildDropdownMenu () {
        const actions = []

        if (this.fields.invitations.length) {
            actions.push('pdf')
        }

        if (this.fields.customer_id !== '') {
            actions.push('email')
        }

        if (this.isDraft || this.isStopped) {
            actions.push('start_recurring')
        }

        if (this.isPending || this.isActive) {
            actions.push('stop_recurring')
        }

        if (!this.fields.is_deleted) {
            actions.push('delete')
        }

        if (!this.fields.deleted_at) {
            actions.push('archive')
        }

        actions.push('cloneRecurringToInvoice')

        return actions
    }

    addItem () {
        // const newArray = this.fields.line_items.slice()
        this.fields.line_items.push(LineItem)
        return this.fields.line_items
    }

    removeItem (index) {
        const array = [...this.fields.line_items] // make a separate copy of the array
        array.splice(index, 1)
        this.fields.line_items = array
        return array
    }

    async completeAction (data, action) {
        if (!this.fields.id) {
            return false
        }

        this.errors = []
        this.error_message = ''

        try {
            const res = await axios.post(`${this.url}/${this.fields.id}/${action}`, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    async loadPdf () {
        try {
            this.errors = []
            this.error_message = ''
            const res = await axios.post('api/preview', { entity: this.entity, entity_id: this._fields.id })

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }

            // Don't forget to return something
            return this.buildPdf(res.data)
        } catch (e) {
            alert(e)
            this.handleError(e)
            return false
        }
    }

    async update (data) {
        if (!this.fields.id) {
            return false
        }

        this.errors = []
        this.error_message = ''

        try {
            const res = await axios.put(`${this.url}/${this.fields.id}`, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    isLate () {
        const dueDate = moment(this._fields.due_date).format('YYYY-MM-DD HH::MM:SS')
        const pending_statuses = [consts.invoice_status_draft, consts.invoice_status_sent, consts.invoice_status_partial]

        return moment().isAfter(dueDate) && pending_statuses.includes(this._fields.status_id)
    }

    async save (data) {
        if (this.fields.id) {
            return this.update(data)
        }

        try {
            this.errors = []
            this.error_message = ''
            const res = await axios.post(this.url, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    customerChange (customer_id) {
        const index = this.customers.findIndex(customer => customer.id === parseInt(customer_id))
        const customer = this.customers[index]
        const address = customer.billing ? {
            line1: customer.billing.address_1,
            town: customer.billing.address_2,
            county: customer.billing.city,
            country: 'United Kingdom'
        } : null

        const contacts = customer.contacts ? customer.contacts : []

        return {
            customer: customer,
            customerName: customer.name,
            contacts: contacts,
            address: address

        }
    }

    recurringInvoiceStatsForInvoice (recurringInvoiceId, invoices) {
        let countActive = 0
        let countArchived = 0

        invoices.forEach((invoice, invoice_id) => {
            if (invoice.recurring_invoice_id === parseInt(recurringInvoiceId)) {
                if (!invoice.deleted_at || !invoice.deleted_at.toString().length) {
                    countActive++
                } else if (invoice.deleted_at && invoice.deleted_at.toString().length) {
                    countArchived++
                }
            }
        })

        const entityStats = new EntityStats(countActive, countArchived)
        return entityStats.present()
    }
}
