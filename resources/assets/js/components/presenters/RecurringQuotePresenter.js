import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { frequencyOptions, recurringQuoteStatusColors, recurringQuoteStatuses } from '../utils/_consts'
import { translations } from '../utils/_translations'

export default function RecurringQuotePresenter (props) {
    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge
            color={recurringQuoteStatusColors[entity.status_id]}>{recurringQuoteStatuses[entity.status_id]}</Badge>
        : <Badge color="warning">{translations.archived}</Badge>

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
        case 'number_of_occurrances':
            return <td>{entity.is_never_ending ? translations.never_ending : entity.number_of_occurrances}</td>
        case 'frequency':
            return <td>{translations[frequencyOptions[entity.frequency]]}</td>
        case 'total':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Total">{
                    <FormatMoney
                        customers={props.customers} customer_id={entity.customer_id}
                        amount={entity.total}/>}</td>
        case 'date':
        case 'due_date':
        case 'start_date':
        case 'created_at':
        case 'last_sent_date':
        case 'date_to_send':
        case 'expiry_date': {
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}><FormatDate
                    field={field} date={entity[field]}/></td>
        }

        case 'status_field':
            return status
        case 'status_id':
            return <td onClick={() => this.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Status">{status}</td>

        case 'auto_billing_enabled':
            return <td onClick={() => this.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label={field}>{entity[field] === true ? translations.yes : translations.no}</td>

        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Customer">{customer.name}</td>
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
