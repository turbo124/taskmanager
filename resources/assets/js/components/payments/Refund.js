import React from 'react'
import {
    Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, Label, InputGroup,
    InputGroupAddon, InputGroupText, DropdownItem, FormGroup, Card, CardBody
} from 'reactstrap'
import axios from 'axios'
import InvoiceLine from './InvoiceLine'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'

class Refund extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            loading: false,
            send_email: false,
            errors: [],
            amount: this.props.payment.amount,
            date: this.props.payment.date,
            invoices: this.props.payment.invoices,
            payable_invoices: [],
            selectedInvoices: [],
            id: this.props.payment.id,
            message: ''
        }

        this.initialState = this.state
        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.handleCustomerChange = this.handleCustomerChange.bind(this)
        this.setInvoices = this.setInvoices.bind(this)
        this.setAmount = this.setAmount.bind(this)
        this.handleCheck = this.handleCheck.bind(this)
    }

    handleInput (e) {
        this.setState({ [e.target.name]: e.target.value })
    }

    handleCheck () {
        this.setState({ send_email: !this.state.checked })
    }

    setAmount (amount) {
        this.setState({ amount: amount })
    }

    setInvoices (payableInvoices) {
        this.setState({ payable_invoices: payableInvoices }, () => console.log('payable invoices', payableInvoices))
    }

    handleCustomerChange (customerId) {
        this.setState({ customer_id: customerId }, () => console.log('customer', this.state.customer_id))
    }

    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    handleClick () {
        const invoices = this.state.payable_invoices.filter(function (el) {
            return el.amount !== 0 && el.invoice_id !== null
        })

        if (invoices.length === 0 && parseFloat(this.state.amount) <= 0) {
            alert('You must enter a valid refund amount')
            return false
        }

        axios.put(`/api/refund/${this.state.id}`, {
            amount: this.state.amount,
            invoices: invoices,
            send_email: this.state.send_email,
            date: this.state.date,
            id: this.props.payment.id
        })
            .then((response) => {
                this.initialState = this.state
                console.log('test', response.data)
                const index = this.props.payments.findIndex(payment => payment.id === this.props.payment.id)
                this.props.payments[index] = response.data
                this.props.action(this.props.payments)
                this.toggle()
            })
            .catch((error) => {
                if (error.response.data.message) {
                    this.setState({ message: error.response.data.message })
                }

                if (error.response.data.errors) {
                    this.setState({
                        errors: error.response.data.errors
                    })
                } else {
                    this.setState({ message: error.response.data })
                }
            })
    }

    toggle () {
        if (this.state.modal) {
            this.setState({ ...this.initialState })
        }

        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    render () {
        const { message } = this.state

        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.refund}`}/>{translations.refund}
                </DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader autoFocus={false} toggle={this.toggle}>
                        {translations.refund}
                    </ModalHeader>
                    <ModalBody>

                        {message && <div className="alert alert-danger" role="alert">
                            {message}
                        </div>}

                        <Card>
                            <CardBody>
                                {this.props.invoices.length > 0 &&
                                <InvoiceLine paymentables={this.props.paymentables} invoices={this.props.invoices}
                                    status={null}
                                    handleAmountChange={this.setAmount} errors={this.state.errors}
                                    allInvoices={this.props.allInvoices}
                                    customerChange={this.handleCustomerChange} onChange={this.setInvoices}/>
                                }

                                {this.props.invoices.length === 0 &&
                                <React.Fragment>
                                    <Label>{translations.amount}</Label>
                                    <InputGroup className="mb-3">
                                        <InputGroupAddon addonType="prepend">
                                            <InputGroupText><i className="fa fa-user-o"/></InputGroupText>
                                        </InputGroupAddon>
                                        <Input value={this.state.amount}
                                            className={this.hasErrorFor('amount') ? 'is-invalid' : ''} type="text"
                                            name="amount"
                                            onChange={this.handleInput.bind(this)}/>
                                        {this.renderErrorFor('amount')}
                                    </InputGroup>
                                </React.Fragment>

                                }

                                <Label>Date</Label>
                                <InputGroup className="mb-3">
                                    <InputGroupAddon addonType="prepend">
                                        <InputGroupText><i className="fa fa-user-o"/></InputGroupText>
                                    </InputGroupAddon>
                                    <Input value={this.state.date}
                                        className={this.hasErrorFor('date') ? 'is-invalid' : ''} type="date"
                                        name="date"
                                        onChange={this.handleInput.bind(this)}/>
                                    {this.renderErrorFor('date')}
                                </InputGroup>
                            </CardBody>
                        </Card>

                        <Card>
                            <CardBody>
                                <FormGroup check>
                                    <Label check>
                                        <Input value={this.state.send_email} onChange={this.handleCheck}
                                            type="checkbox"/>
                                        {translations.send_email}
                                    </Label>
                                </FormGroup>
                            </CardBody>
                        </Card>
                    </ModalBody>

                    <ModalFooter>
                        <Button color="primary" onClick={this.handleClick.bind(this)}>{translations.refund}</Button>
                        <Button color="secondary" onClick={this.toggle}>{translations.cancel}</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}

export default Refund
