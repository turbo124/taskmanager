import axios from 'axios'
import moment from 'moment'
import BaseModel, { LineItem } from './BaseModel'
import { consts } from '../common/_consts'

export default class OrderModel extends BaseModel {
    constructor (data = null, customers = null) {
        super()
        this._url = '/api/order'
        this.customers = customers
        this.entity = 'Order'

        this._file_count = 0

        if (data !== null && data.files) {
            this.fileCount = data.files
        }

        this._fields = {
            is_mobile: window.innerWidth <= 500,
            modalOpen: false,
            deleted_at: null,
            is_amount_discount: false,
            id: null,
            showSuccessMessage: false,
            showErrorMessage: false,
            invitations: [],
            contacts: [],
            address: {},
            customer_id: '',
            invoice_id: null,
            total: 0,
            design_id: '',
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
            loading: false,
            dropdownOpen: false,
            changesMade: false,
            message: '',
            success: false
        }

        this.sent = consts.order_status_sent
        this.approved = consts.order_status_approved
        this.completed = consts.order_status_complete
        this.held = consts.order_status_held
        this.backorder = consts.order_status_backorder
        this.cancelled = consts.order_status_cancelled

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

    get isSent () {
        return parseInt(this.fields.status_id) === this.sent
    }

    get isApproved () {
        return parseInt(this.fields.status_id) === this.approved
    }

    get isCompleted () {
        return parseInt(this.fields.status_id) === this.completed
    }

    get isCancelled () {
        return parseInt(this.fields.status_id) === this.cancelled
    }

    get isBackorder () {
        return parseInt(this.fields.status_id) === this.backorder
    }

    get isHeld () {
        return parseInt(this.fields.status_id) === this.held
    }

    hasInvoice () {
        return this.fields.invoice_id && this.fields.invoice_id.length
    }

    get fileCount () {
        return this._file_count || 0
    }

    set fileCount (files) {
        this._file_count = files ? files.length : 0
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

    isLate () {
        const dueDate = moment(this._fields.due_date).format('YYYY-MM-DD HH::MM:SS')
        const pending_statuses = [consts.order_status_draft, consts.order_status_backorder, consts.order_status_held, consts.order_status_partial]

        return moment().isAfter(dueDate) && pending_statuses.includes(this._fields.status_id)
    }

    get isDeleted () {
        return this.fields.deleted_at && this.fields.deleted_at.length > 0
    }

    get isEditable () {
        return !this.isCancelled && !this.isHeld && !this.isDeleted
    }

    buildDropdownMenu () {
        const actions = []

        if (this.fields.invitations.length) {
            actions.push('pdf')
        }

        if (this.fields.customer_id !== '') {
            actions.push('email')
        }

        if (!this.isSent && this.isEditable) {
            actions.push('markSent')
        }

        if (!this.isCancelled) {
            actions.push('cancel')
        }

        if (!this.isApproved && !this.isCompleted && this.isEditable) {
            actions.push('dispatch')
        }

        if (this.isBackorder && this.isEditable) {
            actions.push('fulfill')
        }

        if (!this.fields.is_deleted) {
            actions.push('delete')
        }

        if (!this.fields.deleted_at) {
            actions.push('archive')
        }

        if (this.isModuleEnabled('invoices') && this.isEditable) {
            actions.push('cloneOrderToInvoice')
        }

        if (this.isModuleEnabled('invoices') && !this.isApproved && this.isEditable) {
            actions.push('convert')
        }

        if (this.isModuleEnabled('quotes') && this.isEditable) {
            actions.push('cloneOrderToQuote')
        }

        if (!this.hasInvoice() && !this.isCompleted && this.isEditable) {
            actions.push('holdOrder')
        }

        if (this.isHeld) {
            actions.push('reverse_status')
        }

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

    get contacts () {
        const index = this.customers.findIndex(customer => customer.id === this.fields.customer_id)
        const customer = this.customers[index]
        return customer.contacts ? customer.contacts : []
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

    set task_id (task_id) {
        this._fields.task_id = task_id
    }

    set customer_id (customer_id) {
        this._fields.customer_id = customer_id
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
            customerName: customer.name,
            contacts: contacts,
            address: address

        }
    }
}
