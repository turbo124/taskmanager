import { Badge } from 'reactstrap'
import React from 'react'
import {
    caseLinkTypes,
    casePriorities,
    casePriorityColors,
    caseStatusColors,
    caseStatuses,
    quoteStatusColors, quoteStatuses
} from '../utils/_consts'
import { translations } from '../utils/_translations'
import FormatDate from '../common/FormatDate'

export default function CasePresenter (props) {
    const { field, entity } = props

    const status = (entity.deleted_at && !entity.is_deleted) ? (<Badge className="mr-2"
        color="warning">{translations.archived}</Badge>) : ((entity.deleted_at && entity.is_deleted) ? (
        <Badge className="mr-2" color="danger">{translations.deleted}</Badge>) : (
        <Badge color={caseStatusColors[entity.status_id]}>{caseStatuses[entity.status_id]}</Badge>))

    const priority = <Badge
        color={casePriorityColors[entity.priority_id]}>{casePriorities[entity.priority_id]}</Badge>

    switch (field) {
        case 'status_field':
            return status
        case 'priority_field':
            return priority
        case 'link_type':
            return <td>{entity.link_type.toString().length ? caseLinkTypes[entity.link_type] : ''}</td>

        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Status">{status}</td>
        case 'priority_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Priority">{priority}</td>
        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Customer">{customer.name}</td>
        }
        case 'assigned_to': {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.assigned_to))
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>{assigned_user.length ? `${assigned_user[0].first_name} ${assigned_user[0].last_name}` : ''}</td>
        }
        case 'user_id': {
            const user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.user_id))
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>{`${user[0].first_name} ${user[0].last_name}`}</td>
        }
        case 'date':
        case 'due_date':
        case 'created_at':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} data-label="Date">
                <FormatDate field={field} date={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
