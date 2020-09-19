import React from 'react'
import FormatDate from '../common/FormatDate'

export default function ProjectPresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'is_completed':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}><i className="fa fa-check"/></td>
        case 'due_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} data-label={field}>
                <FormatDate field={field} date={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
