import axios from 'axios'
import moment from 'moment'
import BaseModel from './BaseModel'
import { consts } from '../utils/_consts'

export default class ExpenseModel extends BaseModel {
    constructor (data = null, customers = null) {
        super()
        this.customers = customers
        this.errors = []
        this.error_message = ''
        this.currencies = JSON.parse(localStorage.getItem('currencies'))
        this._url = '/api/expense'
        this.entity = 'Expense'

        this._file_count = 0

        if (data !== null && data.files) {
            this.fileCount = data.files
        }

        this._fields = {
            modal: false,
            number: '',
            amount: 0,
            assigned_to: '',
            custom_value1: '',
            custom_value2: '',
            custom_value3: '',
            custom_value4: '',
            public_notes: '',
            private_notes: '',
            project_id: null,
            customer_id: '',
            currency_id: this.settings.currency_id.toString().length ? this.settings.currency_id : consts.default_currency,
            invoice_currency_id: null,
            payment_type_id: '',
            exchange_rate: 1,
            transaction_reference: '',
            payment_date: this.settings.create_expense_payment ? moment(new Date()).add(1, 'days').format('YYYY-MM-DD') : '',
            include_documents: this.settings.include_expense_documents || false,
            create_invoice: this.settings.create_expense_invoice || false,
            date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            company_id: '',
            category_id: '',
            user_id: null,
            notes: '',
            loading: false,
            errors: [],
            message: '',
            activeTab: '1',
            currencyOpen: this.settings.convert_expense_currency || false,
            paymentOpen: this.settings.create_expense_payment || false,
            changesMade: false,
            dropdownOpen: false,
            showSuccessMessage: false,
            showErrorMessage: false,
            is_recurring: false,
            recurring_start_date: '',
            recurring_end_date: '',
            recurring_due_date: '',
            last_sent_date: '',
            next_send_date: '',
            recurring_frequency: 0
        }

        if (data !== null) {
            this._fields = { ...this.fields, ...data }
            console.log('customer', this.customer)
            this.fields.currency_id = this.customer.length && this.customer.currency_id ? this.customer.currency_id : this.settings.currency_id
        }
    }

    get fields () {
        return this._fields
    }

    get url () {
        return this._url
    }

    get convertedAmount () {
        return parseFloat((this.fields.amount * this.fields.exchange_rate).toFixed(2))
    }

    get id () {
        return this.fields.id
    }

    set customer_id (customer_id) {
        this.fields.customer_id = customer_id
    }

    get convertedAmountWithTax () {
        return (this.fields.amountWithTax * this.fields.exchange_rate).toFixed(2)
    }

    get currencyId () {
        if (!this.fields.currency_id) {
            return null
        }

        return parseInt(this.fields.currency_id)
    }

    get fileCount () {
        return this._file_count || 0
    }

    set fileCount (files) {
        this._file_count = files ? files.length : 0
    }

    get company () {
        if (!this.fields.company_id.toString().length) {
            return null
        }

        // TODO
        return null
    }

    get customer () {
        return this.customers &&
        this.customers.length &&
        this.fields.customer_id &&
        this.fields.customer_id.toString().length
            ? this.customers.filter(customer => customer.id === parseInt(this.fields.customer_id))[0]
            : []
    }

    getExchangeRateForCurrency (currency_id) {
        const currency = this.currencies && this.currencies.length ? this.currencies.filter(currency => currency.id === parseInt(currency_id)) : []
        return currency.length && currency[0].exchange_rate && currency[0].exchange_rate > 0 ? currency[0].exchange_rate : 1
    }

    buildDropdownMenu () {
        const actions = []
        if (!this.fields.is_deleted) {
            actions.push('newInvoice')
        }

        actions.push('cloneExpense')

        return actions
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
}
