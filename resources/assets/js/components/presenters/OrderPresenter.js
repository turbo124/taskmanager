import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { orderStatusColors, orderStatuses } from '../utils/_consts'
import OrderModel from '../models/OrderModel'
import { translations } from '../utils/_translations'

export default function OrderPresenter (props) {
    const { field, entity } = props

    const objOrderModel = new OrderModel(entity, props.customers)
    const is_late = objOrderModel.isLate()
    const entity_status = is_late === true ? '-1' : entity.status_id

    const status = !entity.deleted_at
        ? <Badge color={orderStatusColors[entity_status]}>{orderStatuses[entity_status]}</Badge>
        : <Badge className="mr-2" color="warning">{translations.archived}</Badge>

    switch (field) {
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
        case 'status_field':
            return status
        case 'date':
        case 'due_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} data-label="Date">
                <FormatDate field={field} date={entity[field]}/></td>

        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Status">{status}</td>

        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Customer">{customer.name}</td>
        }

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
