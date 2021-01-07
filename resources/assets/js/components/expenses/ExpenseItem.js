import React, { Component } from 'react'
import axios from 'axios'
import { Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditExpense from './edit/EditExpense'
import ExpensePresenter from '../presenters/ExpensePresenter'

export default class ExpenseItem extends Component {
    constructor (props) {
        super(props)

        this.deleteExpense = this.deleteExpense.bind(this)
    }

    deleteExpense (id, archive = false) {
        const url = archive === true ? `/api/expenses/archive/${id}` : `/api/expenses/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrExpenses = [...self.props.expenses]
                const index = arrExpenses.findIndex(expense => expense.id === id)
                arrExpenses.splice(index, 1)
                self.props.updateExpenses(arrExpenses)
            })
            .catch(function (error) {
                alert(error)
            })
    }

    render () {
        const { expenses, customers, custom_fields, ignoredColumns, companies } = this.props
        if (expenses && expenses.length && customers.length) {
            return expenses.map((expense, index) => {
                const restoreButton = expense.deleted_at
                    ? <RestoreModal id={expense.id} entities={expenses} updateState={this.props.updateExpenses}
                        url={`/api/expenses/restore/${expense.id}`}/> : null
                const archiveButton = !expense.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteExpense} id={expense.id}/> : null
                const deleteButton = !expense.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteExpense} id={expense.id}/> : null
                const editButton = !expense.deleted_at ? <EditExpense
                    companies={companies}
                    custom_fields={custom_fields}
                    expense={expense}
                    action={this.props.updateExpenses}
                    expenses={expenses}
                    customers={customers}
                    modal={true}
                /> : null

                const columnList = Object.keys(expense).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(expense, expense.number, editButton)}
                        data-label={key}><ExpensePresenter companies={companies} customers={customers}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field={key} entity={expense} edit={editButton}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(expense.id)
                const selectedRow = this.props.viewId === expense.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                const is_mobile = window.innerWidth <= 768

                if (!this.props.show_list) {
                    return <tr className={selectedRow} key={index}>
                        <td>
                            {!!this.props.onChangeBulk &&
                            <Input checked={isChecked} className={checkboxClass} value={expense.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            }
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr>
                }

                return !is_mobile ? <div className="list-group-item-dark">
                    {!!this.props.onChangeBulk &&
                    <Input checked={isChecked} className={checkboxClass} value={expense.id} type="checkbox"
                        onChange={this.props.onChangeBulk}/>
                    }
                    {actionMenu}
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(expense, expense.number, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1">{<ExpensePresenter customers={customers} field="customer_id"
                                entity={expense}
                                edit={editButton}
                                toggleViewedEntity={this.props.toggleViewedEntity}/>}</h5>
                            <span className="mb-1">{expense.number} . {<ExpensePresenter field="date"
                                entity={expense}
                                edit={editButton}
                                toggleViewedEntity={this.props.toggleViewedEntity}/>} </span>
                            {<ExpensePresenter customers={customers}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                field="amount" entity={expense} edit={editButton}/>}
                            <span>{<ExpensePresenter field="status_field" entity={expense} edit={editButton}
                                toggleViewedEntity={this.props.toggleViewedEntity}/>}</span>
                        </div>
                    </ListGroupItem>
                </div> : <div className="list-group-item-dark">
                    {!!this.props.onChangeBulk &&
                    <Input checked={isChecked} className={checkboxClass} value={expense.id} type="checkbox"
                        onChange={this.props.onChangeBulk}/>
                    }
                    {actionMenu}
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(expense, expense.number, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1">{<ExpensePresenter customers={customers} field="customer_id"
                                entity={expense}
                                edit={editButton}
                                toggleViewedEntity={this.props.toggleViewedEntity}/>}</h5>
                            {<ExpensePresenter customers={customers}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                field="amount" entity={expense} edit={editButton}/>}
                        </div>
                        <div className="d-flex w-100 justify-content-between">
                            <span className="mb-1 text-muted">{expense.number} . {<ExpensePresenter field="date"
                                entity={expense}
                                edit={editButton}
                                toggleViewedEntity={this.props.toggleViewedEntity}/>} </span>
                            <span>{<ExpensePresenter field="status_field" entity={expense} edit={editButton}
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
