import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { consts } from '../common/_consts'
import { translations } from '../common/_translations'
import PaymentModel from '../models/PaymentModel'

export default function PaymentPresenter (props) {
    const colors = {
        [consts.payment_status_pending]: 'secondary',
        [consts.payment_status_voided]: 'danger',
        [consts.payment_status_failed]: 'danger',
        [consts.payment_status_completed]: 'success',
        [consts.payment_status_partial_refund]: 'dark',
        [consts.payment_status_refunded]: 'danger'
    }

    const statuses = {
        [consts.payment_status_pending]: translations.pending,
        [consts.payment_status_voided]: translations.voided,
        [consts.payment_status_failed]: translations.failed,
        [consts.payment_status_completed]: translations.complete,
        [consts.payment_status_partial_refund]: translations.partial_refund,
        [consts.payment_status_refunded]: translations.refunded
    }

    const { field, entity } = props

    const paymentModel = new PaymentModel(entity.invoices, entity, entity.credits)
    const invoices = paymentModel.paymentableInvoices
    const credits = paymentModel.paymentableCredits

    const status = !entity.deleted_at
        ? <Badge color={colors[entity.status_id]}>{statuses[entity.status_id]}</Badge>
        : <Badge color="warning">Archived</Badge>

    const paymentInvoices = invoices && invoices.length > 0 ? Array.prototype.map.call(invoices, s => s.number).toString() : null
    const paymentCredits = credits && credits.length > 0 ? Array.prototype.map.call(credits, s => s.number).toString() : null

    switch (field) {
        case 'amount':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Total">{<FormatMoney
                customers={props.customers} customer_id={entity.customer_id}
                amount={entity.amount}/>}</td>
        case 'applied':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Applied">{<FormatMoney
                customers={props.customers} customer_id={entity.customer_id}
                amount={entity.applied}/>}</td>
        case 'date': {
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Date"><FormatDate field={field} date={entity[field]} /></td>
        }

        case 'status_field':
            return status

        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Status">{status}</td>

        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Customer">{customer.name}</td>
        }

        case 'invoices':
            return <td data-label="Invoices">{paymentInvoices}</td>
        case 'credits':
            return <td data-label="Credits">{paymentCredits}</td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
