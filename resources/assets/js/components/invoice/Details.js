import React, { Component } from 'react'
import { FormGroup, Label, Input, Card, CardHeader, CardBody } from 'reactstrap'
import Datepicker from '../common/Datepicker'
import { translations } from '../common/_translations'
import UserDropdown from "../common/UserDropdown";

export default class Details extends Component {
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
        return (
            <Card>
                <CardHeader>{translations.details}</CardHeader>
                <CardBody>
                    <FormGroup>
                        <Label for="date">{translations.date}(*):</Label>
                        <Datepicker name="date" date={this.props.invoice.date} handleInput={this.props.handleInput}
                            className={this.hasErrorFor('date') ? 'form-control is-invalid' : 'form-control'}/>
                        {this.renderErrorFor('date')}
                    </FormGroup>
                    <FormGroup>
                        <Label for="due_date">{translations.due_date}(*):</Label>
                        <Datepicker name="due_date" date={this.props.invoice.due_date} handleInput={this.props.handleInput}
                            className={this.hasErrorFor('due_date') ? 'form-control is-invalid' : 'form-control'}/>
                        {this.renderErrorFor('due_date')}
                    </FormGroup>
                    <FormGroup>
                        <Label for="po_number">{translations.po_number}(*):</Label>
                        <Input value={this.props.invoice.po_number} type="text" id="po_number" name="po_number"
                            onChange={this.props.handleInput}/>
                        {this.renderErrorFor('po_number')}
                    </FormGroup>
                    <FormGroup>
                        <Label>{translations.partial}</Label>
                        <Input
                            value={this.props.invoice.partial}
                            type='text'
                            name='partial'
                            id='partial'
                            onChange={this.props.handleInput}
                        />
                    </FormGroup>

                    <FormGroup className={this.props.invoice.has_partial === true ? '' : 'd-none'}>
                        <Label>{translations.partial_due_date}</Label>
                        <Datepicker name="partial_due_date" date={this.props.invoice.partial_due_date} handleInput={this.props.handleInput}
                            className={this.hasErrorFor('partial_due_date') ? 'form-control is-invalid' : 'form-control'}/>
                    </FormGroup>

                    <FormGroup>
                        <Label for="postcode">{translations.assigned_user}:</Label>
                        <UserDropdown
                            user_id={this.props.invoice.assigned_to}
                            name="assigned_to"
                            errors={this.props.errors}
                            handleInputChanges={this.props.handleInput}
                        />
                    </FormGroup>
                </CardBody>
            </Card>

        )
    }
}
