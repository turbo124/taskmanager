import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { consts } from '../common/_consts'
import { translations } from '../common/_icons'

export default function CustomerPresenter (props) {
    const colors = {
        [consts.credit_status_draft]: 'secondary',
        [consts.credit_status_sent]: 'primary',
        [consts.credit_status_partial]: 'warning',
        [consts.credit_status_applied]: 'success'
    }

    const statuses = {
        [consts.credit_status_draft]: translations.draft,
        [consts.credit_status_sent]: translations.sent,
        [consts.credit_status_partial]: translations.partial,
        [consts.credit_status_applied]: translations.applied
    }

    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={colors[entity.status_id]}>{statuses[entity.status_id]}</Badge>
        : <Badge className="mr-2" color="warning">Archived</Badge>

    switch (field) {
        case 'id':
            return <td data-label="Name"><Avatar name={entity.name}/></td>
        case 'date':
        case 'due_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}>
                <FormatDate field={field} date={entity[field]}/></td>
        case 'balance':
            const text_color = entity[field] <= 0 ? 'text-danger' : 'text-success'
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} data-label={field}>
                <FormatMoney customer_id={entity.customer_id} className={text_color} customers={props.customers} amount={entity[field]}/></td
        case 'paid_to_date':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} data-label={field}>
                <FormatMoney customer_id={entity.id} customers={props.customers} amount={entity[field]}/></td>
        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}       

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.name)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
