import React, { Component } from 'react'
import axios from 'axios'
import { Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditCustomer from './edit/EditCustomer'
import CustomerPresenter from '../presenters/CustomerPresenter'

export default class CustomerItem extends Component {
    constructor (props) {
        super(props)

        this.deleteCustomer = this.deleteCustomer.bind(this)
    }

    deleteCustomer (id, archive = false) {
        const url = archive === true ? `/api/customers/archive/${id}` : `/api/customers/${id}`
        axios.delete(url).then(data => {
            const arrCustomers = [...this.props.customers]
            const index = arrCustomers.findIndex(customer => customer.id === id)
            arrCustomers.splice(index, 1)
            this.props.updateCustomers(arrCustomers)
        })
    }

    render () {
        const { customers, custom_fields, ignoredColumns } = this.props
        if (customers && customers.length) {
           return customers.map((customer, index) => {
                const restoreButton = customer.deleted_at
                    ? <RestoreModal id={customer.id} entities={customers} updateState={this.props.updateCustomers}
                        url={`/api/customers/restore/${customer.id}`}/> : null
                const archiveButton = !customer.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteCustomer} id={customer.id}/> : null
                const deleteButton = !customer.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteCustomer} id={customer.id}/> : null
                const editButton = !customer.deleted_at && customers.length ? <EditCustomer
                    custom_fields={custom_fields}
                    customer={customer}
                    action={this.props.updateCustomers}
                    customers={customers}
                    modal={true}
                /> : null

                const columnList = Object.keys(customer).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(customer, customer.name, editButton)}
                        data-label={key}><CustomerPresenter toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={customer} edit={editButton}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(customer.id)
                const selectedRow = this.props.viewId === customer.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return (
                    !this.props.show_list ? <tr className={selectedRow} key={customer.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={customer.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr> : <ListGroupItem onClick={() => this.props.toggleViewedEntity(quote, quote.number, editButton)}
                    key={index}
                    className="list-group-item-dark list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">{<CustomerPresenter customers={customers} field="name"
                            entity={customer}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>}</h5>
                        {<CustomerPresenter customers={customers}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field="balance" entity={customer} edit={editButton}/>}
                    </div>
                   
                    {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={customer.id} type="checkbox"
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
