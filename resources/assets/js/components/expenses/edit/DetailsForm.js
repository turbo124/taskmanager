import React from 'react'
import { Card, CardBody, CardHeader, FormGroup, Input, Label } from 'reactstrap'
import CompanyDropdown from '../../common/dropdowns/CompanyDropdown'
import CustomerDropdown from '../../common/dropdowns/CustomerDropdown'
import Datepicker from '../../common/Datepicker'
import { translations } from '../../utils/_translations'
import ExpenseCategoryDropdown from '../../common/dropdowns/ExpenseCategoryDropdown'
import UserDropdown from '../../common/dropdowns/UserDropdown'
import RecurringForm from '../../common/RecurringForm'
import ProjectDropdown from '../../common/dropdowns/ProjectDropdown'
import CurrencyDropdown from '../../common/dropdowns/CurrencyDropdown'

export default class DetailsForm extends React.Component {
    render () {
        const amount_field = <FormGroup className="mb-3">
                    <Label>{translations.amount}</Label>
                    <Input value={this.props.expense.amount}
                        className={this.props.hasErrorFor('amount') ? 'is-invalid' : ''}
                        type="text" name="amount"
                        onChange={this.props.handleInput}/>
                    {this.props.renderErrorFor('amount')}
                </FormGroup>

        return (<Card>
            <CardHeader>{translations.settings}</CardHeader>
            <CardBody>
               
                {this.props.model.amountIsPretax &&
                    amount_field
                }

                <FormGroup className="mr-2">
                    <Label for="date">{translations.date}(*):</Label>
                    <Datepicker className="form-control" name="date" date={this.props.expense.date}
                        handleInput={this.props.handleInput}/>
                    {this.props.renderErrorFor('date')}
                </FormGroup>

                <FormGroup>
                    <Label for="postcode">{translations.assigned_user}:</Label>
                    <UserDropdown
                        user_id={this.props.expense.assigned_to}
                        name="assigned_to"
                        errors={this.props.errors}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>

                {!this.props.expense.invoice_id &&
                    <FormGroup>
                        <Label>{translations.project}</Label>
                        <ProjectDropdown
                            renderErrorFor={this.renderErrorFor}
                            name="project_id"
                            handleInputChanges={this.props.handleInput}
                            project={this.props.expense.project_id}
                            customer_id={this.props.expense.customer_id}
                        />
                    </FormGroup>
                }

                <FormGroup className="mr-2">
                    <Label for="date">{translations.category}(*):</Label>
                    <ExpenseCategoryDropdown
                        name="expense_category_id"
                        category={this.props.expense.expense_category_id}
                        renderErrorFor={this.props.renderErrorFor}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>

                <FormGroup>
                    <Label for="date">{translations.currency}(*):</Label>
                    <CurrencyDropdown
                        name="currency_id"
                        currency_id={this.props.expense.currency_id}
                    />
                </FormGroup>

                {!this.props.expense.invoice_id &&
                    <FormGroup className="mb-3">
                        <Label>{translations.customer}</Label>
                        <CustomerDropdown
                            customer={this.props.expense.customer_id}
                            renderErrorFor={this.props.renderErrorFor}
                            handleInputChanges={this.props.handleInput}
                            customers={this.props.customers}
                        />
                        {this.props.renderErrorFor('customer_id')}
                    </FormGroup>
                }

                <FormGroup className="mb-3">
                    <Label>{translations.company}</Label>
                    <CompanyDropdown
                        companies={this.props.companies}
                        company_id={this.props.expense.company_id}
                        renderErrorFor={this.props.renderErrorFor}
                        handleInputChanges={this.props.handleInput}
                    />
                    {this.props.renderErrorFor('company_id')}
                </FormGroup>

                <RecurringForm renderErrorFor={this.props.renderErrorFor} hasErrorFor={this.props.hasErrorFor}
                    handleInput={this.props.handleInput} recurring={this.props.expense}/>
            </CardBody>
        </Card>
        )
    }
}
