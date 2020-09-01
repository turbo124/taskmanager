import axios from 'axios'
import moment from 'moment'
import BaseModel, { LineItem } from './BaseModel'
import { consts } from '../common/_consts'

export const invoice_pdf_fields = ['$invoice.invoice_number', '$invoice.po_number', '$invoice.invoice_date', '$invoice.due_date',
    '$invoice.balance_due', '$invoice.invoice_total', '$invoice.partial_due', '$invoice.invoice1', '$invoice.invoice2', '$invoice.invoice3',
    '$invoice.invoice4', '$invoice.surcharge1', '$invoice.surcharge2', '$invoice.surcharge3', '$invoice.surcharge4'
]

export default class RecurringInvoiceModel extends BaseModel {
    constructor (data = null, customers = null) {
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
            end_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            frequency: 1,
            is_mobile: window.innerWidth <= 768,
            modalOpen: false,
            is_amount_discount: false,
            deleted_at: null,
            assigned_to: '',
            invitations: [],
            invoices: [],
            emails: [],
            customer_id: '',
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
            company_id: '',
            status_id: null,
            tasks: [],
            errors: [],
            total: 0,
            discount_total: 0,
            tax_total: 0,
            sub_total: 0,
            data: [],
            date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            partial: 0,
            partial_due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            has_partial: false,
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
            po_number: '',
            design_id: '',
            success: false,
            showSuccessMessage: false,
            showErrorMessage: false,
            loading: false
        }

        this.approved = 4
        this.reversed = consts.invoice_status_reversed
        this.cancelled = consts.invoice_status_cancelled
        this.paid = consts.invoice_status_paid
        this.sent = consts.invoice_status_sent
        this.partial = consts.invoice_status_partial

        if (data !== null) {
            this._fields = { ...this.fields, ...data }
        }
    }

    get fields () {
        return this._fields
    }

    buildInvitations (contact, add = false) {
        const invitations = this.fields.invitations

        // check if the check box is checked or unchecked
        if (add) {
            // add the numerical value of the checkbox to options array
            invitations.push({ client_contact_id: contact })
        } else {
            // or remove the value from the unchecked checkbox from the array
            const index = invitations.findIndex(contact => contact.client_contact_id === contact)
            invitations.splice(index, 1)
        }

        return invitations
    }

    get isApproved () {
        return parseInt(this.fields.status_id) === this.approved
    }

    get isReversed () {
        return parseInt(this.fields.status_id) === this.reversed
    }

    get isCancelled () {
        return parseInt(this.fields.status_id) === this.cancelled
    }

    get isPaid () {
        return parseInt(this.fields.status_id) === this.paid
    }

    get isSent () {
        return parseInt(this.fields.status_id) === this.sent
    }

    get isPartial () {
        return parseInt(this.fields.status_id) === this.partial
    }

    get isDeleted () {
        return this.fields.deleted_at && this.fields.deleted_at.length > 0
    }

    get isEditable () {
        return !this.isReversed && !this.isCancelled && !this.isDeleted
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

    get invoices () {
        return this.fields.invoices
    }

    buildDropdownMenu () {
        const actions = []

        if (this.fields.invitations.length) {
            actions.push('pdf')
        }

        if (this.fields.customer_id !== '') {
            actions.push('email')
        }

        // if (!this.isPaid) {
        //     actions.push('newPayment')
        // }
        //
        // if (!this.isSent && this.isEditable) {
        //     actions.push('markSent')
        // }
        //
        // if (this.isCancelled || this.isReversed) {
        //     actions.push('reverse_status')
        // }
        //
        // if ((this.isSent || this.isPartial) && !this.isPaid && this.isEditable) {
        //     actions.push('markPaid')
        // }

        if (!this.fields.is_deleted) {
            actions.push('delete')
        }

        if (!this.fields.deleted_at) {
            actions.push('archive')
        }

        actions.push('cloneRecurringToInvoice')

        // if (!this.fields.deleted_at && this.isSent && !this.isCancelled) {
        //     actions.push('cancel')
        // }

        // if (!this.fields.deleted_at && (this.isSent || this.isPaid) && !this.isReversed) {
        //     actions.push('reverse')
        // }

        // if (this.fields.task_id && this.fields.task_id !== '' && this.isEditable) {
        //     actions.push('getProducts')
        // }
        //
        // if (this.isEditable) {
        //     actions.push('cloneToInvoice')
        // }
        //
        // if (this.isModuleEnabled('quotes') && this.isEditable) {
        //     actions.push('cloneInvoiceToQuote')
        // }
        //
        // if (this.isModuleEnabled('credits') && this.isEditable) {
        //     actions.push('cloneToCredit')
        // }

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

    get contacts () {
        const index = this.customers.findIndex(customer => customer.id === this.fields.customer_id)
        const customer = this.customers[index]
        return customer.contacts ? customer.contacts : []
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

    get url () {
        return this._url
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
}
