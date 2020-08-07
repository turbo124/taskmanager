import React from 'react'
import { Modal, ModalBody } from 'reactstrap'
import InvoiceLine from './InvoiceLine'
import AddButtons from '../common/AddButtons'
import CustomFieldsForm from '../common/CustomFieldsForm'
import Notes from '../common/Notes'
import Details from './Details'
import PaymentModel from '../models/PaymentModel'
import { translations } from '../common/_translations'
import Documents from './Documents'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'

class AddPayment extends React.Component {
    constructor (props) {
        super(props)

        this.paymentModel = new PaymentModel(this.props.invoices)
        this.initialState = this.paymentModel.fields
        this.state = this.initialState

        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.handleCustomerChange = this.handleCustomerChange.bind(this)
        this.setInvoices = this.setInvoices.bind(this)
        this.setCredits = this.setCredits.bind(this)
        this.handleCheck = this.handleCheck.bind(this)
        this.setAmount = this.setAmount.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'paymentForm')) {
            const storedValues = JSON.parse(localStorage.getItem('paymentForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value
        this.setState({
            [e.target.name]: value
        })
    }

    handleCheck () {
        this.setState({ send_email: !this.state.checked }, () => localStorage.setItem('paymentForm', JSON.stringify(this.state)))
    }

    setAmount (amount) {
        this.setState({ amount: amount }, () => localStorage.setItem('paymentForm', JSON.stringify(this.state)))
    }

    setInvoices (payableInvoices) {
        this.setState({ payable_invoices: payableInvoices }, () => localStorage.setItem('paymentForm', JSON.stringify(this.state)))
    }

    setCredits (payableCredits) {
        this.setState({ payable_credits: payableCredits }, () => localStorage.setItem('paymentForm', JSON.stringify(this.state)))
    }

    handleCustomerChange (customerId) {
        this.setState({ customer_id: customerId }, () => localStorage.setItem('paymentForm', JSON.stringify(this.state)))
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
        this.setState({ loading: true })
        const data = {
            date: this.state.date,
            type_id: this.state.type_id,
            invoices: this.state.payable_invoices,
            credits: this.state.payable_credits,
            customer_id: this.state.customer_id,
            amount: this.state.amount,
            send_email: this.state.send_email,
            transaction_reference: this.state.transaction_reference,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4,
            private_notes: this.state.private_notes
        }

        this.paymentModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.paymentModel.errors, message: this.paymentModel.error_message })
                return
            }

            this.props.payments.push(response)
            this.props.action(this.props.payments)
            localStorage.removeItem('paymentForm')
            this.setState(this.initialState)
        })
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState(this.initialState, () => localStorage.removeItem('paymentForm'))
            }
        })
    }

    render () {
        const { message, loading } = this.state

        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.add_payment}/>

                    <ModalBody>

                        {message && <div className="alert alert-danger" role="alert">
                            {message}
                        </div>}

                        <Details hide_customer={false} payment={this.state} errors={this.state.errors}
                            handleInput={this.handleInput}
                            handleCustomerChange={this.handleCustomerChange} handleCheck={this.handleCheck}/>

                        <InvoiceLine payment={this.state} status={2} handleAmountChange={this.setAmount}
                            errors={this.state.errors}
                            invoices={this.props.invoices} credits={this.props.credits}
                            payment={this.state}
                            customerChange={this.handleCustomerChange}
                            onChange={this.setInvoices}
                            onCreditChange={this.setCredits}/>

                        <Notes private_notes={this.state.private_notes} handleInput={this.handleInput}/>

                        <Documents payment={this.state}/>

                        <CustomFieldsForm handleInput={this.handleInput} custom_value1={this.state.custom_value1}
                            custom_value2={this.state.custom_value2}
                            custom_value3={this.state.custom_value3}
                            custom_value4={this.state.custom_value4}
                            custom_fields={this.props.custom_fields}/>

                    </ModalBody>

                    <DefaultModalFooter show_success={true} toggle={this.toggle}
                        saveData={this.handleClick.bind(this)}
                        loading={loading}/>
                </Modal>
            </React.Fragment>
        )
    }
}

export default AddPayment
