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

                        const items = this.props.entity && this.props.entity.line_items ? this.props.entity.line_items.map((line_item) =>
                            <ListGroupItem>
                                <ListGroupItemHeading className="d-flex justify-content-between align-items-center">
                                    Product name
                                    <span>Subtotal</span>
                                </ListGroupItemHeading>
                            <ListGroupItemText>
                                Quantity x price - discount <br>
                                Description 
                            </ListGroupItemText>
                        </ListGroupItem>
                    ) : null

                        {this.props.entity && ['Invoice', 'Quote', 'Credit', 'Order'].includes(this.props.entity_type) &&
                        <React.Fragment>
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

                            <ListGroup>
                                {items}
                            </ListGroup>

                          

     <ListGroup>
      <ListGroupItem className="justify-content-between">Tax <Badge pill>{this.props.entity.tax_total}</Badge></ListGroupItem>
      <ListGroupItem className="justify-content-between">Discount <Badge pill> {this.props.entity.discount_total}</Badge></ListGroupItem>
      <ListGroupItem className="justify-content-between">Subtotal <Badge pill> {this.props.entity.sub_total} </Badge></ListGroupItem>
       <ListGroupItem className="justify-content-between">Total <Badge pill> {this.props.entity.total} </Badge></ListGroupItem>
    </ListGroup>
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
