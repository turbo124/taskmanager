import axios from 'axios'
import React, { Component } from 'react'
import { Input } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditDeal from './edit/EditDeal'
import DealPresenter from '../presenters/DealPresenter'

export default class DealItem extends Component {
    constructor (props) {
        super(props)

        this.deleteDeal = this.deleteDeal.bind(this)
    }

    deleteDeal (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/deals/archive/${id}` : `/api/deals/${id}`

        axios.delete(url)
            .then(function (response) {
                const arrDeals = [...self.props.deals]
                const index = arrDeals.findIndex(deal => deal.id === id)
                arrDeals.splice(index, 1)
                self.props.addUserToState(arrDeals)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { deals, custom_fields, users, ignoredColumns } = this.props
        if (deals && deals.length && users.length) {
            return deals.map(deal => {
                const restoreButton = deal.deleted_at && !deal.is_deleted
                    ? <RestoreModal id={deal.id} entities={deals} updateState={this.props.addUserToState}
                        url={`/api/deals/restore/${deal.id}`}/> : null
                const archiveButton = !deal.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteDeal} id={deal.id}/> : null
                const deleteButton = !deal.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteDeal} id={deal.id}/> : null
                const editButton = !deal.deleted_at ? <EditDeal
                    modal={true}
                    listView={true}
                    custom_fields={custom_fields}
                    users={users}
                    deal={deal}
                    deals={deals}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(deal).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <DealPresenter key={key} toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={deal} custom_fields={custom_fields}
                        edit={editButton}
                        users={users}
                        customers={this.props.customers}
                        deals={deals}
                        action={this.props.action}
                        deal={deal}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(deal.id)
                const selectedRow = this.props.viewId === deal.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return <tr className={selectedRow} key={deal.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={deal.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        {actionMenu}
                    </td>
                    {columnList}
                </tr>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
