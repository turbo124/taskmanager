import React from 'react'
import { Card, CardBody, CardHeader, Col, Collapse, CustomInput, FormGroup, Input, Label, Row } from 'reactstrap'
import CurrencyDropdown from '../common/CurrencyDropdown'
import PaymentTypeDropdown from '../common/PaymentTypeDropdown'
import Datepicker from '../common/Datepicker'
import { translations } from '../common/_translations'

export default class SettingsForm extends React.Component {
    constructor (props) {
        super(props)

        this.state = {
            currencyOpen: !!(this.props.expense.currency_id && this.props.expense.expense_currency_id > 0),
            paymentOpen: !!(this.props.expense.payment_date && this.props.expense.payment_date.length > 1)
        }

        this.toggleCurrency = this.toggleCurrency.bind(this)
        this.togglePayment = this.togglePayment.bind(this)
        this.handleCheckboxChange = this.handleCheckboxChange.bind(this)
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
        const name = event.target.id
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
                <CustomInput
                    checked={this.props.expense.create_invoice}
                    type="switch"
                    id="create_invoice"
                    name="customSwitch"
                    label="Mark Billable"
                    onChange={this.handleCheckboxChange.bind(this, 'create_invoice')}/>

                <CustomInput
                    checked={this.state.paymentOpen}
                    type="switch"
                    id="mark_paid"
                    name="customSwitch"
                    label="Mark Paid"
                    onChange={this.togglePayment.bind(this, 'mark_paid')}
                />
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

                <CustomInput
                    checked={this.state.currencyOpen}
                    type="switch"
                    id="convert_currency"
                    name="customSwitch"
                    label="Convert Currency"
                    onChange={this.toggleCurrency.bind(this, 'convert_currency')}
                />
                <Collapse isOpen={this.state.currencyOpen}>
                    <Row form>
                        <Col md={6}>
                            <FormGroup>
                                <Label for="exampleEmail">{translations.currency}</Label>
                                <CurrencyDropdown currency_id={this.props.expense.currency_id}
                                    handleInputChanges={this.props.handleInput}
                                    name="currency_id"/>
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

                <CustomInput
                    checked={this.props.expense.include_documents}
                    type="switch"
                    id="include_documents"
                    name="customSwitch"
                    label="Add Documents to Invoice"
                    onChange={this.handleCheckboxChange.bind(this, 'include_documents')}/>
            </CardBody>
        </Card>
        )
    }
}
