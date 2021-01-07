import React from 'react'

export function getDefaultTableFields () {
    return [
        'name',
        'description',
        'task_count'
    ]
}

export default function TaskStatusPresenter (props) {
    const { field, entity } = props

    switch (field) {
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
