import React from 'react'
import FormatDate from '../common/FormatDate'

export default function TaskPresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'due_date':
            return <td><FormatDate date={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.title, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
