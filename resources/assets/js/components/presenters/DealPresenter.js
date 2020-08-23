
import React from 'react'
import ViewTask from '../tasks/ViewTask'

export default function TaskPresenter (props) {
    const { field, entity } = props

    switch (field) {
       default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.title)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
