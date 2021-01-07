import React, { Component } from 'react'
import axios from 'axios'
import { Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditCredit from './edit/EditCredit'
import CreditPresenter from '../presenters/CreditPresenter'

export default class CreditItem extends Component {
    constructor (props) {
        super(props)

        this.deleteCredit = this.deleteCredit.bind(this)
    }

    deleteCredit (id, archive = false) {
        const url = archive === true ? `/api/credits/archive/${id}` : `/api/credits/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrPayments = [...self.props.credits]
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
        const { credits, customers, custom_fields, ignoredColumns } = this.props
        if (credits && credits.length && customers.length) {
            return credits.map((credit, index) => {
                const editButton = !credit.deleted_at ? <EditCredit
                    custom_fields={custom_fields}
                    credit={credit}
                    action={this.props.updateCustomers}
                    credits={credits}
                    customers={customers}
                    modal={true}
                /> : null
                const restoreButton = credit.deleted_at
                    ? <RestoreModal id={credit.id} entities={credits} updateState={this.props.updateCustomers}
                        url={`/api/credits/restore/${credit.id}`}/> : null
                const archiveButton = !credit.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteCredit} id={credit.id}/> : null
                const deleteButton = !credit.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteCredit} id={credit.id}/> : null

                const columnList = Object.keys(credit).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(credit, credit.number, editButton)}
                        data-label={key}><CreditPresenter customers={customers}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field={key} entity={credit} edit={editButton}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(credit.id)
                const selectedRow = this.props.viewId === credit.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                const is_mobile = window.innerWidth <= 768

                if (!this.props.show_list) {
                    return <tr className={selectedRow} key={credit.id}>
                        <td>
                            {!!this.props.onChangeBulk &&
                            <Input checked={isChecked} className={checkboxClass} value={credit.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            }
                            {!!this.props.onChangeBulk &&
                            <Input checked={isChecked} className={checkboxClass} value={credit.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            }
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr>
                }
                return is_mobile ? <div className="list-group-item-dark">
                    {!!this.props.onChangeBulk &&
                    <Input checked={isChecked} className={checkboxClass} value={credit.id} type="checkbox"
                        onChange={this.props.onChangeBulk}/>
                    }
                    {actionMenu}
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(credit, credit.number, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1"> {<CreditPresenter customers={customers} field="customer_id"
                                entity={credit}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>}</h5>
                            {<CreditPresenter customers={customers}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                field={credit.balance > 0 ? 'balance' : 'total'} entity={credit}
                                edit={editButton}/>}
                        </div>
                        <div className="d-flex w-100 justify-content-between">
                            <span className="mb-1 text-muted">{credit.number} . {<CreditPresenter
                                field={credit.due_date.length ? 'due_date' : 'date'} entity={credit}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>} </span>
                            <span>{<CreditPresenter field="status_field" entity={credit} edit={editButton}
                                toggleViewedEntity={this.props.toggleViewedEntity}/>}</span>
                        </div>
                    </ListGroupItem>
                </div> : <div className="list-group-item-dark">
                    {!!this.props.onChangeBulk &&
                    <Input checked={isChecked} className={checkboxClass} value={credit.id} type="checkbox"
                        onChange={this.props.onChangeBulk}/>
                    }
                    {actionMenu}
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(credit, credit.number, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1"> {<CreditPresenter customers={customers} field="customer_id"
                                entity={credit}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>}</h5>
                            {<CreditPresenter customers={customers}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                field={credit.balance > 0 ? 'balance' : 'total'} entity={credit}
                                edit={editButton}/>}
                            <span className="mb-1 text-muted">{credit.number} . {<CreditPresenter
                                field={credit.due_date.length ? 'due_date' : 'date'} entity={credit}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>} </span>
                            <span>{<CreditPresenter field="status_field" entity={credit} edit={editButton}
                                toggleViewedEntity={this.props.toggleViewedEntity}/>}</span>
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
