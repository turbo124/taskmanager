import React, { Component } from 'react'
import InvoiceLineInputs from './InvoiceLineInputs'
import PaymentModel from '../models/PaymentModel'

export default class InvoiceLine extends Component {
    constructor (props) {
        super(props)

        this.paymentModel = new PaymentModel(this.props.invoices)

        this.state = {
            lines: this.props.lines && this.props.lines.length ? this.props.lines : [{ invoice_id: null, amount: 0 }],
            amount: 0,
            customer_id: null
        }

        this.handleChange = this.handleChange.bind(this)
        this.addLine = this.addLine.bind(this)
        this.removeLine = this.removeLine.bind(this)
    }

    handleChange (e) {
        const name = e.target.name
        const idx = e.target.dataset.id

        const lines = [...this.state.lines]

        let amount = 0
        let manual_update = false

        if (name === 'invoice_id' || (e.target.dataset.invoice && e.target.dataset.invoice.length)) {
            const invoice_id = e.target.dataset.invoice && e.target.dataset.invoice.length ? e.target.dataset.invoice : e.target.value
            const invoice = this.paymentModel.getInvoice(invoice_id)

            if (!invoice) {
                return
            }

            let invoice_total = e.target.dataset.invoice && e.target.dataset.invoice.length && name === 'amount' ? parseFloat(e.target.value) : parseFloat(invoice.total)
            let refunded_amount = 0

            if (this.props.paymentables && this.props.paymentables.length > 0) {
                refunded_amount = this.paymentModel.calculateRefundedAmount(this.props.paymentables)
            }

            if ((refunded_amount + invoice_total) > invoice.total) {
                const amount_remaining = invoice.total - refunded_amount
                invoice_total = amount_remaining
                manual_update = true
            }

            this.props.customerChange(invoice.customer_id)
            lines[idx].amount = parseFloat(invoice_total)
            lines[idx].invoice_id = invoice_id

            if (this.props.allInvoices && this.props.allInvoices.length === 1) {
                amount = invoice_total
            } else {
                amount = this.state.amount + parseFloat(invoice_total)
            }
        }

        if (name === 'credit_id' || (e.target.dataset.credit && e.target.dataset.credit.length)) {
            const credit_id = e.target.dataset.credit && e.target.dataset.credit.length ? e.target.dataset.credit : e.target.value
            const credit = this.paymentModel.getCredit(credit_id)

            if (!credit) {
                return
            }

            let credit_total = e.target.dataset.credit && e.target.dataset.credit.length && name === 'amount' ? parseFloat(e.target.value) : parseFloat(credit.total)
            let refunded_amount = 0

            if (this.props.paymentables && this.props.paymentables.length > 0) {
                refunded_amount = this.paymentModel.calculateRefundedAmount(this.props.paymentables)
            }

            if ((refunded_amount + credit_total) > credit.total) {
                const amount_remaining = credit.total - refunded_amount
                invoice_total = amount_remaining
                manual_update = true
            }

            this.props.customerChange(credit.customer_id)
            lines[idx].amount = parseFloat(credit_total)
            lines[idx].credit_id = credit_id

            if (this.props.allCredits && this.props.allCredits.length === 1) {
                amount = credit_total
            } else {
                amount = this.state.amount + parseFloat(credit_total)
            }
        }

        if (!manual_update) {
            lines[e.target.dataset.id][e.target.name] = e.target.value
        }

        this.setState({ lines }, () => {
            this.props.onChange(this.state.lines)

            if (amount > 0) {
                this.props.handleAmountChange(amount)
            }
        })
    }

    addLine (e) {
        const allowedInvoices = this.paymentModel.getInvoicesByStatus(this.props.status)
        const allowedCredits = this.paymentModel.getCreditsByStatus(2)

        if (this.state.lines.length >= (allowedInvoices.length + allowedCredits.length)) {
            return
        }

        this.setState((prevState) => ({
            lines: [...prevState.lines, { invoice_id: null, amount: 0, credit_id: null }]
        }), () => this.props.onChange(this.state.lines))
    }

    removeLine (idx) {
        if (this.state.lines.length === 1) {
            return
        }

        this.setState({
            lines: this.state.lines.filter(function (line, sidx) {
                return sidx !== idx
            })
        }, () => this.props.onChange(this.state.lines))
    }

    render () {
        const { lines } = this.state
        const status = this.props.status ? this.props.status : null
        const invoices = this.props.allInvoices ? this.props.allInvoices : []
        const credits = this.props.allCredits ? this.props.allCredits : []
        return (
            <form>
                <InvoiceLineInputs invoices={invoices} credits={credits} status={status} errors={this.props.errors}
                    onChange={this.handleChange} lines={lines}
                    removeLine={this.removeLine}
                    addLine={this.addLine}/>
                {/* <Button color="primary" onClick={this.addLine}>Add</Button> */}
            </form>
        )
    }
}
