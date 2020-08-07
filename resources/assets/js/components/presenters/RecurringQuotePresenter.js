import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { consts } from '../common/_consts'
import { translations } from '../common/_translations'

export default function RecurringQuotePresenter (props) {
    const colors = {
        [consts.recurring_quote_status_draft]: 'primary',
        [consts.recurring_quote_status_active]: 'primary',
        [consts.recurring_quote_status_cancelled]: 'danger',
        [consts.recurring_quote_status_pending]: 'primary',
        [consts.recurring_quote_status_completed]: 'success'
    }

    const statuses = {
        [consts.recurring_invoice_status_draft]: translations.draft,
        [consts.recurring_invoice_status_active]: translations.active,
        [consts.recurring_invoice_status_cancelled]: translations.cancelled,
        [consts.recurring_invoice_status_pending]: translations.pending,
        [consts.recurring_invoice_status_completed]: translations.complete
    }

    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={colors[entity.status_id]}>{statuses[entity.status_id]}</Badge>
        : <Badge color="warning">Archived</Badge>

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
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Date"><FormatDate
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
