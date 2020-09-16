import { Badge } from 'reactstrap'
import React from 'react'
import { translations } from '../utils/_translations'

export default function ProductPresenter (props) {
    const { field, entity } = props

    const status = entity.deleted_at ? <Badge className="mr-2" color="warning">{translations.archived}</Badge> : null

    switch (field) {
        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)}
                data-label="Status">{status}</td>
        case 'company_id': {
            const index = props.companies.findIndex(company => company.id === entity[field])
            const company = props.companies[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)}
                data-label="Company">{company.name}</td>
        }
        case 'is_featured': {
            const icon = parseInt(entity.is_featured) === 1 ? 'fa-check' : 'fa-times-circle'
            const icon_class = parseInt(entity.is_featured) === 1 ? 'text-success' : 'text-danger'
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}><i className={`fa ${icon} ${icon_class}`}/></td>
        }
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
