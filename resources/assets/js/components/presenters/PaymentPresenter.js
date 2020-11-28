import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import PaymentModel from '../models/PaymentModel'
import { paymentStatusColors, paymentStatuses } from '../utils/_consts'
import { translations } from '../utils/_translations'

export default function PaymentPresenter (props) {
    const { field, entity } = props

    const paymentModel = new PaymentModel(entity.invoices, entity, entity.credits)

    const status = (entity.deleted_at && !entity.is_deleted) ? (<Badge className="mr-2"
        color="warning">{translations.archived}</Badge>) : ((entity.deleted_at && entity.is_deleted) ? (
        <Badge className="mr-2" color="danger">{translations.deleted}</Badge>) : (
        <Badge color={paymentStatusColors[entity.status_id]}>{paymentStatuses[entity.status_id]}</Badge>))

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
        case 'amount':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Total">{
                    <FormatMoney
                        customers={props.customers} customer_id={entity.customer_id}
                        amount={entity.amount}/>}</td>
        case 'applied':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Applied">{
                    <FormatMoney
                        customers={props.customers} customer_id={entity.customer_id}
                        amount={entity.applied}/>}</td>
        case 'date':
        case 'created_at': {
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} data-label="Date">
                <FormatDate
                    field={field} date={entity[field]}/></td>
        }

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

        case 'invoices': {
            const invoices = paymentModel.paymentableInvoices
            const paymentInvoices = invoices && invoices.length > 0 ? Array.prototype.map.call(invoices, s => s.number).toString() : null

            return <td data-label="Invoices">{paymentInvoices}</td>
        }

        case 'credits': {
            const credits = paymentModel.paymentableCredits
            const paymentCredits = credits && credits.length > 0 ? Array.prototype.map.call(credits, s => s.number).toString() : null

            return <td data-label="Credits">{paymentCredits}</td>
        }

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
