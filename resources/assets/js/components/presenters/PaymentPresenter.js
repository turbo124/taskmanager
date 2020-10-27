import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import PaymentModel from '../models/PaymentModel'
import { paymentStatusColors, paymentStatuses } from '../utils/_consts'
import { translations } from '../utils/_translations'

export default function PaymentPresenter ( props ) {
    const { field, entity } = props

    const paymentModel = new PaymentModel ( entity.invoices, entity, entity.credits )

    const status = !entity.deleted_at
        ? <Badge color={paymentStatusColors[ entity.status_id ]}>{paymentStatuses[ entity.status_id ]}</Badge>
        : <Badge color="warning">{translations.archived}</Badge>

    switch ( field ) {
        case 'amount':
            return <td onClick={() => props.toggleViewedEntity ( entity, entity.number, props.edit )}
                       data-label="Total">{
                <FormatMoney
                    customers={props.customers} customer_id={entity.customer_id}
                    amount={entity.amount}/>}</td>
        case 'applied':
            return <td onClick={() => props.toggleViewedEntity ( entity, entity.number, props.edit )}
                       data-label="Applied">{
                <FormatMoney
                    customers={props.customers} customer_id={entity.customer_id}
                    amount={entity.applied}/>}</td>
        case 'date':
        case 'created_at': {
            return <td onClick={() => props.toggleViewedEntity ( entity, entity.number, props.edit )} data-label="Date">
                <FormatDate
                    field={field} date={entity[ field ]}/></td>
        }

        case 'status_field':
            return status

        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity ( entity, entity.number, props.edit )}
                       data-label="Status">{status}</td>

        case 'customer_id': {
            const index = props.customers.findIndex ( customer => customer.id === entity[ field ] )
            const customer = props.customers[ index ]
            return <td onClick={() => props.toggleViewedEntity ( entity, entity.number, props.edit )}
                       data-label="Customer">{customer.name}</td>
        }

        case 'invoices': {
            const invoices = paymentModel.paymentableInvoices
            const paymentInvoices = invoices && invoices.length > 0 ? Array.prototype.map.call ( invoices, s => s.number ).toString () : null

            return <td data-label="Invoices">{paymentInvoices}</td>
        }

        case 'credits': {
            const credits = paymentModel.paymentableCredits
            const paymentCredits = credits && credits.length > 0 ? Array.prototype.map.call ( credits, s => s.number ).toString () : null

            return <td data-label="Credits">{paymentCredits}</td>
        }

        default:
            return <td onClick={() => props.toggleViewedEntity ( entity, entity.number )} key={field}
                       data-label={field}>{entity[ field ]}</td>
    }
}
