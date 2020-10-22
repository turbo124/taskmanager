import React from 'react'
import { Card, CardBody, CardHeader, Col, Collapse, CustomInput, FormGroup, Input, Label, Row } from 'reactstrap'
import CurrencyDropdown from '../../common/dropdowns/CurrencyDropdown'
import PaymentTypeDropdown from '../../common/dropdowns/PaymentTypeDropdown'
import Datepicker from '../../common/Datepicker'
import { translations } from '../../utils/_translations'
import { icons } from '../../utils/_icons'
import TaxRateDropdown from '../../common/dropdowns/TaxRateDropdown'

export default class SettingsForm extends React.Component {
    constructor (props) {
        super(props)

        this.state = {
            currencyOpen: this.props.expense.currencyOpen === true ? true : !!(this.props.expense.currency_id && this.props.expense.expense_currency_id > 0),
            paymentOpen: this.props.expense.paymentOpen === true ? true : !!(this.props.expense.payment_date && this.props.expense.payment_date.length > 1)
        }

        this.toggleCurrency = this.toggleCurrency.bind(this)
        this.togglePayment = this.togglePayment.bind(this)
        this.handleCheckboxChange = this.handleCheckboxChange.bind(this)

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.account_settings = user_account[0].account.settings
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

    toggleCurrency (buttonName, e) {
        this.setState({ currencyOpen: e.target.checked })
    }

    togglePayment (buttonName, e) {
        this.setState({ paymentOpen: e.target.checked })
    }

    handleCheckboxChange (buttonName, event) {
        const value = event.target.checked
        const name = event.target.name
        const e = {}
        e.target = {
            name: name,
            value: value,
            type: 'checkbox'
        }

        this.setState({ [name]: value }, () => this.props.handleInput(e))
    }

    render () {
        return (<Card>
            <CardHeader>
                {translations.settings}
            </CardHeader>

            <CardBody>
                {this.account_settings.show_tax_rate1 &&
                    <FormGroup>
                        <Label>{translations.tax}</Label>
                        <TaxRateDropdown
                            name="tax_rate"
                            handleInputChanges={this.props.handleInput}
                        />
                    </FormGroup>
                }

                {this.account_settings.show_tax_rate2 &&
                    <FormGroup>
                        <Label>{translations.tax}</Label>
                        <TaxRateDropdown
                            name="tax_2"
                            handleInputChanges={this.props.handleInput}
                        />
                    </FormGroup>
                }

                {this.account_settings.show_tax_rate3 &&
                    <FormGroup>
                        <Label>{translations.tax}</Label>
                        <TaxRateDropdown
                            name="tax_3"
                            handleInputChanges={this.props.handleInput}
                        />
                    </FormGroup>
                }

                <a href="#"
                    className="mt-2 mb-2 list-group-item-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">
                            <i style={{ fontSize: '24px', marginRight: '20px' }}
                                className={`fa ${icons.customer}`}/>
                            {translations.create_expense_invoice}
                        </h5>
                        <CustomInput
                            checked={this.props.expense.create_invoice}
                            type="switch"
                            id="create_invoice"
                            name="create_invoice"
                            label=""
                            onChange={this.props.handleInput}/>
                    </div>

                    <h6 id="passwordHelpBlock" className="form-text text-muted">
                        {translations.create_expense_invoice_help}
                    </h6>
                </a>

                <a href="#"
                    className="mt-2 mb-2 list-group-item-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">
                            <i style={{ fontSize: '24px', marginRight: '20px' }}
                                className={`fa ${icons.customer}`}/>
                            {translations.create_expense_payment}
                        </h5>
                        <CustomInput
                            checked={this.state.paymentOpen}
                            type="switch"
                            id="mark_paid"
                            name="paymentOpen"
                            label=""
                            onChange={this.togglePayment.bind(this, 'mark_paid')}
                        />
                    </div>

                    <h6 id="passwordHelpBlock" className="form-text text-muted">
                        {translations.create_expense_payment_help}
                    </h6>
                </a>

                <Collapse isOpen={this.state.paymentOpen}>
                    <Row form>
                        <Col md={4}>
                            <FormGroup>
                                <Label for="exampleEmail">{translations.payment_type}</Label>
                                <PaymentTypeDropdown payment_type={this.props.expense.payment_type_id}
                                    handleInputChanges={this.props.handleInput}
                                    name="payment_type_id"/>
                            </FormGroup>
                        </Col>
                        <Col md={4}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.date}</Label>
                                <Datepicker className="form-control" name="payment_date"
                                    date={this.props.expense.payment_date}
                                    handleInput={this.props.handleInput}/>
                            </FormGroup>
                        </Col>
                        <Col md={4}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.transaction_reference}</Label>
                                <Input value={this.props.expense.transaction_reference} type="text"
                                    name="transaction_reference"
                                    onChange={this.props.handleInput} id="transaction_reference"
                                    placeholder="password placeholder"/>
                            </FormGroup>
                        </Col>
                    </Row>
                </Collapse>

                <a href="#"
                    className="mt-2 mb-2 list-group-item-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">
                            <i style={{ fontSize: '24px', marginRight: '20px' }}
                                className={`fa ${icons.customer}`}/>
                            {translations.convert_expense_currency}
                        </h5>
                        <CustomInput
                            checked={this.state.currencyOpen}
                            type="switch"
                            id="convert_currency"
                            name="currencyOpen"
                            label=""
                            onChange={this.toggleCurrency.bind(this, 'convert_currency')}
                        />
                    </div>

                    <h6 id="passwordHelpBlock" className="form-text text-muted">
                        {translations.convert_expense_currency_help}
                    </h6>
                </a>

                <Collapse isOpen={this.state.currencyOpen}>
                    <Row form>
                        <Col md={6}>
                            <FormGroup>
                                <Label for="exampleEmail">{translations.currency}</Label>
                                <CurrencyDropdown currency_id={this.props.expense.invoice_currency_id}
                                    handleInputChanges={this.props.handleInput}
                                    name="invoice_currency_id"/>
                            </FormGroup>
                        </Col>
                        <Col md={6}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.exchange_rate}</Label>
                                <Input type="text" name="exchange_rate" id="exchange_rate"
                                    onChange={this.props.handleInput}
                                    value={this.props.expense.exchange_rate}
                                    placeholder={translations.exchange_rate}/>
                            </FormGroup>
                        </Col>
                    </Row>
                </Collapse>

                <a href="#"
                    className="mt-2 mb-2 list-group-item-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">
                            <i style={{ fontSize: '24px', marginRight: '20px' }}
                                className={`fa ${icons.customer}`}/>
                            {translations.include_expense_documents}
                        </h5>
                        <CustomInput
                            checked={this.props.expense.include_documents}
                            type="switch"
                            id="include_documents"
                            name="include_documents"
                            label=""
                            onChange={this.props.handleInput}/>
                    </div>

                    <h6 id="passwordHelpBlock" className="form-text text-muted">
                        {translations.include_expense_documents_help}
                    </h6>
                </a>
            </CardBody>
        </Card>
        )
    }
}
