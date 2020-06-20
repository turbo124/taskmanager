import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'

export default function Transaction (props) {
    const transactions = props.transactions.length ? props.transactions.map((transaction, index) => (
        <dl key={index} className="row border-bottom">
            <dt className="col-sm-2">{transaction.entity_name}</dt>
            <dt className="col-sm-3">{<FormatDate date={transaction.created_at}/>}</dt>
            <dd className="col-sm-2">{<FormatMoney amount={transaction.amount}/>}<br/><FormatMoney
                amount={transaction.updated_balance}/>
            </dd>
            <dd className="col-sm-5">{transaction.notes}</dd>
        </dl>
    )) : null

    return transactions
}
