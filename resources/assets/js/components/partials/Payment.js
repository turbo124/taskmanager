import React, { Component } from 'react'
import {
    Row,
    Card,
    CardText
} from 'reactstrap'
import FormatMoney from './FormatMoney'
import PaymentPresenter from '../presenters/PaymentPresenter'

export default class Payment extends Component {
    render () {
        return (
            <React.Fragment>
                <Card body outline color="primary">
                    <CardText className="text-white">
                        <div className="d-flex">
                            <div
                                className="p-2 flex-fill">
                                <h4>Amount</h4>
                                {<FormatMoney
                                    amount={this.props.entity.amount}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4>Applied</h4>
                                {<FormatMoney
                                    amount={this.props.entity.applied}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4>Refunded</h4>
                                {<FormatMoney
                                    amount={this.props.entity.refunded}/>}
                            </div>
                        </div>

                       <Row>
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
                       </Row>
                    </CardText>
                </Card>

                <PaymentPresenter entity={this.props.entity} field="status_field" />
            </React.Fragment>
        )
    }
}
