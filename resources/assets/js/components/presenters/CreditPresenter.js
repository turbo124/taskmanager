import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { consts } from '../common/_consts'
import { translations } from '../common/_icons'

export default function CreditPresenter (props) {
    const colors = {
        [consts.credit_status_draft]: 'secondary',
        [consts.credit_status_sent]: 'primary',
        [consts.credit_status_partial]: 'warning',
        [consts.credit_status_applied]: 'success'
    }

    const statuses = {
        [consts.credit_status_draft]: translations.draft,
        [consts.credit_status_sent]: translations.sent,
        [consts.credit_status_partial]: translations.partial,
        [consts.credit_status_applied]: translations.applied
    }

    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={colors[entity.status_id]}>{statuses[entity.status_id]}</Badge>
        : <Badge className="mr-2" color="warning">Archived</Badge>

    switch (field) {
        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity)} data-label="Status">{status}</td>
        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Customer">{customer.name}</td>
        }
        case 'date':
        case 'due_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}>
                <FormatDate field={field} date={entity[field]}/></td>
        case 'balance':
        case 'total':
        case 'discount_total':
        case 'tax_total':
        case 'sub_total':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}>
                <FormatMoney customer_id={entity.customer_id} customers={props.customers} amount={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
