import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { purchaseOrderStatusColors, purchaseOrderStatuses } from '../utils/_consts'
import PurchaseOrderModel from '../models/PurchaseOrderModel'
import { translations } from '../utils/_translations'

export default function PurchaseOrderPresenter (props) {
    const { field, entity } = props

    const objQuoteModel = new PurchaseOrderModel(entity, props.companies)
    const is_late = objQuoteModel.isLate()
    const entity_status = is_late === true ? 100 : entity.status_id

    const status = !entity.deleted_at
        ? <Badge color={purchaseOrderStatusColors[entity_status]}>{purchaseOrderStatuses[entity_status]}</Badge>
        : <Badge className="mr-2" color="warning">{translations.archived}</Badge>

    switch (field) {
        case 'balance':
        case 'total':
        case 'discount_total':
        case 'tax_total':
        case 'sub_total':
        case 'exchange_rate':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label={field}>
                <FormatMoney customer_id={entity.company_id} customers={props.companies} amount={entity[field]}/>
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

        case 'company_id': {
            const index = props.companies.findIndex(company => company.id === entity[field])
            const company = props.companies[index]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Company">{company.name}</td>
        }

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
