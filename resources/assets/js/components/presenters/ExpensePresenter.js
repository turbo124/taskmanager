import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'

export default function ExpensePresenter (props) {
    const colors = {
        Pending: 'secondary',
        Voided: 'danger',
        Failed: 'danger',
        Completed: 'success',
        'Partially Refunded': 'dark',
        Refunded: 'danger'
    }

    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={colors[entity.status]}>{entity.status}</Badge>
        : <Badge color="warning">Archived</Badge>

    const paymentInvoices = entity.invoices && Object.keys(entity.invoices).length > 0 ? Array.prototype.map.call(entity.invoices, s => s.number).toString() : null

    switch (field) {
        case 'amount':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)} data-label="Total">{<FormatMoney
                customers={props.customers} customer_id={entity.customer_id}
                amount={entity.amount}/>}</td>
        case 'expense_date':
        case 'payment_date': {
            return <FormatDate field={field} date={entity[field]} />
        }

        case 'status':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.transaction_reference)}
                data-label="Status">{status}</td>

        case 'customer_id': {
            const customerIndex = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[customerIndex]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Customer">{customer.name}</td>
        }

        case 'company_id': {
            const companyIndex = props.companies.findIndex(company => company.id === entity[field])
            const company = props.companies[companyIndex]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number)}
                data-label="Company">{company.name}</td>
        }

        case 'invoices':
            return <td data-label="Invoices">{paymentInvoices}</td>

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.transaction_reference)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
