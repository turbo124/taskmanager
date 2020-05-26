import React, { Component } from 'react'
import {
    Alert,
    Row,
    Card,
    CardText,
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
        return (
            <React.Fragment>
                <Card body outline color="primary">
                    <CardText className="text-white">
                        <div className="d-flex">
                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted"> {translations.amount} </h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.amount}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted"> {translations.applied} </h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.applied}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted"> {translations.refunded} </h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.refunded}/>}
                            </div>
                        </div>
                    </CardText>
                </Card>

                <PaymentPresenter entity={this.props.entity} field="status_field" />

                <Row>
                    <ListGroup className="col-12 mt-4">
                        {this.props.entity.paymentables.map((line_item, index) => (
                            <ListGroupItem className="list-group-item-dark">
                                <ListGroupItemHeading>
                                    <i className={`fa ${icons.document} mr-4`}/> {line_item.number}

                                </ListGroupItemHeading>

                                <ListGroupItemText>
                                    <FormatMoney amount={line_item.amount}/> - <FormatDate date={line_item.date} />
                                </ListGroupItemText>
                            </ListGroupItem>
                        ))}
                    </ListGroup>
                </Row>

                <Row>
                    <ListGroup className="mt-4 mb-4 col-12">
                        <ListGroupItem className="list-group-item-dark">
                            <ListGroupItemHeading><i className={`fa ${icons.customer} mr-4`}/>
                                {this.props.entity.customer_name}
                            </ListGroupItemHeading>
                        </ListGroupItem>
                    </ListGroup>
                </Row>

                <Row>
                    <ul className="col-12">
                        <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                            <ListGroupItemHeading> {translations.date} </ListGroupItemHeading>
                            <ListGroupItemText>
                                <FormatDate date={this.props.entity.date}/>
                            </ListGroupItemText>
                        </ListGroupItem>

                        <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                            <ListGroupItemHeading>
                                {translations.transaction_reference}
                            </ListGroupItemHeading>
                            <ListGroupItemText>
                                {this.props.entity.transaction_reference}
                            </ListGroupItemText>
                        </ListGroupItem>
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
