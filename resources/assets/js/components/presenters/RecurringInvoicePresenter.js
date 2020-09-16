import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { recurringInvoiceStatusColors, recurringInvoiceStatuses } from '../utils/_consts'
import { translations } from '../utils/_translations'

export default function RecurringInvoicePresenter (props) {
    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge
            color={recurringInvoiceStatusColors[entity.status_id]}>{recurringInvoiceStatuses[entity.status_id]}</Badge>
        : <Badge className="mr-2" color="warning">{translations.archived}</Badge>

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
