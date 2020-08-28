import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditGateway from './edit/EditGateway'
import { Input } from 'reactstrap'
import GatewayPresenter from '../presenters/GatewayPresenter'

export default class GatewayItem extends Component {
    constructor (props) {
        super(props)

        this.deleteGateway = this.deleteGateway.bind(this)
    }

    deleteGateway (id, archive = false) {
        const url = archive === true ? `/api/gateways/archive/${id}` : `/api/gateways/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrGateways = [...self.props.gateways]
                const index = arrGateways.findIndex(gateway => gateway.id === id)
                arrGateways.splice(index, 1)
                self.props.addUserToState(arrGateways)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { customer_id, group_id, gateways, ignoredColumns, customers } = this.props
        if (gateways && gateways.length) {
            return gateways.map(gateway => {
                const restoreButton = gateway.deleted_at
                    ? <RestoreModal id={gateway.id} entities={gateways} updateState={this.props.addUserToState}
                        url={`/api/gateways/restore/${gateway.id}`}/> : null
                const deleteButton = !gateway.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteGateway} id={gateway.id}/> : null
                const archiveButton = !gateway.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteGateway} id={gateway.id}/> : null

                const editButton = !gateway.deleted_at ? <EditGateway
                    customer_id={customer_id}
                    group_id={group_id}
                    gateways={gateways}
                    customers={customers}
                    gateway={gateway}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(gateway).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <GatewayPresenter key={key} customers={customers}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={gateway}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(gateway.id)
                const selectedRow = this.props.viewId === gateway.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return <tr className={selectedRow} key={gateway.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={gateway.id} type="checkbox"
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
