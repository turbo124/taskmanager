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
                    </CardText>
                </Card>

                <ExpensePresenter entity={this.props.entity} field="status_field" />
            </React.Fragment>

        )
    }
}
