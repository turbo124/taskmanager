import React from 'react'
import {
    Input,
    FormGroup,
    Label,
    Card,
    CardBody,
    CardHeader
} from 'reactstrap'
import CompanyDropdown from '../common/CompanyDropdown'
import CustomerDropdown from '../common/CustomerDropdown'
import Datepicker from '../common/Datepicker'
import { translations } from '../common/_translations'
import ExpenseCategoryDropdown from '../common/ExpenseCategoryDropdown'
import UserDropdown from "../common/UserDropdown";

export default class DetailsForm extends React.Component {
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
            <CardHeader>{translations.settings}</CardHeader>
            <CardBody>
                <FormGroup className="mb-3">
                    <Label>{translations.amount}</Label>
                    <Input value={this.props.expense.amount}
                        className={this.hasErrorFor('amount') ? 'is-invalid' : ''}
                        type="text" name="amount"
                        onChange={this.props.handleInput}/>
                    {this.renderErrorFor('amount')}
                </FormGroup>

                <FormGroup className="mr-2">
                    <Label for="date">{translations.date}(*):</Label>
                    <Datepicker className="form-control" name="date" date={this.props.expense.date}
                        handleInput={this.props.handleInput}/>
                    {this.renderErrorFor('date')}
                </FormGroup>

                <FormGroup>
                    <Label for="postcode">{translations.assigned_user}:</Label>
                    <UserDropdown
                        user_id={this.props.product.assigned_to}
                        name="assigned_to"
                        errors={this.props.errors}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>

                <FormGroup className="mr-2">
                    <Label for="date">{translations.category}(*):</Label>
                    <ExpenseCategoryDropdown
                        name="category_id"
                        category={this.props.category_id}
                        renderErrorFor={this.renderErrorFor}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>

                <FormGroup className="mb-3">
                    <Label>{translations.customer}</Label>
                    <CustomerDropdown
                        customer={this.props.expense.customer_id}
                        renderErrorFor={this.renderErrorFor}
                        handleInputChanges={this.props.handleInput}
                        customers={this.props.customers}
                    />
                    {this.renderErrorFor('customer_id')}
                </FormGroup>

                <FormGroup className="mb-3">
                    <Label>{translations.company}</Label>
                    <CompanyDropdown
                        companies={this.props.companies}
                        company_id={this.props.expense.company_id}
                        renderErrorFor={this.renderErrorFor}
                        handleInputChanges={this.props.handleInput}
                    />
                    {this.renderErrorFor('company_id')}
                </FormGroup>
            </CardBody>
        </Card>
        )
    }
}
