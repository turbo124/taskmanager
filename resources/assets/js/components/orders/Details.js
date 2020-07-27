import React from 'react'
import {
    FormGroup,
    Label,
    Card,
    CardBody,
    CardHeader, Input
} from 'reactstrap'
import Datepicker from '../common/Datepicker'
import CustomerDropdown from '../common/CustomerDropdown'
import { translations } from '../common/_translations'
import UserDropdown from "../common/UserDropdown";

export default class Details extends React.Component {
    constructor (props) {
        super(props)

        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
    }

    hasErrorFor (field) {
        return !!this.props.errors[field]
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
        return (<Card>
            <CardHeader>{translations.details}</CardHeader>
            <CardBody>
                <FormGroup className="mr-2">
                    <Label for="date">{translations.date}(*):</Label>
                    <Datepicker name="date" date={this.props.order.date} handleInput={this.props.handleInput}
                        className={this.hasErrorFor('date') ? 'form-control is-invalid' : 'form-control'}/>
                    {this.renderErrorFor('date')}
                </FormGroup>

                <FormGroup>
                    <Label for="due_date">{translations.due_date}(*):</Label>
                    <Datepicker name="due_date" date={this.props.order.due_date} handleInput={this.props.handleInput}
                        className={this.hasErrorFor('due_date') ? 'form-control is-invalid' : 'form-control'}/>
                    {this.renderErrorFor('due_date')}
                </FormGroup>

                <FormGroup>
                    <Label for="po_number">{translations.po_number}(*):</Label>
                    <Input value={this.props.order.po_number} type="text" id="po_number" name="po_number"
                        onChange={this.props.handleInput}/>
                    {this.renderErrorFor('po_number')}
                </FormGroup>

                <FormGroup>
                    <Label for="postcode">{translations.assigned_user}:</Label>
                    <UserDropdown
                        user_id={this.props.order.assigned_to}
                        name="assigned_to"
                        errors={this.props.errors}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>{translations.customer}</Label>
                    <CustomerDropdown
                        handleInputChanges={this.props.handleInput}
                        customer={this.props.order.customer_id}
                        customers={this.props.customers}
                        errors={this.props.errors}
                    />
                </FormGroup>
            </CardBody>
        </Card>
        )
    }
}
