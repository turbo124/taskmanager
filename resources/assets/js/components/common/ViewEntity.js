import React, { Component } from 'react'
import { Button, Modal, ModalBody, ModalFooter, ModalHeader } from 'reactstrap'
import Invoice from '../invoice/view/Invoice'
import RecurringInvoice from '../recurringInvoices/view/RecurringInvoice'
import Payment from '../payments/view/Payment'
import Customer from '../customers/view/Customer'
import Expense from '../expenses/view/Expense'
import Quote from '../quotes/view/Quote'
import RecurringQuote from '../recurringQuotes/view/RecurringQuote'
import Credit from '../credits/view/Credit'
import Project from '../projects/view/Project'
import Company from '../companies/view/Company'
import Order from '../orders/view/Order'
import Lead from '../leads/view/Lead'
import Promocode from '../promocodes/view/Promocode'
import Product from '../products/view/Product'
import User from '../users/view/User'
import Case from '../cases/view/Case'
import Subscription from '../subscriptions/view/Subscription'
import Task from '../tasks/view/Task'
import TaxRate from '../TaxRates/view/TaxRate'
import Token from '../tokens/view/Token'
import Group from '../groups/view/Group'
import Gateway from '../gateways/view/Gateway'
import Deal from '../deals/view/Deal'
import PurchaseOrder from "../purchase_orders/view/PurchaseOrder";

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
        const theme = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'dark-theme' : 'light-theme'

        return (
            <React.Fragment>
                <Modal centered={true} backdrop="static" isOpen={this.props.viewed} toggle={this.toggle}
                    className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>{this.props.title ? this.props.title : 'Details'}</ModalHeader>
                    <ModalBody className={`${theme} view-entity`}>
                        {this.props.entity && this.props.entity_type && ['Invoice'].includes(this.props.entity_type) &&
                        <Invoice customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['RecurringInvoice'].includes(this.props.entity_type) &&
                        <RecurringInvoice customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Credit'].includes(this.props.entity_type) &&
                        <Credit customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Order'].includes(this.props.entity_type) &&
                        <Order customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Customer'].includes(this.props.entity_type) &&
                        <Customer entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Promocode'].includes(this.props.entity_type) &&
                        <Promocode entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Product'].includes(this.props.entity_type) &&
                        <Product entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && (this.props.entity_type === 'Payment') &&
                        <Payment customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Expense'].includes(this.props.entity_type) &&
                        <Expense customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Quote'].includes(this.props.entity_type) &&
                        <Quote customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['RecurringQuote'].includes(this.props.entity_type) &&
                        <RecurringQuote customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Project'].includes(this.props.entity_type) &&
                        <Project customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['User'].includes(this.props.entity_type) &&
                        <User entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Deal'].includes(this.props.entity_type) &&
                        <Deal customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Company'].includes(this.props.entity_type) &&
                        <Company customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Lead'].includes(this.props.entity_type) &&
                        <Lead customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Case'].includes(this.props.entity_type) &&
                        <Case customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Subscription'].includes(this.props.entity_type) &&
                        <Subscription entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Task'].includes(this.props.entity_type) &&
                        <Task customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Tax Rate'].includes(this.props.entity_type) &&
                        <TaxRate entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Token'].includes(this.props.entity_type) &&
                        <Token entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Group'].includes(this.props.entity_type) &&
                        <Group entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Gateway'].includes(this.props.entity_type) &&
                        <Gateway entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['PurchaseOrder'].includes(this.props.entity_type) &&
                        <PurchaseOrder entity={this.props.entity} companies={this.props.companies}/>}

                    </ModalBody>
                    <ModalFooter>
                        <Button onClick={this.toggle} color="secondary">Close</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
