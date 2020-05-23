import React, { Component } from 'react'
import {
    Row,
    Card,
    CardText,
    ListGroupItemText,
    ListGroupItemHeading,
    ListGroupItem,
    ListGroup
} from 'reactstrap'
import PaymentPresenter from '../presenters/PaymentPresenter'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'

export default class Payment extends Component {
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
                    <ListGroup className="mt-4">
                        <ListGroupItem className="list-group-item-dark">
                            <ListGroupItemHeading><i className="fa fa-user-circle-o mr-2"/>
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
            </React.Fragment>
        )
    }
}
