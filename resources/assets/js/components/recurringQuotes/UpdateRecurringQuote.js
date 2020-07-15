import React, { Component } from 'react'
import { Button, Modal, ModalBody, ModalHeader, ModalFooter, DropdownItem } from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import Details from './Details'
import Notes from '../common/Notes'
import CustomFieldsForm from '../common/CustomFieldsForm'
import RecurringQuoteModel from '../models/RecurringQuoteModel'
import DropdownMenuBuilder from '../common/DropdownMenuBuilder'

class UpdateRecurringQuote extends Component {
    constructor (props, context) {
        super(props, context)
        this.recurringQuoteModel = new RecurringQuoteModel(this.props.invoice)
        this.initialState = this.recurringQuoteModel.fields
        this.state = this.initialState
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

    getFormData () {
        return {
            start_date: this.state.start_date,
            quote_id: this.state.quote_id,
            customer_id: this.state.customer_id,
            end_date: this.state.end_date,
            due_date: this.state.due_date,
            frequency: this.state.frequency,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4,
            public_notes: this.state.public_notes,
            private_notes: this.state.private_notes
        }
    }

    handleClick () {
        const formData = this.getFormData()

        this.recurringQuoteModel.save(formData).then(response => {
            if (!response) {
                this.setState({ errors: this.recurringQuoteModel.errors, message: this.recurringQuoteModel.error_message })
                return
            }

            const index = this.props.invoices.findIndex(invoice => invoice.id === this.props.invoice.id)
            this.props.invoices[index] = response
            this.props.action(this.props.invoices)
            this.setState({
                editMode: false,
                changesMade: false
            })
            this.toggle()
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

        const form = (
            <div className={inlineClass}>
                <Details recurring_quote={this.state} hasErrorFor={this.hasErrorFor}
                    renderErrorFor={this.renderErrorFor} handleInput={this.handleInput}/>

                <Notes private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                    handleInput={this.handleInput}/>

                <CustomFieldsForm handleInput={this.handleInput} custom_fields={this.props.custom_fields}
                    custom_value1={this.state.custom_value1} custom_value2={this.state.custom_value2}
                    custom_value3={this.state.custom_value3} custom_value4={this.state.custom_value4}/>
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
                        <DropdownMenuBuilder invoices={this.props.invoices} formData={this.getFormData()}
                            model={this.recurringQuoteModel}
                            action={this.props.action}/>

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
