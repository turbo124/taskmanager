import React from 'react'
import FormatDate from '../common/FormatDate'
import { Badge } from 'reactstrap'
import { translations } from '../utils/_translations'

export function getDefaultTableFields () {
    return [
        'number',
        'name',
        'description',
        'due_date',
        'status_name'
    ]
}

export default function DealPresenter (props) {
    const { field, entity } = props

    const status = (entity.deleted_at) ? <Badge color="warning">{translations.archived}</Badge>
        : <Badge color="primary">{entity.status_name}</Badge>

    switch (field) {
        case 'assigned_to': {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.assigned_to))
            return assigned_user.length ? `${assigned_user[0].first_name} ${assigned_user[0].last_name}` : ''
        }
        case 'status_name':
        case 'status_field':
            return status
        case 'user_id': {
            const user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.user_id))
            return `${user[0].first_name} ${user[0].last_name}`
        }
        case 'due_date':
            return <FormatDate date={entity[field]}/>
        case 'customer_id': {
            if (!entity[field]) {
                return ''
            }

            const customerIndex = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[customerIndex]
            return customer.name
        }
        default:
            return entity[field]
    }
}
