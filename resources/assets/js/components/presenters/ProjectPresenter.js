import React from 'react'
import FormatDate from '../common/FormatDate'

export default function ProjectPresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'assigned_to': {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.assigned_to))
            return assigned_user.length ? `${assigned_user[0].first_name} ${assigned_user[0].last_name}` : ''
        }
        case 'user_id': {
            const user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.user_id))
            return `${user[0].first_name} ${user[0].last_name}`
        }
        case 'is_completed':
            return <i className="fa fa-check"/>
        case 'due_date':
        case 'created_at':
            return <FormatDate field={field} date={entity[field]}/>
        default:
            return entity[field]
    }
}
