import axios from 'axios'
import moment from 'moment'
import BaseModel, { LineItem } from './BaseModel'
import { consts } from '../common/_consts'

export const quote_pdf_fields = ['$quote.quote_number', '$quote.po_number', '$quote.quote_date', '$quote.valid_until', '$quote.balance_due',
    '$quote.quote_total', '$quote.partial_due', '$quote.quote1', '$quote.quote2', '$quote.quote3', '$quote.quote4', '$quote.surcharge1',
    '$quote.surcharge2', '$invoice.surcharge3', '$invoice.surcharge4'
]

export default class PurchaseOrderModel extends BaseModel {
    constructor (data = null, companies = []) {
        super()
        this.companies = companies
        this._url = '/api/purchase_order'
        this.entity = 'PurchaseOrder'
        this.errors = []
        this.error_message = ''

        this._file_count = 0

        if (data !== null && data.files) {
            this.fileCount = data.files
        }

        this._fields = {
            is_mobile: window.innerWidth <= 768,
            modalOpen: false,
            is_amount_discount: false,
            invitations: [],
            customer_id: '',
            invoice_id: '',
            assigned_to: '',
            number: '',
            user_id: null,
            contacts: [],
            due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            quantity: '',
            id: null,
            account_id: JSON.parse(localStorage.getItem('appState')).user.account_id,
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
            gateway_fee: 0,
            gateway_percentage: false,
            tax: 0,
            discount: 0,
            total_custom_values: 0,
            total_custom_tax: 0,
            recurring: '',
            activeTab: '1',
            po_number: '',
            design_id: '',
            currency_id: null,
            exchange_rate: 1,
            success: false,
            showSuccessMessage: false,
            showErrorMessage: false,
            loading: false
        }

        this.sent = consts.quote_status_sent
        this.approved = consts.quote_status_approved

        this.company = null

        if (data !== null) {
            this._fields = { ...this.fields, ...data }

            if (this.companies.length && this._fields.company_id) {
                const company = this.companies.filter(company => company.id === parseInt(this._fields.company_id))
                this.company = company[0]
            }
        }

        if (this.company && this.company.currency_id.toString().length) {
            const currency = JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === this.company.currency_id)
            this.exchange_rate = currency[0].exchange_rate
        }
    }

    set exchange_rate (exchange_rate) {
        this.fields.exchange_rate = exchange_rate
    }

    get exchange_rate () {
        return this.fields.exchange_rate
    }

    get company () {
        return this._company
    }

    set company (company) {
        this._company = company
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

    get invitations () {
        return this.fields.invitations
    }

    get invitation_link () {
        return `http://${this.account.account.subdomain}portal/purchaseOrders/$key`
    }

    get company_id () {
        return this.fields.company_id
    }

    get hasInvoice () {
        return this.fields.invoice_id.toString().length
    }

    buildDropdownMenu () {
        const actions = []

        if (this.fields.invitations.length) {
            actions.push('pdf')
        }

        if (this.fields.company_id !== '') {
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
            invitations.push({ contact_id: contact })
        } else {
            // or remove the value from the unchecked checkbox from the array
            const index = invitations.findIndex(contact => contact.contact_id === contact)
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
        const index = this.companies.findIndex(company => company.id === this.fields.company_id)
        const company = this.companies[index]
        return company.contacts ? company.contacts : []
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

    companyChange (company_id) {
        const index = this.companies.findIndex(company => company.id === parseInt(company_id))

        const company = this.companies[index]
        // const address = customer.billing ? {
        //     line1: customer.billing.address_1,
        //     town: customer.billing.address_2,
        //     county: customer.billing.city,
        //     country: 'United Kingdom'
        // } : null

        const contacts = company && company.contacts ? company.contacts : []

        return {
            company: company,
            companyName: company.name,
            contacts: contacts
            // address: address

        }
    }
}
