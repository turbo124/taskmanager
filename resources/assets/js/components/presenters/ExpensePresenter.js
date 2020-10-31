import { Badge } from 'reactstrap'
import React from 'react'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { expenseStatusColors, expenseStatuses } from '../utils/_consts'
import { translations } from '../utils/_translations'

export default function ExpensePresenter (props) {
    const { field, entity } = props

    const status = !entity.deleted_at
        ? <Badge color={expenseStatusColors[entity.status_id]}>{expenseStatuses[entity.status_id]}</Badge>
        : <Badge color="warning">{translations.archived}</Badge>

    const paymentInvoices = entity.invoices && Object.keys(entity.invoices).length > 0 ? Array.prototype.map.call(entity.invoices, s => s.number).toString() : null

    switch (field) {
        case 'frequency':
            return <td>{translations[frequencyOptions[entity.frequency]]}</td>
        case 'amount':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Total">{
                    <FormatMoney
                        customers={props.customers} customer_id={entity.customer_id}
                        amount={entity.amount}/>}</td>
        case 'status_field':
            return status
        case 'date':
        case 'created_at':
        case 'payment_date': {
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} data-label="Date">
                <FormatDate
                    field={field} date={entity[field]}/></td>
        }

        case 'status':
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Status">{status}</td>

        case 'customer_id': {
            const customerIndex = props.customers.findIndex(customer => customer.id === entity[field])
            const customer = props.customers[customerIndex]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Customer">{customer.name}</td>
        }

        case 'company_id': {
            const companyIndex = props.companies.findIndex(company => company.id === entity[field])
            const company = props.companies[companyIndex]
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)}
                data-label="Company">{company.name}</td>
        }

        case 'invoices':
            return <td data-label="Invoices">{paymentInvoices}</td>

        default:
            return <td onClick={() => props.toggleViewedEntity(entity, entity.number, props.edit)} key={field}
                data-label={field}>{entity[field]}</td>
    }
}
