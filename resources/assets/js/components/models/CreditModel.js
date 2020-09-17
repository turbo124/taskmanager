import axios from 'axios'
import moment from 'moment'
import BaseModel, { LineItem } from './BaseModel'
import { consts } from '../utils/_consts'

export const credit_pdf_fields = ['$credit.credit_number', '$credit.po_number', '$credit.credit_date', '$credit.credit_amount',
    '$credit.balance_due', '$credit.partial_due', '$credit.credit1', '$credit.credit2', '$credit.credit3', '$credit.credit4',
    '$credit.surcharge1', '$credit.surcharge2', '$credit.surcharge3', '$credit.surcharge4'
]

export default class CreditModel extends BaseModel {
    constructor (data = null, customers = []) {
        super()
        this.customers = customers
        this._url = '/api/credit'
        this.entity = 'Credit'
        this._file_count = 0

        if (data !== null && data.files) {
            this.fileCount = data.files
        }

        this._fields = {
            is_mobile: window.innerWidth <= 768,
            modalOpen: false,
            is_amount_discount: false,
            id: null,
            account_id: JSON.parse(localStorage.getItem('appState')).user.account_id,
            showSuccessMessage: false,
            showErrorMessage: false,
            invitations: [],
            contacts: [],
            address: {},
            customerName: '',
            total: 0,
            customer_id: '',
            assigned_to: '',
            number: '',
            design_id: '',
            file_count: 0,
            date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            custom_value1: '',
            public_notes: '',
            private_notes: '',
            footer: '',
            terms: '',
            custom_value2: '',
            custom_value3: '',
            custom_value4: '',
            transaction_fee_tax: false,
            shipping_cost_tax: false,
            transaction_fee: 0,
            shipping_cost: 0,
            gateway_fee: 0,
            gateway_percentage: false,
            tax_rate_name: '',
            tax_rate: 0,
            // company_id: this.props.add === false && this.props.invoice && this.props.invoice.company_id ? this.props.invoice.company_id : '',
            status_id: null,
            tasks: [],
            errors: [],
            discount_total: 0,
            tax: 0,
            discount: 0,
            tax_total: 0,
            sub_total: 0,
            line_items: [],
            partial: 0,
            has_partial: false,
            partial_due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            activeTab: '1',
            po_number: '',
            return_to_stock: false,
            currency_id: null,
            exchange_rate: 1,
            loading: false,
            dropdownOpen: false,
            changesMade: false,
            message: '',
            success: false
        }

        this.sent = consts.credit_status_sent
        this.approved = consts.credit_status_applied

        this.customer = null

        if (data !== null) {
            this._fields = { ...this.fields, ...data }

            if (this.customers.length && this._fields.customer_id) {
                const customer = this.customers.filter(customer => customer.id === parseInt(this._fields.customer_id))
                this.customer = customer[0]
            }
        }

        if (this.customer && this.customer.currency_id.toString().length) {
            const currency = JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === this.customer.currency_id)
            this.exchange_rate = currency[0].exchange_rate
        }
    }

    get exchange_rate () {
        return this.fields.exchange_rate
    }

    set exchange_rate (exchange_rate) {
        this.fields.exchange_rate = exchange_rate
    }

    get customer () {
        return this._customer
    }

    set customer (customer) {
        this._customer = customer
    }

    get fields () {
        return this._fields
    }

    get url () {
        return this._url
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

    get customer_id () {
        return this.fields.customer_id
    }

    get invitation_link () {
        return `http://${this.account.account.subdomain}portal/credits/$key`
    }

    get isSent () {
        return parseInt(this.fields.status_id) === this.sent
    }

    get isApproved () {
        return parseInt(this.fields.status_id) === this.approved
    }

    get contacts () {
        const index = this.customers.findIndex(customer => customer.id === this.fields.customer_id)
        const customer = this.customers[index]
        return customer.contacts ? customer.contacts : []
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

    buildDropdownMenu () {
        const actions = []

        console.log('invitations', this.fields.invitations)

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
            actions.push('cloneToCredit')
        }

        if (this.isModuleEnabled('invoices') && !this.isApproved) {
            actions.push('convert')
        }

        if (this.isModuleEnabled('quotes')) {
            actions.push('cloneCreditToQuote')
        }

        return actions
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
