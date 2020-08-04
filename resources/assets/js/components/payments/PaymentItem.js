import React, { Component } from 'react'
import axios from 'axios'
import {
    Input
} from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditPayment from './EditPayment'
import PaymentPresenter from '../presenters/PaymentPresenter'
import Refund from './Refund'
import PaymentModel from '../models/PaymentModel'
import { consts } from '../common/_consts'

export default class PaymentItem extends Component {
    constructor (props) {
        super(props)

        this.deletePayment = this.deletePayment.bind(this)
    }

    deletePayment (id, archive = true) {
        const url = archive === true ? `/api/payments/archive/${id}` : `/api/payments/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrPayments = [...self.props.payments]
                const index = arrPayments.findIndex(payment => payment.id === id)
                arrPayments.splice(index, 1)
                self.props.updateCustomers(arrPayments)
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
        const { payments, custom_fields, invoices, customers, credits } = this.props

        if (payments && payments.length && customers.length && invoices.length && credits.length) {
            return payments.map(payment => {
                const paymentModel = new PaymentModel(invoices, payment, credits)
                const paymentableInvoices = invoices && invoices.length ? paymentModel.paymentableInvoices : null
                const paymentableCredits = credits && credits.length ? paymentModel.paymentableCredits : null

                const restoreButton = !paymentModel.isDeleted
                    ? <RestoreModal id={payment.id} entities={payments} updateState={this.props.updateCustomers}
                        url={`/api/payments/restore/${payment.id}`}/> : null

                const archiveButton = paymentModel.isActive
                    ? <DeleteModal archive={true} deleteFunction={this.deletePayment} id={payment.id}/> : null

                const deleteButton = paymentModel.isActive || paymentModel.isArchived
                    ? <DeleteModal archive={false} deleteFunction={this.deletePayment} id={payment.id}/> : null

                const editButton = !payment.deleted_at ? <EditPayment
                    custom_fields={custom_fields}
                    invoices={invoices}
                    credits={credits}
                    payment={payment}
                    action={this.props.updateCustomers}
                    payments={payments}
                    customers={customers}
                    modal={true}
                /> : null

                const columnList = Object.keys(payment).filter(key => {
                    return this.props.ignoredColumns && !this.props.ignoredColumns.includes(key)
                }).map(key => {
                    return <PaymentPresenter key={key} customers={customers} field={key}
                        paymentables={paymentableInvoices} paymentable_credits={paymentableCredits}
                        entity={payment}
                        toggleViewedEntity={this.props.toggleViewedEntity}/>
                })

                const refundButton = paymentableInvoices.length && invoices.length
                    ? <Refund customers={customers} payment={payment}
                        modal={true}
                        allInvoices={paymentableInvoices}
                        allCredits={paymentableCredits}
                        invoices={invoices}
                        credits={credits}
                        payments={payments}
                        paymentables={payment.paymentables}
                        action={this.props.updateCustomers}/> : null

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(payment.id)
                const selectedRow = this.props.viewId === payment.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true ? <ActionsMenu refund={refundButton} edit={editButton} delete={deleteButton} archive={archiveButton}
                    restore={restoreButton}/> : null

                return (
                    <tr className={selectedRow} key={payment.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={payment.id} type="checkbox"
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
