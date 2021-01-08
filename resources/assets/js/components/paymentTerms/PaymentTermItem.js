import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditPaymentTerm from './edit/EditPaymentTerm'
import { Input, ListGroupItem } from 'reactstrap'

export default class PaymentTermItem extends Component {
    constructor (props) {
        super(props)

        this.deletePaymentTerm = this.deletePaymentTerm.bind(this)
    }

    deletePaymentTerm (id, archive = false) {
        const url = archive === true ? `/api/payment_terms/archive/${id}` : `/api/payment_terms/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrPaymentTerms = [...self.props.paymentTerms]
                const index = arrPaymentTerms.findIndex(payment_term => payment_term.id === id)
                arrPaymentTerms.splice(index, 1)
                self.props.addUserToState(arrPaymentTerms)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { paymentTerms, ignoredColumns } = this.props
        if (paymentTerms && paymentTerms.length) {
            return paymentTerms.map((payment_term, index) => {
                const restoreButton = payment_term.deleted_at
                    ? <RestoreModal id={payment_term.id} entities={paymentTerms} updateState={this.props.addUserToState}
                        url={`/api/payment_terms/restore/${payment_term.id}`}/> : null
                const deleteButton = !payment_term.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deletePaymentTerm} id={payment_term.id}/> : null
                const archiveButton = !payment_term.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deletePaymentTerm} id={payment_term.id}/> : null

                const editButton = !payment_term.deleted_at ? <EditPaymentTerm
                    payment_terms={paymentTerms}
                    payment_term={payment_term}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(payment_term).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td onClick={() => this.props.toggleViewedEntity(payment_term, payment_term.name)}
                        data-label={key}
                        key={key}>{payment_term[key]}</td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(payment_term.id)
                const selectedRow = this.props.viewId === payment_term.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                const is_mobile = window.innerWidth <= 768

                if (!this.props.show_list) {
                    return <tr className={selectedRow} key={payment_term.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={payment_term.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr>
                }

                return is_mobile ? <div className="d-flex d-inline list-group-item-dark">
                    <div className="list-action">
                        {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={payment_term.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                        {actionMenu}
                    </div>

                    <ListGroupItem
                        onClick={() => this.props.toggleViewedEntity(payment_term, payment_term.name, editButton)}
                        key={index}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1">{payment_term.name}</h5>
                        </div>
                    </ListGroupItem>
                </div> : <div className="d-flex d-inline list-group-item-dark">
                    <div className="list-action">
                        {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={payment_term.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                        {actionMenu}
                    </div>

                    <ListGroupItem
                        onClick={() => this.props.toggleViewedEntity(payment_term, payment_term.name, editButton)}
                        key={index}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1">{payment_term.name}</h5>
                        </div>
                    </ListGroupItem>
                </div>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
