import React, { Component } from 'react'
import axios from 'axios'
import { Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditOrder from './edit/EditOrder'
import OrderPresenter from '../presenters/OrderPresenter'

export default class OrderItem extends Component {
    constructor (props) {
        super(props)

        this.deleteOrder = this.deleteOrder.bind(this)
    }

    deleteOrder (id, archive = false) {
        const url = archive === true ? `/api/order/archive/${id}` : `/api/order/${id}`
        const self = this
        axios.delete(url).then(function (response) {
            const arrQuotes = [...self.props.orders]
            const index = arrQuotes.findIndex(payment => payment.id === id)
            arrQuotes.splice(index, 1)
            self.props.updateOrder(arrQuotes)
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
        const { orders, customers, custom_fields } = this.props
        if (orders && orders.length && customers.length) {
            return orders.map(order => {
                const restoreButton = order.deleted_at && !order.is_deleted
                    ? <RestoreModal id={order.id} entities={orders} updateState={this.props.updateOrder}
                        url={`/api/order/restore/${order.id}`}/> : null

                const archiveButton = !order.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteOrder} id={order.id}/> : null

                const deleteButton = !order.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteOrder} id={order.id}/> : null

                const editButton = !order.deleted_at ? <EditOrder
                    custom_fields={custom_fields}
                    customers={customers}
                    modal={true}
                    add={false}
                    order={order}
                    order_id={order.id}
                    action={this.props.updateOrder}
                    orders={orders}
                /> : null

                const columnList = Object.keys(order).filter(key => {
                    return this.props.ignoredColumns && !this.props.ignoredColumns.includes(key)
                }).map(key => {
                    return <OrderPresenter key={key} customers={customers}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={order} edit={editButton}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(order.id)
                const selectedRow = this.props.viewId === order.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return this.props.show_list ? (
                    <tr className={selectedRow} key={order.id}>
                        <td>
                            {!!this.props.onChangeBulk && 
                            <Input checked={isChecked} className={checkboxClass} value={order.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            }
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr> 
                ) : <ListGroupItem key={invoice.id}
                    className="list-group-item-dark list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1"> {<OrderPresenter field="customer" entity={invoice} edit={editButton}/>}</h5>
                        {<OrderPresenter key={key} customers={customers}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field="balance" entity={order} edit={editButton}/>}
                    </div>
                    <div className="d-flex w-100 justify-content-between">
                        <span className="mb-1 text-muted">{actionMenu} {order.number} . {<OrderPresenter field="due_date" entity={order} edit={editButton}/>} </span>
                        <span>{<OrderPresenter field="status_field" entity={order}/>}</span>
                    </div>
                </ListGroupItem>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
