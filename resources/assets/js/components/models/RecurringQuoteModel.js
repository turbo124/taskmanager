import axios from 'axios'
import moment from 'moment'
import BaseModel, { LineItem } from './BaseModel'
import { consts } from '../common/_consts'

export const quote_pdf_fields = ['$quote.quote_number', '$quote.po_number', '$quote.quote_date', '$quote.valid_until', '$quote.balance_due',
    '$quote.quote_total', '$quote.partial_due', '$quote.quote1', '$quote.quote2', '$quote.quote3', '$quote.quote4', '$quote.surcharge1',
    '$quote.surcharge2', '$invoice.surcharge3', '$invoice.surcharge4'
]

export default class RecurringQuoteModel extends BaseModel {
    constructor (data = null, customers = null) {
        super()
        this.customers = customers
        this._url = '/api/recurring-quote'
        this.entity = 'RecurringQuote'
        this.errors = []
        this.error_message = ''

        this._file_count = 0

        if (data !== null && data.files) {
            this.fileCount = data.files
        }

        this._fields = {
            modal: false,
            errors: [],
            is_recurring: false,
            invoice_id: null,
            customer_id: null,
            public_notes: '',
            private_notes: '',
            start_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            end_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            frequency: 1,
            custom_value1: '',
            custom_value2: '',
            custom_value3: '',
            custom_value4: ''
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

    get fileCount () {
        return this._file_count || 0
    }

    set fileCount (files) {
        this._file_count = files ? files.length : 0
    }

    buildDropdownMenu () {
        const actions = []

        // if (this.fields.invitations.length) {
        //     actions.push('pdf')
        // }

        if (this.fields.customer_id !== '') {
            actions.push('email')
        }

        // if (!this.isSent) {
        //     actions.push('markSent')
        // }

        if (!this.fields.is_deleted) {
            actions.push('delete')
        }

        if (!this.fields.deleted_at) {
            actions.push('archive')
        }

        actions.push('cloneRecurringToQuote')

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
