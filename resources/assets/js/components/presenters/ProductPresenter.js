import { Badge } from 'reactstrap'
import React from 'react'
import { translations } from '../common/_icons'

export default function ProductPresenter (props) {
    const { field, entity } = props

    const status = entity.deleted_at ? <Badge className="mr-2" color="warning">{translations.archived}</Badge> : null

    switch (field) {
        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity)} data-label="Status">{status}</td>
        case 'company_id': {
            const index = props.companies.findIndex(company => company.id === entity[field])
            const company = props.companies[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Company">{company.name}</td>
        }
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.id)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
