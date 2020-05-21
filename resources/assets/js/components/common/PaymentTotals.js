import React, { Component } from 'react'
import {
    Card,
    CardText
} from 'reactstrap'
import FormatMoney from './FormatMoney'
import PaymentPresenter from '../presenters/PaymentPresenter'

export default class PaymentTotals extends Component {
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
                    </CardText>
                </Card>

                <PaymentPresenter entity={this.props.entity} field="status_field" />
            </React.Fragment>
        )
    }
}
