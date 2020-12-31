import { Badge } from 'reactstrap'
import React from 'react'
import ViewTask from '../tasks/ViewTask'
import FormatDate from '../common/FormatDate'
import { translations } from '../utils/_translations'
import { frequencyOptions } from '../utils/_consts'

export default function TaskPresenter (props) {
    const { field, entity } = props

    const status = (entity.deleted_at)
        ? (<Badge color="warning">{tra.archived}</Badge>)
        : ((entity.invoice_id) ? (<Badge color="success">{translations.invoiced}</Badge>)
            : (<Badge color="primary">{entity.status_name}</Badge>))

    switch (field) {
        case 'assigned_to': {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.assigned_to))
            return assigned_user.length ? `${assigned_user[0].first_name} ${assigned_user[0].last_name}` : ''
        }
        case 'user_id': {
            const user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.user_id))
            return `${user[0].first_name} ${user[0].last_name}`
        }
        case 'status_name':
            return status
        case 'status_field':
            return status
        case 'frequency':
            return translations[frequencyOptions[entity.frequency]]
        case 'due_date':
        case 'start_date':
        case 'created_at':
            return <FormatDate date={entity[field]}/>
        case 'customer_id': {
            if (!entity[field]) {
                return ''
            }

            const customerIndex = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[customerIndex]
            return customer.name
        }
        case 'title':
            return <ViewTask custom_fields={props.custom_fields}
                project_id={props.task.project_id}
                users={props.users}
                customers={props.customers}
                task_type={1}
                allTasks={props.tasks}
                action={props.action}
                task={props.task}/>
        default:
            return entity[field]
    }
}
