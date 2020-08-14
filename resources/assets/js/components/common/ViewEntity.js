import React, { Component } from 'react'
import {
    Button,
    ListGroupItem,
    ListGroupItemHeading,
    ListGroupItemText,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader
} from 'reactstrap'
import Invoice from '../partials/Invoice'
import RecurringInvoice from '../partials/RecurringInvoice'
import Payment from '../partials/Payment'
import Customer from '../partials/Customer'
import Expense from '../partials/Expense'
import Quote from '../partials/Quote'
import RecurringQuote from '../partials/RecurringQuote'
import Credit from '../partials/Credit'
import Project from '../partials/Project'
import Company from '../partials/Company'
import Order from '../partials/Order'
import Lead from '../partials/Lead'
import Promocode from '../partials/Promocode'
import Product from '../partials/Product'
import User from '../partials/User'
import Case from '../partials/Case'
import Subscription from '../partials/Subscription'
import Task from '../partials/Task'
import TaxRate from '../partials/TaxRate'
import Token from '../partials/Token'
import Group from '../partials/Group'
import Gateway from '../partials/Gateway'

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

                        {this.props.entity && this.props.entity_type && ['Company'].includes(this.props.entity_type) &&
                        <Company customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Lead'].includes(this.props.entity_type) &&
                        <Lead customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Case'].includes(this.props.entity_type) &&
                        <Case customers={this.props.customers} entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Subscription'].includes(this.props.entity_type) &&
                        <Subscription entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Task'].includes(this.props.entity_type) &&
                        <Task entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Tax Rate'].includes(this.props.entity_type) &&
                        <TaxRate entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Token'].includes(this.props.entity_type) &&
                        <Token entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Group'].includes(this.props.entity_type) &&
                        <Group entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Gateway'].includes(this.props.entity_type) &&
                        <Gateway entity={this.props.entity}/>}

                    </ModalBody>
                    <ModalFooter>
                        <Button onClick={this.toggle} color="secondary">Close</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
