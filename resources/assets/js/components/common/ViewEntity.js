import React, { Component } from 'react'
import {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    Button,
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    ListGroupItemText
} from 'reactstrap'
import InvoiceTotals from './InvoiceTotals'
import PaymentTotals from './PaymentTotals'
import CustomerTotals from './CustomerTotals'
import ExpenseTotals from './ExpenseTotals'
import QuoteTotals from './QuoteTotals'
import CreditTotals from './CreditTotals'
import FormatDate from './FormatDate'

export default class ViewEntity extends Component {
    constructor (props) {
        super(props)
        this.state = {
            roles: [],
            modal: true
        }

        this.toggle = this.toggle.bind(this)
    }

    toggle () {
        this.setState({
            modal: false,
            errors: []
        }, () => this.props.toggle())
    }

    render () {
        const columnList = this.props.entity ? Object.keys(this.props.entity).filter(key => {
            return this.props.ignore && !this.props.ignore.includes(key) && typeof this.props.entity[key] !== 'object'
        }).map(key => {
            let column_name = key.replace(/_/g, ' ')
            column_name = column_name.replace(
                /\w\S*/g,
                function (txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
                }
            )

            return <ListGroupItem className="col-md-6 col-12 pull-left" color="dark" key={key}>
                <ListGroupItemHeading>
                    {this.props.entity[key]}
                </ListGroupItemHeading>

                <ListGroupItemText>
                    {column_name}
                </ListGroupItemText>
            </ListGroupItem>
        }) : null

        return (
            <React.Fragment>
                <Modal centered={true} backdrop="static" isOpen={this.props.viewed} toggle={this.toggle}
                    className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>{this.props.title ? this.props.title : 'Details'}</ModalHeader>
                    <ModalBody>
                        {this.props.entity && this.props.entity_type && ['Invoice'].includes(this.props.entity_type) &&
                        <InvoiceTotals entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Credit'].includes(this.props.entity_type) &&
                        <CreditTotals entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Quote'].includes(this.props.entity_type) &&
                        <QuoteTotals entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Customer'].includes(this.props.entity_type) &&
                        <CustomerTotals entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && (this.props.entity_type === 'Payment') &&
                        <PaymentTotals entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Expense'].includes(this.props.entity_type) &&
                        <ExpenseTotals entity={this.props.entity}/>}

                        {!['Payment', 'Invoice', 'Quote', 'Credit', 'Order'].includes(this.props.entity_type) &&
                        <ul className="mt-4 row">
                            {columnList}
                        </ul>
                        }

                        {this.props.entity && ['Invoice', 'Quote', 'Credit', 'Order'].includes(this.props.entity_type) &&
                        <React.Fragment>
                            <div className="row">
                                <ListGroup className="mt-4">
                                    <ListGroupItem className="list-group-item-dark">
                                        <ListGroupItemHeading><i
                                            className="fa fa-user-circle-o mr-2"/>{this.props.entity.customer_name}
                                        </ListGroupItemHeading>
                                    </ListGroupItem>
                                </ListGroup>
                                <ul className="mt-4">
                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>Invoice Date</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            <FormatDate date={this.props.entity.date}/>
                                        </ListGroupItemText>
                                    </ListGroupItem>

                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>Due Date</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {this.props.entity.due_date}
                                        </ListGroupItemText>
                                    </ListGroupItem>

                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>PO Number</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            <FormatDate date={this.props.entity.po_number}/>
                                        </ListGroupItemText>
                                    </ListGroupItem>

                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>Discount</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {this.props.entity.discount_total}
                                        </ListGroupItemText>
                                    </ListGroupItem>
                                </ul>
                            </div>

                            <div className="row">
                                <ListGroup className="col-12 mt-4">
                                    {this.props.entity.line_items.map((line_item, index) => (
                                        <ListGroupItem className="list-group-item-dark">
                                            <ListGroupItemHeading
                                                className="d-flex justify-content-between align-items-center">
                                                {line_item.product_id}
                                                <span>{line_item.sub_total}</span>
                                            </ListGroupItemHeading>
                                            <ListGroupItemText>
                                                {line_item.quantity} x {line_item.unit_price} Discount: {line_item.unit_discount} Tax: {line_item.unit_tax}
                                                <br/>
                                                {line_item.description}
                                            </ListGroupItemText>
                                        </ListGroupItem>
                                    ))}
                                </ListGroup>
                            </div>

                            <div className="row justify-content-end">
                                <ListGroup className="col-6 mt-4">
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Tax
                                        <span>{this.props.entity.tax_total}</span>
                                    </ListGroupItem>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Discount
                                        <span> {this.props.entity.discount_total}</span>
                                    </ListGroupItem>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Subtotal
                                        <span> {this.props.entity.sub_total} </span>
                                    </ListGroupItem>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Total
                                        <span> {this.props.entity.total} </span>
                                    </ListGroupItem>
                                </ListGroup>
                            </div>

                        </React.Fragment>

                        }

                        {this.props.entity && ['Payment'].includes(this.props.entity_type) &&
                        <React.Fragment>
                            <ListGroup className="mt-4">
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i className="fa fa-user-circle-o mr-2"/> Client
                                        here</ListGroupItemHeading>
                                </ListGroupItem>
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i
                                        className="fa fa-credit-card-alt mr-2"/> {this.props.entity.number}
                                    </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {this.props.entity.amount}
                                    </ListGroupItemText>
                                </ListGroupItem>
                            </ListGroup>

                            <ul>
                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>Payment Date</ListGroupItemHeading>
                                    <ListGroupItemText>
                                        <FormatDate date={this.props.entity.date}/>
                                    </ListGroupItemText>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>
                                        Transaction Reference
                                    </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {this.props.entity.transaction_reference}
                                    </ListGroupItemText>
                                </ListGroupItem>
                            </ul>
                        </React.Fragment>

                        }

                    </ModalBody>
                    <ModalFooter>
                        <Button onClick={this.toggle} color="secondary">Close</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
