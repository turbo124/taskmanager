import React, { Component } from 'react'
import { Button, Col, FormGroup, Input, Label, Row } from 'reactstrap'
import ProductAttributeDropdown from './dropdowns/ProductAttributeDropdown'
import ProductDropdown from './dropdowns/ProductDropdown'
import TaskDropdown from './dropdowns/TaskDropdown'
import ExpenseDropdown from './dropdowns/ExpenseDropdown'
import FormatMoney from './FormatMoney'
import { translations } from '../utils/_translations'
import { consts } from "../utils/_consts";

class LineItem extends Component {
    constructor (props) {
        super(props)
        // this.state = Object.assign({}, props.lineItemData)
        this.handleDeleteClick = this.handleDeleteClick.bind(this)
        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = user_account[0].account.settings
    }

    handleDeleteClick () {
        this.props.onDelete(this.props.id)
    }

    renderErrorFor () {

    }

    render () {
        const uses_inclusive_taxes = this.settings.inclusive_taxes

        return this.props.rows.map((lineItem, index) => {
            let total = 0

            if (lineItem.unit_price > 0 && lineItem.quantity > 0) {
                total = lineItem.unit_price * lineItem.quantity

                if (lineItem.unit_discount > 0) {
                    if (this.props.invoice.is_amount_discount === true) {
                        total -= lineItem.unit_discount
                    } else {
                        const percentage = total * lineItem.unit_discount / 100
                        total -= percentage
                    }
                }

                if (lineItem.unit_tax > 0) {
                    const tax_percentage = total * lineItem.unit_tax / 100

                    if (uses_inclusive_taxes === false) {
                        total += tax_percentage
                    }
                }
            }

            return <React.Fragment key={index}>
                <Row className="border-bottom border-primary my-3" form>
                    {lineItem.type_id === 1 &&
                    <Col md={3} data-id={index}>
                        <FormGroup>
                            <Label>{translations.product}</Label>
                            <ProductDropdown
                                dataId={index}
                                renderErrorFor={this.renderErrorFor}
                                name="product_id"
                                handleInputChanges={this.props.onChange}
                                product={lineItem.product_id}
                                products={this.props.products}
                            />
                        </FormGroup>
                    </Col>
                    }

                    {lineItem.type_id === consts.line_item_task &&
                    <Col md={3} data-id={index}>
                        <FormGroup>
                            <Label>{translations.task}</Label>
                            <TaskDropdown
                                tasks={this.props.tasks}
                                dataId={index}
                                single_only={true}
                                renderErrorFor={this.renderErrorFor}
                                name="task_id"
                                handleInputChanges={this.props.onChange}
                                task={lineItem.product_id}

                            />
                        </FormGroup>
                    </Col>
                    }

                    {lineItem.type_id === consts.line_item_expense &&
                    <Col md={3} data-id={index}>
                        <FormGroup>
                            <Label>{translations.expense}</Label>
                            <ExpenseDropdown
                                dataId={index}
                                expenses={this.props.expenses}
                                renderErrorFor={this.renderErrorFor}
                                name="expense_id"
                                handleInputChanges={this.props.onChange}
                                expense={lineItem.product_id}

                            />
                        </FormGroup>
                    </Col>
                    }

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>{translations.price}</Label>
                            <Input key={`a-${index}`} name="unit_price" data-line={index} type='text' data-column="5"
                                value={lineItem.unit_price} onChange={this.props.onChange}
                                className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    <Col md={1} data-id={index}>
                        <FormGroup>
                            <Label>{translations.quantity}</Label>
                            <Input key={`b-${index}`} name="quantity" data-line={index} type='text'
                                value={lineItem.quantity}
                                onChange={this.props.onChange} className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>{translations.discount}</Label>
                            <Input key={`c-${index}`} name="unit_discount" data-line={index} type='text'
                                value={lineItem.unit_discount}
                                onChange={this.props.onChange} className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>{translations.tax}</Label>
                            <Input key={`d_${index}`} name="unit_tax" data-line={index} type='select'
                                value={lineItem.tax_rate_id}
                                onChange={this.props.onChange} className='pa2 mr2 f6 form-control'>
                                <option value="0">No Tax</option>
                                {this.props.tax_rates.map(tax_rate =>
                                    <option key={tax_rate.id} data-rate={tax_rate.rate}
                                        value={tax_rate.id}>{`${tax_rate.name} (${tax_rate.rate})`}</option>
                                )}
                            </Input>
                        </FormGroup>
                    </Col>
                    <FormGroup className="mr-4">
                        <Label>Tax Total</Label>
                        <p className='pa2 mr2 f6'>{<FormatMoney
                            amount={lineItem.tax_total}/>}</p>
                    </FormGroup>

                    <FormGroup>
                        <Label>{translations.subtotal}</Label>
                        <p className='pa2 mr2 f6'>{<FormatMoney
                            amount={total}/>}</p>
                    </FormGroup>

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>{translations.description}</Label>
                            <Input key={`e-${index}`} name="description" data-line={index} type='text'
                                value={lineItem.description}
                                onChange={this.props.onChange} className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    {lineItem.type_id === consts.line_item_product &&
                    <Col md={3} data-id={index}>
                        <FormGroup>
                            <Label>{translations.variation}</Label>
                            <ProductAttributeDropdown
                                dataId={index}
                                renderErrorFor={this.renderErrorFor}
                                name="attribute_id"
                                handleInputChanges={this.props.onChange}
                                attribute_value_id={lineItem.attribute_id}
                                product_id={lineItem.product_id}
                            />
                        </FormGroup>
                    </Col>
                    }

                    <Col md={2} data-id={index}>
                        <Button color="danger" onClick={(event) => {
                            this.props.onDelete(index)
                        }}>{translations.delete}</Button>
                    </Col>
                </Row>
            </React.Fragment>
        })
    }
}

export default LineItem
