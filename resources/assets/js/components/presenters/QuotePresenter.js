import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { quoteStatusColors, quoteStatuses } from '../common/_consts'
import QuoteModel from '../models/QuoteModel'
import { translations } from '../common/_translations'

export default function QuotePresenter (props) {

    const { field, entity } = props

    const objQuoteModel = new QuoteModel(entity, props.customers)
    const is_late = objQuoteModel.isLate()
    const entity_status = is_late === true ? 100 : entity.status_id

    const status = !entity.deleted_at
        ? <Badge color={quoteStatusColors[entity_status]}>{quoteStatuses[entity_status]}</Badge>
        : <Badge className="mr-2" color="warning">{translations.archived}</Badge>

    switch (field) {
        case 'balance':
        case 'total':
        case 'discount_total':
        case 'tax_total':
        case 'sub_total':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}>
                <FormatMoney customer_id={entity.customer_id} customers={props.customers} amount={entity[field]}/>
            </td>
        case 'status_field':
            return status
        case 'date':
        case 'due_date': {
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}><FormatDate
                field={field} date={entity[field]}/></td>
        }

        case 'status_id':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Status">{status}</td>

        case 'customer_id': {
            const index = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Customer">{customer.name}</td>
        }

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
