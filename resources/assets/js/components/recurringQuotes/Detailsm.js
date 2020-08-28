import React, { Component } from 'react'
import { Card, CardBody, CardHeader, FormGroup, Input, Label } from 'reactstrap'
import CustomerDropdown from '../common/CustomerDropdown'
import FormBuilder from '../accounts/FormBuilder'
import Datepicker from '../common/Datepicker'
import { translations } from '../common/_translations'
import UserDropdown from '../common/UserDropdown'

export default class Detailsm extends Component {
    constructor (props, context) {
        super(props, context)
        this.state = {
            is_recurring: false
        }
        this.handleSlideClick = this.handleSlideClick.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    handleSlideClick (e) {
        this.setState({ is_recurring: e.target.checked })
    }

    hasErrorFor (field) {
        return this.props.errors && !!this.props.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.props.errors[field][0]}</strong>
                </span>
            )
        }
    }

    render () {
        const customFields = this.props.custom_fields ? this.props.custom_fields : []

        if (customFields[0] && Object.keys(customFields[0]).length) {
            customFields[0].forEach((element, index, array) => {
                if (this.props[element.name] && this.props[element.name].length) {
                    customFields[0][index].value = this.props[element.name]
                }
            })
        }

        const customForm = customFields && customFields.length ? <FormBuilder
            handleChange={this.props.handleInput.bind(this)}
            formFieldsRows={customFields}
        /> : null

        return (
            <Card>
                <CardHeader>{translations.details}</CardHeader>
                <CardBody>
                    <FormGroup>
                        <Label for="date">{translations.date}(*):</Label>
                        <Datepicker name="date" date={this.props.quote.date} handleInput={this.props.handleInput}
                            className={this.hasErrorFor('date') ? 'form-control is-invalid' : 'form-control'}/>
                        {this.renderErrorFor('due_date')}
                    </FormGroup>
                    <FormGroup>
                        <Label for="due_date">{translations.expiry_date}(*):</Label>
                        <Datepicker name="due_date" date={this.props.quote.due_date}
                            handleInput={this.props.handleInput}
                            className={this.hasErrorFor('due_date') ? 'form-control is-invalid' : 'form-control'}/>
                        {this.renderErrorFor('due_date')}
                    </FormGroup>
                    <FormGroup>
                        <Label for="po_number">{translations.po_number}(*):</Label>
                        <Input value={this.props.quote.po_number} type="text" id="po_number" name="po_number"
                            onChange={this.props.handleInput}/>
                        {this.renderErrorFor('po_number')}
                    </FormGroup>
                    <FormGroup>
                        <Label>{translations.partial}</Label>
                        <Input
                            value={this.props.quote.partial}
                            type='text'
                            name='partial'
                            id='partial'
                            onChange={this.props.handleInput}
                        />
                    </FormGroup>

                    <FormGroup className={this.props.quote.has_partial === true ? '' : 'd-none'}>
                        <Label>{translations.partial_due_date}</Label>
                        <Datepicker name="partial_due_date" date={this.props.quote.partial_due_date}
                            handleInput={this.props.handleInput}
                            className={this.hasErrorFor('partial_due_date') ? 'form-control is-invalid' : 'form-control'}/>
                    </FormGroup>

                    <FormGroup>
                        <Label>{translations.number}</Label>
                        <Input className={this.hasErrorFor('number') ? 'form-control is-invalid' : 'form-control'}
                            value={this.props.quote.number}
                            type='text'
                            name='number'
                            id='number'
                            onChange={this.props.handleInput}
                        />
                        {this.renderErrorFor('number')}
                    </FormGroup>

                    <FormGroup>
                        <Label for="postcode">{translations.assigned_user}:</Label>
                        <UserDropdown
                            user_id={this.props.quote.assigned_to}
                            name="assigned_to"
                            errors={this.props.errors}
                            handleInputChanges={this.props.handleInput}
                        />
                    </FormGroup>

                    {this.props.hide_customer === true &&
                    <FormGroup>
                        <Label>{translations.customer}</Label>
                        <CustomerDropdown
                            handleInputChanges={this.props.handleInput}
                            customer={this.props.quote.customer_id}
                            customers={this.props.customers}
                            errors={this.props.errors}
                        />
                    </FormGroup>
                    }

                    {/* <FormGroup> */}
                    {/*    <Label>{translations.company}</Label> */}
                    {/*    <CompanyDropdown */}
                    {/*        company_id={this.props.quote.company_id} */}
                    {/*        name="company_id" */}
                    {/*        hasErrorFor={this.hasErrorFor} */}
                    {/*        errors={this.props.errors} */}
                    {/*        handleInputChanges={this.props.handleInput} */}
                    {/*    /> */}
                    {/* </FormGroup> */}

                    {customForm}
                </CardBody>
            </Card>

        )
    }
}
