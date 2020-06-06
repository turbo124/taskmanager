import { Badge } from 'reactstrap'
import React from 'react'
import { consts } from '../common/_consts'
import { translations } from '../common/_icons'
import FormatDate from '../common/FormatDate'

export default function CasePresenter (props) {
    const status_colors = {
        [consts.case_status_draft]: 'secondary'
    }

    const priority_colors = {
        [consts.low_priority]: 'draft',
        [consts.medium_priority]: 'warning',
        [consts.low_priority]: 'danger'
    }

    const statuses = {
        [consts.case_status_draft]: translations.draft
    }

    const priorities = {
        [consts.low_priority]: translations.low,
        [consts.medium_priority]: translations.medium,
        [consts.high_priority]: translations.high
    }

    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={status_colors[entity.status_id]}>{statuses[entity.status_id]}</Badge>
        : <Badge className="mr-2" color="warning">Archived</Badge>

    switch (field) {
        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity)} data-label="Status">{status}</td>
        case 'priority_id':
            return <td onClick={() => props.toggleViewedEntity(entity)} data-label="Priority"><Badge
                color={priority_colors[entity.priority_id]}>{priorities[entity.priority_id]}</Badge></td>
        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Customer">{customer.name}</td>
        }
        case 'date':
        case 'due_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Date">
                <FormatDate field={field} date={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
