import { Badge } from 'reactstrap'
import React from 'react'
import { consts } from '../common/_consts'
import { translations } from '../common/_icons'

export default function CasePresenter (props) {
    const colors = {
        [consts.case_status_draft]: 'secondary'
    }

    const statuses = {
        [consts.case_status_draft]: translations.draft
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
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
