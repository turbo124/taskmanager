import React, { Component } from 'react'
import {
    Card,
    CardText,
    Row,
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    ListGroupItemText
} from 'reactstrap'
import ExpenseModel from '../models/ExpenseModel'
import ExpensePresenter from '../presenters/ExpensePresenter'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { translations } from '../common/_icons'

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
                                <h4 className="text-muted">{translations.amount}</h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.amount}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted">{translations.converted}</h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={convertedAmount}/>}
                            </div>
                        </div>
                    </CardText>
                </Card>

                <ExpensePresenter entity={this.props.entity} field="status_field" />

                <Row>
                    <ListGroup className="mt-4">
                        <ListGroupItem className="list-group-item-dark">
                            <ListGroupItemHeading><i className="fa fa-user-circle-o mr-2"/>
                                {this.props.entity.customer_name}
                            </ListGroupItemHeading>
                        </ListGroupItem>
                    </ListGroup>

                    <ul className="col-12">
                        <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                            <ListGroupItemHeading>{translations.date}</ListGroupItemHeading>
                            <ListGroupItemText>
                                <FormatDate date={this.props.entity.expense_date}/>
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

                        <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                            <ListGroupItemHeading>
                                {translations.exchange_rate}
                            </ListGroupItemHeading>
                            <ListGroupItemText>
                                {this.props.entity.exchange_rate}
                            </ListGroupItemText>
                        </ListGroupItem>

                        <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                            <ListGroupItemHeading>{translations.payment_date}</ListGroupItemHeading>
                            <ListGroupItemText>
                                <FormatDate date={this.props.entity.payment_date}/>
                            </ListGroupItemText>
                        </ListGroupItem>
                    </ul>
                </Row>
            </React.Fragment>

        )
    }
}
