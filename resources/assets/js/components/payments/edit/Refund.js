import React from 'react'
import {
    Card,
    CardBody,
    DropdownItem,
    FormGroup,
    Input,
    InputGroup,
    InputGroupAddon,
    InputGroupText,
    Label,
    Modal,
    ModalBody
} from 'reactstrap'
import axios from 'axios'
import InvoiceLine from './InvoiceLine'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import DefaultModalHeader from '../../common/ModalHeader'
import DefaultModalFooter from '../../common/ModalFooter'

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
            payable_credits: [],
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
        this.setCredits = this.setCredits.bind(this)
        this.setAmount = this.setAmount.bind(this)
        this.handleCheck = this.handleCheck.bind(this)
        this.getForm = this.getForm.bind(this)
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

    setCredits (payableCredits) {
        this.setState({ payable_credits: payableCredits }, () => console.log('payable credits', payableCredits))
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

        console.log('credits', this.state.payable_credits)

        const credits = this.state.payable_credits.filter(function (el) {
            return el.amount !== 0 && el.credit_id !== null
        })

        if (invoices.length === 0 && parseFloat(this.state.amount) <= 0) {
            alert('You must enter a valid refund amount')
            return false
        }

        axios.put(`/api/refund/${this.state.id}`, {
            amount: this.state.amount,
            credits: credits,
            invoices: invoices,
            send_email: this.state.send_email,
            date: this.state.date,
            id: this.props.payment.id
        })
            .then((response) => {
                this.initialState = this.state

                if (this.props.payments && this.props.action) {
                    const index = this.props.payments.findIndex(payment => payment.id === this.props.payment.id)
                    this.props.payments[index] = response.data
                    this.props.action(this.props.payments)
                    this.toggle()
                }
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

    getForm () {
        return <React.Fragment>
            <Card>
                <CardBody>
                    <InvoiceLine payment={this.props.payment} paymentables={this.props.paymentables}
                        refund={true}
                        hideEmpty={false}
                        credits={this.props.credits}
                        invoices={this.props.invoices}
                        status={null}
                        handleAmountChange={this.setAmount} errors={this.state.errors}
                        allInvoices={this.props.allInvoices}
                        allCredits={this.props.allCredits} onCreditChange={this.setCredits}
                        customerChange={this.handleCustomerChange} onChange={this.setInvoices}/>

                    {(!this.props.invoices || this.props.invoices.length === 0) &&
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

                    <Label>{translations.date}</Label>
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
        </React.Fragment>
    }

    render () {
        const { message } = this.state
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        return this.props.modal === true ? (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.refund}`}/>{translations.refund}
                </DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.refund}/>

                    <ModalBody className={theme}>

                        {message && <div className="alert alert-danger" role="alert">
                            {message}
                        </div>}

                        {this.getForm()}
                    </ModalBody>

                    <DefaultModalFooter show_success={true} toggle={this.toggle}
                        saveData={this.handleClick.bind(this)}
                        loading={false}/>
                </Modal>
            </React.Fragment>
        ) : this.getForm()
    }
}

export default Refund
