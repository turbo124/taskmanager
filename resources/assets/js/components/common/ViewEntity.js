import React, { Component } from 'react'
import {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    Button,
    ListGroupItem,
    ListGroupItemHeading,
    ListGroupItemText
} from 'reactstrap'
import Invoice from '../partials/Invoice'
import Payment from '../partials/Payment'
import Customer from '../partials/Customer'
import Expense from '../partials/Expense'
import Quote from '../partials/Quote'
import Credit from '../partials/Credit'
import Project from '../partials/Project'
import Company from '../partials/Company'
import Order from '../partials/Order'
import Lead from '../partials/Lead'

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
                        <Invoice entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Credit'].includes(this.props.entity_type) &&
                        <Credit entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Order'].includes(this.props.entity_type) &&
                        <Order entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Quote'].includes(this.props.entity_type) &&
                        <Quote entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Customer'].includes(this.props.entity_type) &&
                        <Customer entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && (this.props.entity_type === 'Payment') &&
                        <Payment entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Expense'].includes(this.props.entity_type) &&
                        <Expense entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Quote'].includes(this.props.entity_type) &&
                        <Quote entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Project'].includes(this.props.entity_type) &&
                        <Project entity={this.props.entity}/>}

                        {this.props.entity && this.props.entity_type && ['Company'].includes(this.props.entity_type) &&
                        <Company entity={this.props.entity}/>}

                       {this.props.entity && this.props.entity_type && ['Lead'].includes(this.props.entity_type) &&
                        <Lead entity={this.props.entity}/>}

                        {!['Company', 'Project', 'Payment', 'Invoice', 'Quote', 'Credit', 'Order', 'Expense', 'Customer'].includes(this.props.entity_type) &&
                        <ul className="mt-4 row">
                            {columnList}
                        </ul>
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
