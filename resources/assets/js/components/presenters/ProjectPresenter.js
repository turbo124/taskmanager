import React from 'react'
import FormatDate from '../common/FormatDate'

export default function ProjectPresenter (props) {
    const { field, entity } = props

    switch (field) {
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
        case 'is_completed':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}><i className="fa fa-check"/></td>
        case 'due_date':
        case 'created_at':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>
                <FormatDate field={field} date={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
