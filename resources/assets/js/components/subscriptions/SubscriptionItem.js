import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditSubscription from './edit/EditSubscription'
import { Input } from 'reactstrap'
import SubscriptionPresenter from '../presenters/SubscriptionPresenter'

export default class SubscriptionItem extends Component {
    constructor (props) {
        super(props)

        this.deleteSubscription = this.deleteSubscription.bind(this)
    }

    deleteSubscription (id, archive = false) {
        const url = archive === true ? `/api/subscriptions/archive/${id}` : `/api/subscriptions/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrSubscriptions = [...self.props.subscriptions]
                const index = arrSubscriptions.findIndex(subscription => subscription.id === id)
                arrSubscriptions.splice(index, 1)
                self.props.addUserToState(arrSubscriptions)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { subscriptions, ignoredColumns } = this.props
        if (subscriptions && subscriptions.length) {
            return subscriptions.map(subscription => {
                const restoreButton = subscription.deleted_at
                    ? <RestoreModal id={subscription.id} entities={subscriptions} updateState={this.props.addUserToState}
                        url={`/api/subscriptions/restore/${subscription.id}`}/> : null
                const deleteButton = !subscription.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteSubscription} id={subscription.id}/> : null
                const archiveButton = !subscription.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteSubscription} id={subscription.id}/> : null

                const editButton = !subscription.deleted_at ? <EditSubscription
                    subscriptions={subscriptions}
                    subscription={subscription}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(subscription).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <SubscriptionPresenter key={key} toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={subscription}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(subscription.id)
                const selectedRow = this.props.viewId === subscription.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return <tr className={selectedRow} key={subscription.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={subscription.id} type="checkbox"
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
