import React, { Component } from 'react'
import axios from 'axios'
import { Input } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import UpdateRecurringInvoice from './edit/UpdateRecurringInvoice'
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
            return invoices.map((invoice, index) => {
                const restoreButton = invoice.deleted_at
                    ? <RestoreModal id={invoice.id} entities={invoices} updateState={this.props.updateInvoice}
                        url={`/api/recurringInvoice/restore/${invoice.id}`}/> : null

                const archiveButton = !invoice.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteInvoice} id={invoice.id}/> : null

                const deleteButton = !invoice.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteInvoice} id={invoice.id}/> : null

                const editButton = !invoice.deleted_at ? <UpdateRecurringInvoice
                    allInvoices={allInvoices}
                    custom_fields={custom_fields}
                    customers={customers}
                    modal={true}
                    add={false}
                    invoice={invoice}
                    invoice_id={invoice.id}
                    action={this.props.updateInvoice}
                    invoices={invoices}
                /> : null

                const columnList = Object.keys(user).filter(key => {
                    return this.props.ignoredColumns && !this.props.ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(invoice, invoice.number, editButton)}
                        data-label={key}><RecurringInvoicePresenter customers={customers} edit={editButton}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={invoice}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(user.id)
                const selectedRow = this.props.viewId === user.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return (
                    !this.props.show_list ? <tr className={selectedRow} key={user.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={user.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr> :  : <ListGroupItem key={index}
                    onClick={() => this.props.toggleViewedEntity(invoice, invoice.number, editButton)}
                    className="list-group-item-dark list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1"> {<RecurringInvoicePresenter customers={customers} field="customer_id"
                            entity={invoice}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>}</h5>
                        {<RecurringInvoicePresenter customers={customers}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field="balance" entity={invoice} edit={editButton}/>}
                    </div>
                    <div className="d-flex w-100 justify-content-between">
                        <span className="mb-1 text-muted">{invoice.number} . {<RecurringInvoicePresenter
                            field="due_date" entity={invoice} toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>} </span>
                        <span>{<RecurringInvoicePresenter field="status_field" entity={invoice} edit={editButton}
                            toggleViewedEntity={this.props.toggleViewedEntity}/>}</span>
                    </div>
                     {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={task.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                    {actionMenu}
                </ListGroupItem>
                )
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
