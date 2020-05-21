import React, { Component } from 'react'
import {
    Card,
    CardText
} from 'reactstrap'
import FormatMoney from './FormatMoney'
import InvoicePresenter from '../presenters/InvoicePresenter'

export default class InvoiceTotals extends Component {
    render () {
        return (
            <React.Fragment>
                <Card body outline color="primary">
                    <CardText className="text-white">
                        <div className="d-flex">
                            <div
                                className="p-2 flex-fill">
                                <h4>Total</h4>
                                {<FormatMoney
                                    amount={this.props.entity.total}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4>Balance</h4>
                                {<FormatMoney
                                    amount={this.props.entity.balance} />}
                            </div>
                        </div>
                    </CardText>
                </Card>

                <InvoicePresenter entity={this.props.entity} field="status_field" />
            </React.Fragment>

        )
    }
}
