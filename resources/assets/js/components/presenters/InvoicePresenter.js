import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { consts } from '../common/_consts'
import InvoiceModel from '../models/InvoiceModel'
import { translations } from '../common/_translations'

export default function InvoicePresenter (props) {
    const colors = {
        [consts.invoice_status_draft]: 'secondary',
        [consts.invoice_status_sent]: 'primary',
        [consts.invoice_status_paid]: 'success',
        [consts.invoice_status_partial]: 'warning',
        [consts.invoice_status_draft_text]: 'danger',
        [consts.invoice_status_reversed]: 'danger',
        [consts.invoice_status_cancelled]: 'danger',
        100: 'danger'
    }

    const statuses = {
        [consts.invoice_status_draft]: translations.draft,
        [consts.invoice_status_sent]: translations.sent,
        [consts.invoice_status_paid]: translations.paid,
        [consts.invoice_status_partial]: translations.partial,
        [consts.invoice_status_cancelled]: translations.cancelled,
        100: translations.overdue,
        [consts.invoice_status_reversed]: translations.reversed
    }

    const { field, entity } = props

    const objInvoiceModel = new InvoiceModel(entity, props.customers)
    const is_late = objInvoiceModel.isLate()

    const entity_status = is_late === true ? 100 : entity.status_id

    const status = !entity.deleted_at
        ? <Badge color={colors[entity_status]}>{statuses[entity_status]}</Badge>
        : <Badge className="mr-2" color="warning">Archived</Badge>

    switch (field) {
        case 'balance':
        case 'total':
        case 'discount_total':
        case 'tax_total':
        case 'sub_total':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}>
                <FormatMoney customer_id={entity.customer_id} customers={props.customers} amount={entity[field]}/></td>
        case 'status_field':
            return status
        case 'date':
        case 'due_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Date">
                <FormatDate field={field} date={entity[field]}/></td>

        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Status">{status}</td>

        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Customer">{customer.name}</td>
        }

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
