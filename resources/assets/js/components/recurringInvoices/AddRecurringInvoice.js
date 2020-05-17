import React, { Component } from 'react'
import moment from 'moment'
import { Button, FormGroup, Input, Label, Modal, ModalBody, ModalHeader, ModalFooter } from 'reactstrap'
import InvoiceDropdown from '../common/InvoiceDropdown'
import axios from 'axios'
import CustomerDropdown from '../common/CustomerDropdown'
import AddButtons from '../common/AddButtons'
import Notes from '../common/Notes'
import CustomFieldsForm from '../common/CustomFieldsForm'
import Datepicker from '../common/Datepicker'
import { translations } from '../common/_icons'

class AddRecurringInvoice extends Component {
    constructor (props, context) {
        super(props, context)

        this.initialState = {
            errors: [],
            is_recurring: false,
            invoice_id: null,
            customer_id: null,
            public_notes: '',
            private_notes: '',
            start_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            end_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            recurring_due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            frequency: 1,
            custom_value1: '',
            custom_value2: '',
            custom_value3: '',
            custom_value4: ''
        }

        this.state = this.initialState

        this.handleInput = this.handleInput.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.toggle = this.toggle.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'recurringInvoiceForm')) {
            const storedValues = JSON.parse(localStorage.getItem('recurringInvoiceForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
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
        axios.post('/api/recurring-invoice', {
            start_date: this.state.start_date,
            invoice_id: this.state.invoice_id,
            customer_id: this.state.customer_id,
            end_date: this.state.end_date,
            recurring_due_date: this.state.recurring_due_date,
            frequency: this.state.frequency,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4,
            public_notes: this.state.public_notes,
            private_notes: this.state.private_notes
        })
            .then((response) => {
                this.toggle()
                const newUser = response.data
                this.props.invoices.push(newUser)
                this.props.action(this.props.invoices)
                localStorage.removeItem('recurringInvoiceForm')
                this.setState(this.initialState)
            })
            .catch((error) => {
                alert(error)
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    handleInput (e) {
        let customerId = this.state.customer_id

        if (e.target.name === 'invoice_id') {
            const invoice = this.props.allInvoices.filter(function (invoice) {
                return invoice.id === parseInt(e.target.value)
            })

            if (!invoice.length) {
                return
            }

            customerId = invoice[0].customer_id
        }

        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value
        this.setState({
            customer_id: customerId,
            [e.target.name]: value
        }, () => localStorage.setItem('recurringInvoiceForm', JSON.stringify(this.state)))

        if (this.props.setRecurring) {
            this.props.setRecurring(JSON.stringify(this.state))
        }
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState(this.initialState, () => localStorage.removeItem('recurringInvoiceForm'))
            }
        })
    }

    render () {
        const inlineClass = this.props ? 'mb-4' : 'form-inline mb-4'

        const form = (
            <div className={inlineClass}>
                <FormGroup>
                    <Label for="start_date">{translations.start_date}(*):</Label>
                    <Datepicker name="start_date" date={this.state.start_date} handleInput={this.handleInput}
                        className={this.hasErrorFor('start_date') ? 'form-control is-invalid' : 'form-control'}/>
                    {this.renderErrorFor('start_date')}
                </FormGroup>

                <FormGroup>
                    <Label for="end_date">{translations.end_date}(*):</Label>
                    <Datepicker name="end_date" date={this.state.end_date} handleInput={this.handleInput}
                        className={this.hasErrorFor('end_date') ? 'form-control is-invalid' : 'form-control'}/>
                    {this.renderErrorFor('end_date')}
                </FormGroup>

                <FormGroup>
                    <Label for="recurring_due_date">{translations.due_date}(*):</Label>
                    <Datepicker name="recurring_due_date" date={this.state.recurring_due_date} handleInput={this.handleInput}
                        className={this.hasErrorFor('recurring_due_date') ? 'form-control is-invalid' : 'form-control'}/>
                    {this.renderErrorFor('recurring_due_date')}
                </FormGroup>

                <FormGroup>
                    <Label>{translations.frequency}</Label>
                    <Input
                        value={this.state.frequency}
                        type='text'
                        name='frequency'
                        id='frequency'
                        placeholder="Days"
                        onChange={this.handleInput}
                    />
                </FormGroup>

                <CustomFieldsForm handleInput={this.handleInput} custom_fields={this.props.custom_fields}
                    custom_value1={this.state.custom_value1} custom_value2={this.state.custom_value2}
                    custom_value3={this.state.custom_value3} custom_value4={this.state.custom_value4}/>
            </div>
        )

        return this.props.modal === true
            ? <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.add_recurring_invoice}
                    </ModalHeader>

                    <ModalBody>
                        {form}
                        <FormGroup>
                            <Label>{translations.invoice}</Label>
                            <InvoiceDropdown
                                invoices={this.props.allInvoices}
                                handleInputChanges={this.handleInput}
                                name="invoice_id"
                                errors={this.state.errors}
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label>{translations.customer}</Label>
                            <CustomerDropdown
                                disabled={true}
                                handleInputChanges={this.handleInput}
                                customer={this.state.customer_id}
                                customers={this.props.customers}
                                errors={this.state.errors}
                            />
                        </FormGroup>

                        <Notes private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                            handleInput={this.handleInput}/>
                    </ModalBody>
                    <ModalFooter>
                        <Button color="primary" onClick={this.handleClick.bind(this)}>{translations.save}</Button>
                        <Button color="secondary" onClick={this.toggle}>{translations.close}</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
            : form
    }
}

export default AddRecurringInvoice
