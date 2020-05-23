import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditPaymentTerm from './EditPaymentTerm'
import { Input } from 'reactstrap'

export default class PaymentTermItem extends Component {
    constructor (props) {
        super(props)

        this.deletePaymentTerm = this.deletePaymentTerm.bind(this)
    }

    deletePaymentTerm (id, archive = false) {
        const url = archive === true ? `/api/paymentTerms /archive/${id}` : `/api/paymentTerms /${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrPaymentTerms = [...self.props.paymentTerms ]
                const index = arrPaymentTerms.findIndex(group => group.id === id)
                arrPaymentTerms.splice(index, 1)
                self.props.addUserToState(arrPaymentTerms)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { paymentTerms , ignoredColumns } = this.props
        if (paymentTerms  && paymentTerms .length) {
            return paymentTerms .map(group => {
                const restoreButton = group.deleted_at
                    ? <RestoreModal id={group.id} entities={paymentTerms } updateState={this.props.addUserToState}
                        url={`/api/paymentTerms/restore/${group.id}`}/> : null
                const deleteButton = !group.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deletePaymentTerm} id={group.id}/> : null
                const archiveButton = !group.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deletePaymentTerm} id={group.id}/> : null

                const editButton = !group.deleted_at ? <EditPaymentTerm
                    paymentTerms ={paymentTerms }
                    group={group}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(group).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td onClick={() => this.props.toggleViewedEntity(group, group.name)} data-label={key}
                        key={key}>{group[key]}</td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'

                return <tr key={group.id}>
                    <td>
                        <Input className={checkboxClass} value={group.id} type="checkbox" onChange={this.props.onChangeBulk}/>
                        <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                            restore={restoreButton}/>
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
