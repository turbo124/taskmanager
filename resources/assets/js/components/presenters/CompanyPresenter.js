import React from 'react'

export default function CompanyPresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'logo':
            return <td><img style={{ width: '100px' }} src={entity.logo}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
