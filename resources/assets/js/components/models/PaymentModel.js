import axios from 'axios'
import moment from 'moment'
import BaseModel from './BaseModel'

export default class PaymentModel extends BaseModel {
    constructor (invoices, data = null, credits = null) {
        super()
        this.invoices = invoices
        this.credits = credits
        this.errors = []
        this.error_message = ''

        this._url = '/api/payments'
        this.entity = 'Payment'

        this._fields = {
            modal: false,
            deleted_at: null,
            is_deleted: false,
            customer_id: '',
            invoice_id: null,
            transaction_reference: '',
            date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            amount: 0,
            type_id: '',
            loading: false,
            custom_value1: '',
            custom_value2: '',
            custom_value3: '',
            custom_value4: '',
            private_notes: '',
            errors: [],
            send_email: true,
            selectedInvoices: [],
            payable_invoices: [],
            message: ''
        }

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

    get isDeleted () {
        return this.fields.deleted_at && this.fields.deleted_at.toString().length > 0
    }

    get isArchived () {
        return this.fields.deleted_at && this.fields.deleted_at.toString().length > 0 && this.fields.is_deleted === false
    }

    get isActive () {
        return !this.fields.deleted_at && this.fields.is_deleted === false
    }

    buildDropdownMenu () {
        const actions = []

        if (this.fields.customer_id !== '') {
            actions.push('email')
        }

        if (!this.fields.is_deleted) {
            actions.push('delete')
        }

        if (!this.fields.is_deleted) {
            actions.push('refund')
        }

        if (!this.fields.deleted_at) {
            actions.push('archive')
        }

        return actions
    }

    getInvoice (invoice_id) {
        const invoice = this.invoices.filter(function (invoice) {
            return invoice.id === parseInt(invoice_id)
        })

        if (!invoice.length || !invoice[0]) {
            return false
        }

        return invoice[0]
    }

    getCredit (credit_id) {
        const credit = this.credits.filter(function (credit) {
            return credit.id === parseInt(credit_id)
        })

        if (!credit.length || !credit[0]) {
            return false
        }

        return credit[0]
    }

    getInvoicesByStatus (status) {
        return status ? this.invoices.filter(invoice => invoice.status_id === status) : this.invoices
    }

    getCreditsByStatus (status) {
        return status ? this.credits.filter(credit => credit.status_id === status) : this.credits
    }

    filterInvoicesByCustomer (customer_id) {
        if (customer_id === '') {
            return this.invoices
        }
        return this.invoices.filter(function (invoice) {
            return invoice.customer_id === parseInt(customer_id)
        })
    }

    filterCreditsByCustomer (customer_id) {
        if (customer_id === '') {
            return this.credits
        }
        return this.credits.filter(function (credit) {
            return credit.customer_id === parseInt(customer_id)
        })
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

    calculateRefundedAmount (paymentables) {
        let refunded = 0

        paymentables.map((paymentable, idx) => {
            if (paymentable.refunded > 0) {
                refunded += parseFloat(paymentable.refunded)
            }
        })

        return refunded
    }
}
