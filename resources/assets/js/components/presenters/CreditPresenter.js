import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { creditStatusColors, creditStatuses } from '../common/_consts'
import { translations } from '../common/_translations'

export default function CreditPresenter (props) {
    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={creditStatusColors[entity.status_id]}>{creditStatuses[entity.status_id]}</Badge>
        : <Badge className="mr-2" color="warning">{translations.archived}</Badge>

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
                <FormatMoney customer_id={entity.customer_id} customers={props.customers} amount={entity[field]}/>
            </td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
