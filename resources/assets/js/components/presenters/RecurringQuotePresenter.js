import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { recurringQuoteStatusColors, recurringQuoteStatuses } from '../common/_consts'
import { translations } from '../common/_translations'

export default function RecurringQuotePresenter (props) {
    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge
            color={recurringQuoteStatusColors[entity.status_id]}>{recurringQuoteStatuses[entity.status_id]}</Badge>
        : <Badge color="warning">{translations.archived}</Badge>

    switch (field) {
        case 'total':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Total">{
                <FormatMoney
                    customers={props.customers} customer_id={entity.customer_id}
                    amount={entity.total}/>}</td>
        case 'date':
        case 'due_date':
        case 'start_date':
        case 'end_date': {
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}><FormatDate
                field={field} date={entity[field]}/></td>
        }

        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => this.toggleViewedEntity(entity, entity.number)}
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
