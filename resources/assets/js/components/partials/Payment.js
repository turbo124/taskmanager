import React, { Component } from 'react'
import {
    Alert,
    Row,
    ListGroupItemText,
    ListGroupItemHeading,
    ListGroupItem,
    ListGroup, NavLink
} from 'reactstrap'
import PaymentPresenter from '../presenters/PaymentPresenter'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { icons, translations } from '../common/_icons'
import PaymentModel from '../models/PaymentModel'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SimpleSectionItem from '../common/entityContainers/SimpleSectionItem'

export default class Payment extends Component {
    constructor (props) {
        super(props)

        this.state = {
            show_success: false
        }

        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        const paymentModel = new PaymentModel(this.props.entity)
        paymentModel.completeAction(this.props.entity, action).then(response => {
            this.setState({ show_success: true })

            setTimeout(
                function () {
                    this.setState({ show_success: false })
                }
                    .bind(this),
                2000
            )
        })
    }

    render () {
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))

        return (
            <React.Fragment>
                <ViewEntityHeader heading_1={translations.amount} value_1={this.props.entity.amount}
                    heading_2={translations.applied} value_2={this.props.entity.applied}/>

                <PaymentPresenter entity={this.props.entity} field="status_field" />

                <Row>
                    <ListGroup className="col-12 mt-4">
                        {this.props.entity.paymentables.map((line_item, index) => (
                            <a key={index} href={`/#/invoice?number=${line_item.number}`} >
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading>
                                        <i className={`fa ${icons.document} mr-4`}/> {line_item.number}

                                    </ListGroupItemHeading>

                                    <ListGroupItemText>
                                        <FormatMoney amount={line_item.amount}/> - <FormatDate date={line_item.date} />
                                    </ListGroupItemText>
                                </ListGroupItem>
                            </a>
                        ))}
                    </ListGroup>
                </Row>

                <Row>
                    <ListGroup className="mt-4 mb-4 col-12">
                        <ListGroupItem className="list-group-item-dark">
                            <ListGroupItemHeading><i className={`fa ${icons.customer} mr-4`}/>
                                {customer[0].name}
                            </ListGroupItemHeading>
                        </ListGroupItem>
                    </ListGroup>
                </Row>

                <Row>
                    <ul className="col-12">
                        <SimpleSectionItem heading={translations.date} value={<FormatDate date={this.props.entity.date}/>} />
                        <SimpleSectionItem heading={translations.transaction_reference} value={this.props.entity.transaction_reference} />
                    </ul>
                </Row>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                <div className="navbar d-flex p-0 view-buttons">
                    <NavLink className="flex-fill border border-secondary btn btn-dark"
                        onClick={() => {
                            this.toggleTab('3')
                        }}>
                        {translations.refund}
                    </NavLink>
                    <NavLink className="flex-fill border border-secondary btn btn-dark"
                        onClick={() => {
                            this.triggerAction('archive')
                        }}>
                        {translations.archive}
                    </NavLink>
                </div>
            </React.Fragment>
        )
    }
}
