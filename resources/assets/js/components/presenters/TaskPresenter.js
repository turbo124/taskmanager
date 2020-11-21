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
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>{assigned_user.length ? `${assigned_user[0].first_name} ${assigned_user[0].last_name}` : ''}</td>
        }
        case 'user_id': {
            const user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(props.entity.user_id))
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>{`${user[0].first_name} ${user[0].last_name}`}</td>
        }
        case 'status_name':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Status">{status}</td>
        case 'status_field':
            return status
        case 'frequency':
            return <td>{translations[frequencyOptions[entity.frequency]]}</td>
        case 'due_date':
        case 'start_date':
        case 'created_at':
            return <td><FormatDate date={entity[field]}/></td>
        case 'title':
            return <td data-label="Title"><ViewTask custom_fields={props.custom_fields}
                project_id={props.task.project_id}
                users={props.users}
                customers={props.customers}
                task_type={1}
                allTasks={props.tasks}
                action={props.action}
                task={props.task}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.title, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
