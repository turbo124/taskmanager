import React, { Component } from 'react'
import axios from 'axios'
import { FormGroup, Input } from 'reactstrap'
import { translations } from '../../utils/_translations'

export default class InvoiceDropdown extends Component {
    constructor (props) {
        super(props)
        this.state = {
            invoices: []
        }

        this.getInvoices = this.getInvoices.bind(this)
        this.count = 0
    }

    componentDidMount () {
        if (!this.props.invoices || !this.props.invoices.length) {
            this.getInvoices()
        } else {
            this.state.invoices = this.props.invoices
        }
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback d-block'>
                    <strong>{this.props.errors[field][0]}</strong>
                </span>
            )
        }
    }

    hasErrorFor (field) {
        return this.props.errors && !!this.props.errors[field]
    }

    getInvoices () {
        const url = this.props.status && this.props.status !== null ? `api/invoice/getInvoicesByStatus/${this.props.status}` : '/api/invoice'

        axios.get(url)
            .then((r) => {
                this.setState({
                    invoices: r.data
                }, () => console.log('invoices', this.state.invoices))
            })
            .catch((e) => {
                console.error(e)
            })
    }

    render () {
        let invoiceList = null
        let { invoices } = this.state

        if (!invoices) {
            invoiceList = <option value="">Loading...</option>
        } else {
            if (this.props.customer_id) {
                invoices = invoices.filter(invoice => invoice.customer_id === parseInt(this.props.customer_id))
            }

            if (this.props.allowed_invoices && this.props.allowed_invoices.length) {
                invoices = invoices.filter(invoice => this.props.allowed_invoices.includes(invoice.id))
            }

            this.count = invoices.length

            invoiceList = invoices.map((invoice, index) => (
                <option key={index} value={invoice.id}>{invoice.number} ({invoice.balance})</option>
            ))
        }

        const name = this.props.name && this.props.name ? this.props.name : 'invoice_id'
        const error_name = this.props.error_name ? this.props.error_name : name
        const data_id = this.props.data_id ? this.props.data_id : 0

        const selected = this.props.invoices && this.props.invoices.length === 1 ? this.props.invoices[0].id : this.props.invoice_id

        const selectList = this.props.multiple && this.props.multiple === true ? (
            <Input onChange={this.props.handleInputChanges} multiple type="select"
                data-id={data_id}
                name={name} id={name}>
                {invoiceList}
            </Input>
        ) : <Input data-id={data_id} value={selected} onChange={this.props.handleInputChanges}
            type="select"
            name={name} id={name}>
            <option value="">{translations.select_option}</option>
            {invoiceList}
        </Input>

        return this.props.hide_when_empty && !this.count ? null : (
            <FormGroup>
                {selectList}
                {this.renderErrorFor(error_name)}
            </FormGroup>
        )
    }
}
