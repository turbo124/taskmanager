import React, { Component } from 'react'
import axios from 'axios'
import { Input } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditPurchaseOrder from './edit/EditPurchaseOrder'
import PurchaseOrderPresenter from '../presenters/PurchaseOrderPresenter'

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
            return purchase_orders.map(purchase_order => {
                const restoreButton = purchase_order.deleted_at
                    ? <RestoreModal id={purchase_order.id} entities={purchase_orders}
                        updateState={this.props.updateInvoice}
                        url={`/api/purchase_order/restore/${purchase_order.id}`}/> : null

                const deleteButton = !purchase_order.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deletePurchaseOrder}
                        id={purchase_order.id}/> : null

                const archiveButton = !purchase_order.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deletePurchaseOrder}
                        id={purchase_order.id}/> : null

                const editButton = !purchase_order.deleted_at ? <EditPurchaseOrder
                    custom_fields={custom_fields}
                    companies={companies}
                    modal={true}
                    add={false}
                    invoice={purchase_order}
                    invoice_id={purchase_order.id}
                    action={this.props.updateInvoice}
                    invoices={purchase_orders}
                /> : null

                const columnList = Object.keys(purchase_order).filter(key => {
                    return this.props.ignoredColumns && !this.props.ignoredColumns.includes(key)
                }).map(key => {
                    return <PurchaseOrderPresenter key={key} companies={companies} edit={editButton}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={purchase_order}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(purchase_order.id)
                const selectedRow = this.props.viewId === purchase_order.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return (
                    <tr className={selectedRow} key={purchase_order.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={purchase_order.id}
                                type="checkbox"
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
