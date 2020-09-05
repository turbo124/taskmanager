import React, { Component } from 'react'
import axios from 'axios'
import { Input } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditPurchaseOrder from './edit/EditPurchaseOrder'
import QuotePresenter from '../presenters/QuotePresenter'

export default class PurchaseOrderItem extends Component {
    constructor (props) {
        super(props)

        this.deletePurchaseOrder = this.deletePurchaseOrder.bind(this)
    }

    deletePurchaseOrder (id, archive = false) {
        const url = archive === true ? `/api/purchase_order/archive/${id}` : `/api/purchase_order/${id}`
        const self = this
        axios.delete(url).then(function (response) {
            const arrQuotes = [...self.props.purchase_orders]
            const index = arrQuotes.findIndex(payment => payment.id === id)
            arrQuotes.splice(index, 1)
            self.props.updateInvoice(arrQuotes)
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
        const { purchase_orders, custom_fields, companies } = this.props
        if (purchase_orders && purchase_orders.length && companies.length) {
            return purchase_orders.map(user => {
                const restoreButton = user.deleted_at
                    ? <RestoreModal id={user.id} entities={quotes} updateState={this.props.updateInvoice}
                        url={`/api/purchase_order/restore/${user.id}`}/> : null

                const deleteButton = !user.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deletePurchaseOrder} id={user.id}/> : null

                const archiveButton = !user.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deletePurchaseOrder} id={user.id}/> : null

                const editButton = !user.deleted_at ? <EditPurchaseOrder
                    custom_fields={custom_fields}
                    companies={companies}
                    modal={true}
                    add={false}
                    invoice={user}
                    invoice_id={user.id}
                    action={this.props.updateInvoice}
                    invoices={purchase_orders}
                /> : null

                const columnList = Object.keys(user).filter(key => {
                    return this.props.ignoredColumns && !this.props.ignoredColumns.includes(key)
                }).map(key => {
                    return <PurchaseOrderPresenter key={key} companies={companies}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={user}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(user.id)
                const selectedRow = this.props.viewId === user.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return (
                    <tr className={selectedRow} key={user.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={user.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
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
