import React, { Component } from 'react'
import { Card, CardBody, CardHeader, FormGroup, Input, Label } from 'reactstrap'
import CustomerDropdown from '../../common/dropdowns/CustomerDropdown'
import Datepicker from '../../common/Datepicker'
import { translations } from '../../utils/_translations'
import UserDropdown from '../../common/dropdowns/UserDropdown'
import ProjectDropdown from '../../common/dropdowns/ProjectDropdown'
import QuoteDropdown from "../../common/dropdowns/QuoteDropdown";

export default class Recurringm extends Component {
    constructor (props, context) {
        super(props, context)
        this.state = {
            is_recurring: false
        }
       
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
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
        return (
            <Card>
                <CardHeader>{translations.details}</CardHeader>
               
                <CardBody>
                    {!!this.props.show_invoice &&
                <FormGroup>
                    <Label>{translations.invoice}</Label>
                    <QuoteDropdown
                        is_recurring={true}
                        quotes={this.props.allQuotes}
                        handleInputChanges={this.props.handleInput}
                        name="quote_id"
                        errors={this.state.errors}
                    />
                </FormGroup>
                }

                    {props.recurring_quote.last_sent_date.length
                    ? <FormGroup>
                        <Label for="date_to_send">{translations.next_send_date}(*):</Label>
                        <Datepicker name="date_to_send" date={props.recurring_quote.date_to_send}
                            handleInput={props.handleInput}
                            className={props.hasErrorFor('date_to_send') ? 'form-control is-invalid' : 'form-control'}/>
                        {props.renderErrorFor('date_to_send')}
                    </FormGroup>
                    : <FormGroup>
                        <Label for="start_date">{translations.start_date}(*):</Label>
                        <Datepicker name="start_date" date={props.recurring_quote.start_date}
                            handleInput={props.handleInput}
                            className={props.hasErrorFor('start_date') ? 'form-control is-invalid' : 'form-control'}/>
                        {props.renderErrorFor('start_date')}
                    </FormGroup>
                }

                <FormGroup>
                    <Label for="expiry_date">{translations.end_date}(*):</Label>
                    <Datepicker name="expiry_date" date={props.recurring_quote.expiry_date}
                        handleInput={props.handleInput}
                        className={props.hasErrorFor('expiry_date') ? 'form-control is-invalid' : 'form-control'}/>
                    {props.renderErrorFor('expiry_date')}
                </FormGroup>

                <FormGroup>
                    <Label for="due_date">{translations.due_date}(*):</Label>
                    <Datepicker name="due_date" date={props.recurring_quote.due_date} handleInput={props.handleInput}
                        className={props.hasErrorFor('due_date') ? 'form-control is-invalid' : 'form-control'}/>
                    {props.renderErrorFor('due_date')}
                </FormGroup>

                <FormGroup>
                        <Label for="po_number">{translations.po_number}(*):</Label>
                        <Input value={this.props.recurring_quote.po_number} type="text" id="po_number" name="po_number"
                            onChange={this.props.handleInput}/>
                        {this.renderErrorFor('po_number')}
                    </FormGroup>

                <FormGroup>
                    <Label>{translations.frequency}</Label>
                    <Input
                        className={props.hasErrorFor('frequency') ? 'form-control is-invalid' : 'form-control'}
                        value={props.recurring_quote.frequency}
                        type='select'
                        name='frequency'
                        placeholder="Days"
                        id='frequency'
                        onChange={props.handleInput}
                    >
                        <option value="">{translations.select_frequency}</option>
                        {Object.keys(frequencyOptions).map((frequency) => (
                            <option value={frequency}>{translations[frequencyOptions[frequency]]}</option>
                        ))}
                    </Input>
                    {props.renderErrorFor('frequency')}
                </FormGroup>

                <FormGroup>
                    <Label>{translations.grace_period}</Label>
                    <Input
                        value={props.recurring_quote.grace_period}
                        type='text'
                        name='grace_period'
                        placeholder="Days"
                        id='grace_period'
                        onChange={props.handleInput}
                    />

                    <h6 id="passwordHelpBlock" className="form-text text-muted">
                        {translations.grace_period_help_text}
                    </h6>
                </FormGroup>

                <FormGroup>
                    <Label>{translations.number_of_occurances}</Label>
                    <Input
                        value={props.recurring_quote.number_of_occurances}
                        type='text'
                        name='number_of_occurances'
                        placeholder="Days"
                        id='number_of_occurances'
                        onChange={props.handleInput}
                    />
                </FormGroup>

                <a href="#"
                    className="list-group-item-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">
                            <i style={{ fontSize: '24px', marginRight: '20px' }} className={`fa ${icons.credit_card}`}/>
                            {translations.auto_billing_enabled}
                        </h5>
                        <CustomInput
                            checked={props.recurring_quote.auto_billing_enabled}
                            type="switch"
                            id="auto_billing_enabled"
                            name="auto_billing_enabled"
                            label=""
                            onChange={props.handleInput}/>
                    </div>

                    <h6 id="passwordHelpBlock" className="form-text text-muted">
                        {translations.auto_billing_enabled_help_text}
                    </h6>
                </a>

                    <FormGroup>
                        <Label for="postcode">{translations.assigned_user}:</Label>
                        <UserDropdown
                            user_id={this.props.recurring_quote.assigned_to}
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
                            customer={this.props.recurring_quote.customer_id}
                            customers={this.props.customers}
                            errors={this.props.errors}
                        />
                    </FormGroup>
                    }
                </CardBody>
            </Card>
        )
    }
}
