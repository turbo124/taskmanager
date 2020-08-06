import React, { Component } from 'react'
import axios from 'axios'
import {
    Input
} from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import UpdateRecurringInvoice from './UpdateRecurringInvoice'
import RecurringInvoicePresenter from '../presenters/RecurringInvoicePresenter'

export default class RecurringInvoiceItem extends Component {
    constructor (props) {
        super(props)

        this.deleteInvoice = this.deleteInvoice.bind(this)
    }

    deleteInvoice (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/recurring-invoice/archive/${id}` : `/api/recurring-invoice/${id}`
        axios.delete(url)
            .then(function (response) {
                const arrInvoices = [...self.props.invoices]
                const index = arrInvoices.findIndex(payment => payment.id === id)
                arrInvoices.splice(index, 1)
                self.props.updateInvoice(arrInvoices)
            })
            .catch(function (error) {
                self.setState(
                    {
                        error: error.response.data
                    }
                )
            })
    }

    render () {
        const { invoices, custom_fields, customers, allInvoices } = this.props
        if (invoices && invoices.length && customers.length) {
            return invoices.map(user => {
                const restoreButton = user.deleted_at
                    ? <RestoreModal id={user.id} entities={invoices} updateState={this.props.updateInvoice}
                        url={`/api/recurringInvoice/restore/${user.id}`}/> : null

                const archiveButton = !user.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteInvoice} id={user.id}/> : null

                const deleteButton = !user.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteInvoice} id={user.id}/> : null

                const editButton = !user.deleted_at ? <UpdateRecurringInvoice
                    allInvoices={allInvoices}
                    custom_fields={custom_fields}
                    customers={customers}
                    modal={true}
                    add={false}
                    invoice={user}
                    invoice_id={user.id}
                    action={this.props.updateInvoice}
                    invoices={invoices}
                /> : null

                const columnList = Object.keys(user).filter(key => {
                    return this.props.ignoredColumns && !this.props.ignoredColumns.includes(key)
                }).map(key => {
                    return <RecurringInvoicePresenter key={key} customers={customers} toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={user}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(user.id)
                const selectedRow = this.props.viewId === user.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                    restore={restoreButton}/> : null

                return (
                    <tr className={selectedRow} key={user.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={user.id} type="checkbox" onChange={this.props.onChangeBulk} />
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr>
                )
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
