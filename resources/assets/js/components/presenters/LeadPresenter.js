import React from 'react'
import { Badge } from 'reactstrap'
import { translations } from '../utils/_translations'
import FormatMoney from '../common/FormatMoney'

export function getDefaultTableFields () {
    return [
        'number',
        'first_name',
        'last_name',
        'status_name',
        'email',
        'phone',
        'status_id'
    ]
}

export default function LeadPresenter (props) {
    const { field, entity } = props

    const status = (entity.deleted_at) ? <Badge color="warning">{translations.archived}</Badge>
        : <Badge color="primary">{entity.status_name}</Badge>

    switch (field) {
        case 'assigned_to': {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.assigned_to))
            return assigned_user.length ? `${assigned_user[0].first_name} ${assigned_user[0].last_name}` : ''
        }
        case 'valued_at':
            return <FormatMoney amount={entity[field]}/>
        case 'status_name':
        case 'status_field':
            return status
        case 'user_id': {
            const user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.user_id))
            return `${user[0].first_name} ${user[0].last_name}`
        }
        case 'name': {
            return `${entity.first_name} ${entity.last_name}`
        }
        default:
            return entity[field]
    }
}
