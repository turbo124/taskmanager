import React, { Component } from 'react'
import { Button, Input, Col, Row, FormGroup, Label } from 'reactstrap'
import ProductDropdown from '../common/ProductDropdown'
import TaskDropdown from '../common/TaskDropdown'
import ExpenseDropdown from '../common/ExpenseDropdown'
import FormatMoney from './FormatMoney'

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
                    {this.props.line_type === 1 &&
                    <Col md={3} data-id={index}>
                        <FormGroup>
                            <Label>Product</Label>
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

                    {this.props.line_type === 2 &&
                    <Col md={3} data-id={index}>
                        <FormGroup>
                            <Label>Task</Label>
                            <TaskDropdown
                                dataId={index}
                                renderErrorFor={this.renderErrorFor}
                                name={`${index}|task_id`}
                                handleInputChanges={this.props.onChange}
                                task={lineItem.task_id}

                            />
                        </FormGroup>
                    </Col>
                    }

                    {this.props.line_type === 3 &&
                    <Col md={3} data-id={index}>
                        <FormGroup>
                            <Label>Expense</Label>
                            <ExpenseDropdown
                                dataId={index}
                                expenses={this.props.expenses}
                                renderErrorFor={this.renderErrorFor}
                                name="expense_id"
                                handleInputChanges={this.props.onChange}
                                expense={lineItem.expense_id}

                            />
                        </FormGroup>
                    </Col>
                    }

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>Price</Label>
                            <Input key={`a-${index}`} name="unit_price" data-line={index} type='text' data-column="5"
                                value={lineItem.unit_price} onChange={this.props.onChange}
                                className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    <Col md={1} data-id={index}>
                        <FormGroup>
                            <Label>Quantity</Label>
                            <Input key={`b-${index}`} name="quantity" data-line={index} type='text' value={lineItem.quantity}
                                onChange={this.props.onChange} className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>Discount</Label>
                            <Input key={`c-${index}`} name="unit_discount" data-line={index} type='text'
                                value={lineItem.unit_discount}
                                onChange={this.props.onChange} className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>Tax</Label>
                            <Input key={`d_${index}`} name="unit_tax" data-line={index} type='select' value={lineItem.tax_rate_id}
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
                        <Label>Sub Total</Label>
                        <p className='pa2 mr2 f6'>{<FormatMoney
                            amount={total}/>}</p>
                    </FormGroup>

                    <Col md={2} data-id={index}>
                        <FormGroup>
                            <Label>Description</Label>
                            <Input key={`e-${index}`} name="description" data-line={index} type='text'
                                value={lineItem.description}
                                onChange={this.props.onChange} className='pa2 mr2 f6 form-control'/>
                        </FormGroup>
                    </Col>

                    <Col md={2} data-id={index}>
                        <Button color="danger" onClick={(event) => {
                            this.props.onDelete(index)
                        }}>Delete</Button>
                    </Col>
                </Row>
            </React.Fragment>
        })
    }
}

export default LineItem
