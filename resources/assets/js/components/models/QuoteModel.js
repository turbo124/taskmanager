import axios from 'axios'
import moment from 'moment'
import BaseModel, { LineItem } from './BaseModel'
import { consts } from '../common/_consts'

export const quote_pdf_fields = ['$quote.quote_number', '$quote.po_number', '$quote.quote_date', '$quote.valid_until', '$quote.balance_due',
    '$quote.quote_total', '$quote.partial_due', '$quote.quote1', '$quote.quote2', '$quote.quote3', '$quote.quote4', '$quote.surcharge1',
    '$quote.surcharge2', '$invoice.surcharge3', '$invoice.surcharge4'
]

export default class CreditModel extends BaseModel {
    constructor (data = null, customers = null) {
        super()
        this.customers = customers
        this._url = '/api/quote'
        this.entity = 'Quote'
        this.errors = []
        this.error_message = ''

        this._fields = {
            is_mobile: window.innerWidth <= 500,
            modalOpen: false,
            is_amount_discount: false,
            invitations: [],
            customer_id: '',
            user_id: null,
            contacts: [],
            due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            quantity: '',
            id: null,
            lines: [],
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
            line_items: [],
            date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            partial: 0,
            has_partial: false,
            partial_due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
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
            tax: 0,
            discount: 0,
            total_custom_values: 0,
            total_custom_tax: 0,
            recurring: '',
            activeTab: '1',
            po_number: '',
            design_id: '',
            success: false,
            showSuccessMessage: false,
            showErrorMessage: false,
            loading: false
        }

        this.sent = 2
        this.approved = 4

        if (data !== null) {
            this._fields = { ...this.fields, ...data }
        }
    }

    get fields () {
        return this._fields
    }

    get url () {
        return this._url
    }

    get isApproved () {
        return parseInt(this.fields.status_id) === this.approved
    }

    get isSent () {
        return parseInt(this.fields.status_id) === this.sent
    }

    buildDropdownMenu () {
        const actions = []

        if (this.fields.invitations.length) {
            actions.push('pdf')
        }

        if (this.fields.customer_id !== '') {
            actions.push('email')
        }

        if (!this.isSent) {
            actions.push('markSent')
        }

        if (!this.fields.is_deleted) {
            actions.push('delete')
        }

        if (!this.fields.deleted_at) {
            actions.push('archive')
        }

        if (this.fields.task_id && this.fields.task_id !== '') {
            actions.push('getProducts')
        }

        if (!this.isApproved) {
            actions.push('approve')
        }

        actions.push('cloneToQuote')

        if (this.isModuleEnabled('orders')) {
            actions.push('clone_to_order')
        }

        if (this.isModuleEnabled('credits')) {
            actions.push('cloneToCredit')
        }

        if (this.isModuleEnabled('invoices')) {
            actions.push('cloneQuoteToInvoice')
        }

        return actions
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

    addItem () {
        const newArray = this.fields.line_items.slice()
        newArray.push(LineItem)
        this.fields.line_items = newArray
        return newArray
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

    isLate () {
        const dueDate = moment(this._fields.due_date).format('YYYY-MM-DD')
        const pending_statuses = [consts.quote_status_draft, consts.quote_status_sent]

        return moment().isAfter(dueDate) && pending_statuses.includes(this._fields.status_id)
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
        // const address = customer.billing ? {
        //     line1: customer.billing.address_1,
        //     town: customer.billing.address_2,
        //     county: customer.billing.city,
        //     country: 'United Kingdom'
        // } : null

        const contacts = customer && customer.contacts ? customer.contacts : []

        return {
            customerName: customer.name,
            contacts: contacts
            // address: address

        }
    }
}
