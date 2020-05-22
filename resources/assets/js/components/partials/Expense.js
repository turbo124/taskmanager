import React, { Component } from 'react'
import {
    Card,
    CardText
} from 'reactstrap'
import FormatMoney from './FormatMoney'
import ExpenseModel from '../models/ExpenseModel'
import PaymentPresenter from '../presenters/PaymentPresenter'
import ExpensePresenter from '../presenters/ExpensePresenter'

export default class Expense extends Component {
    render () {
        const expenseModel = new ExpenseModel(this.props.entity)
        const convertedAmount = expenseModel.convertedAmount

        return (
            <React.Fragment>
                <Card body outline color="success">
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
                                <h4>Converted</h4>
                                {<FormatMoney
                                    amount={convertedAmount}/>}
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

                <ExpensePresenter entity={this.props.entity} field="status_field" />
            </React.Fragment>

        )
    }
}
