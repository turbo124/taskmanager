import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import Avatar from '../common/Avatar'

export default function CustomerPresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'id':
            return <td data-label="Name"><Avatar name={entity.name}/></td>
        case 'date':
        case 'due_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} data-label={field}>
                <FormatDate field={field} date={entity[field]}/></td>
        case 'balance':
            const text_color = entity[field] <= 0 ? 'text-danger' : 'text-success'
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} data-label={field}>
                <FormatMoney customer_id={entity.customer_id} className={text_color} customers={props.customers}
                    amount={entity[field]}/></td>
        case 'paid_to_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} data-label={field}>
                <FormatMoney customer_id={entity.id} customers={props.customers} amount={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
