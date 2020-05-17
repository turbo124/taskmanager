import React, { Component } from 'react'
import moment from 'moment'
import { Button, FormGroup, Input, Label, Modal, ModalBody, ModalHeader, ModalFooter, DropdownItem } from 'reactstrap'
import axios from 'axios'
import FormBuilder from '../accounts/FormBuilder'
import Datepicker from '../common/Datepicker'
import { icons, translations } from '../common/_icons'

class UpdateRecurringQuote extends Component {
    constructor (props, context) {
        super(props, context)
        this.state = {
            errors: [],
            id: this.props.invoice.id,
            is_recurring: false,
            changesMade: false,
            quote_id: this.props.invoice && this.props.invoice.quote_id ? this.props.invoice.quote_id : 0,
            customer_id: this.props.invoice && this.props.invoice.customer_id ? this.props.invoice.customer_id : 0,
            finance_type: this.props.finance_type ? this.props.finance_type : 1,
            start_date: this.props.invoice && this.props.invoice.start_date ? this.props.invoice.start_date : moment(new Date()).format('YYYY-MM-DD'),
            end_date: this.props.invoice && this.props.invoice.end_date ? this.props.invoice.end_date : moment(new Date()).format('YYYY-MM-DD'),
            recurring_due_date: this.props.invoice && this.props.invoice.recurring_due_date ? this.props.invoice.recurring_due_date : moment(new Date()).format('YYYY-MM-DD'),
            frequency: this.props.invoice && this.props.invoice.frequency ? this.props.invoice.frequency : 1,
            custom_value1: this.props.invoice.custom_value1,
            custom_value2: this.props.invoice.custom_value2,
            custom_value3: this.props.invoice.custom_value3,
            custom_value4: this.props.invoice.custom_value4,
            public_notes: this.props.invoice.public_notes,
            private_notes: this.props.invoice.private_notes
        }

        this.initialState = this.state
        this.handleInput = this.handleInput.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.toggle = this.toggle.bind(this)
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
        axios.put(`/api/recurring-quote/${this.state.id}`, {
            start_date: this.state.start_date,
            quote_id: this.state.quote_id,
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
                const index = this.props.invoices.findIndex(invoice => invoice.id === this.props.invoice.id)
                this.props.invoices[index] = response.data
                this.props.action(this.props.invoices)
                this.setState({ changesMade: false })
                this.toggle()
            })
            .catch((error) => {
                alert(error)
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    handleInput (e) {
        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value
        this.setState({
            [e.target.name]: value,
            changesMade: true
        })

        if (this.props.setRecurring) {
            this.props.setRecurring(JSON.stringify(this.state))
        }
    }

    toggle () {
        if (this.state.modal && this.state.changesMade) {
            if (window.confirm('Your changes have not been saved?')) {
                this.setState({ ...this.initialState })
            }

            return
        }

        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    render () {
        const inlineClass = this.props ? 'mb-4' : 'form-inline mb-4'
        const customFields = this.props.custom_fields ? this.props.custom_fields : []
        const customForm = customFields && customFields.length ? <FormBuilder
            handleChange={this.handleInput.bind(this)}
            formFieldsRows={customFields}
        /> : null

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
                        type='select'
                        name='frequency'
                        id='frequency'
                        placeholder={translations.frequency}
                        onChange={this.handleInput}
                    />
                </FormGroup>

                <FormGroup>
                    <Label for="public_notes">{translations.public_notes}:</Label>
                    <Input value={this.state.public_notes} type="text" id="public_notes" name="public_notes"
                        onChange={this.handleInput}/>
                    {this.renderErrorFor('public_notes')}
                </FormGroup>

                <FormGroup>
                    <Label for="private_notes">{translations.private_notes}(*):</Label>
                    <Input value={this.state.private_notes} type="text" id="private_notes" name="private_notes"
                        onChange={this.handleInput}/>
                    {this.renderErrorFor('private_notes')}
                </FormGroup>

                {customForm}
            </div>
        )

        return this.props.modal === true
            ? <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_recurring_quote}</DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_recurring_quote}
                    </ModalHeader>

                    <ModalBody>
                        {form}
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

export default UpdateRecurringQuote
