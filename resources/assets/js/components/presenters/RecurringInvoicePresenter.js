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
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Total">{
                    <FormatMoney
                        customers={props.customers} customer_id={entity.customer_id}
                        amount={entity.total}/>}</td>
        case 'date':
        case 'due_date':
        case 'created_at':
        case 'start_date':
        case 'last_sent_date':
        case 'date_to_send':
        case 'expiry_date': {
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}><FormatDate
                    field={field} date={entity[field]}/></td>
        }

        case 'auto_billing_enabled':
            return <td onClick={() => this.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>{entity[field] === true ? translations.yes : translations.no}</td>

        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Status">{status}</td>

        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Customer">{customer.name}</td>
        }

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
