import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { creditStatusColors, creditStatuses } from '../utils/_consts'
import { translations } from '../utils/_translations'

export default function CreditPresenter (props) {
    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={creditStatusColors[entity.status_id]}>{creditStatuses[entity.status_id]}</Badge>
        : <Badge className="mr-2" color="warning">{translations.archived}</Badge>

    switch (field) {
        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Status">{status}</td>
        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Customer">{customer.name}</td>
        }
        case 'date':
        case 'due_date':
        case 'created_at':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>
                <FormatDate field={field} date={entity[field]}/></td>
        case 'balance':
        case 'total':
        case 'discount_total':
        case 'tax_total':
        case 'sub_total':
        case 'exchange_rate':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>
                <FormatMoney customer_id={entity.customer_id} customers={props.customers} amount={entity[field]}/>
            </td>
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

        case 'currency_id': {
            const currency = JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === parseInt(props.entity.currency_id))
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>{currency.length ? currency[0].iso_code : ''}</td>
        }
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
