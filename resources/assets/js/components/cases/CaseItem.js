import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditCase from './edit/EditCase'
import { Input, ListGroupItem } from 'reactstrap'
import CasePresenter from '../presenters/CasePresenter'

export default class CaseItem extends Component {
    constructor (props) {
        super(props)

        this.deleteCase = this.deleteCase.bind(this)
    }

    deleteCase (id, archive = false) {
        const url = archive === true ? `/api/cases/archive/${id}` : `/api/cases/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrCases = [...self.props.cases]
                const index = arrCases.findIndex(case_file => case_file.id === id)
                arrCases.splice(index, 1)
                self.props.addUserToState(arrCases)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { cases, ignoredColumns, customers } = this.props
        if (cases && cases.length) {
           return cases.map((case_file, index) => {
                const restoreButton = case_file.deleted_at
                    ? <RestoreModal id={case_file.id} entities={cases} updateState={this.props.addUserToState}
                        url={`/api/cases/restore/${case_file.id}`}/> : null
                const deleteButton = !case_file.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteCase} id={case_file.id}/> : null
                const archiveButton = !case_file.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteCase} id={case_file.id}/> : null

                const editButton = !case_file.deleted_at ? <EditCase
                    cases={cases}
                    customers={customers}
                    case={case_file}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(case_file).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(case_file, case_file.subject, editButton)}
                        data-label={key}><CasePresenter key={key} customers={customers} edit={editButton}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={case_file}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(case_file.id)
                const selectedRow = this.props.viewId === case_file.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return !this.props.show_list ? <tr className={selectedRow} key={case_file.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={case_file.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        {actionMenu}
                    </td>
                    {columnList}
                </tr> : <ListGroupItem onClick={() => this.props.toggleViewedEntity(case_file, case_file.subject, editButton)}
                    key={index}
                    className="list-group-item-dark list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">{<CasePresenter customers={customers} field="customer_id"
                            entity={case_file}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>}</h5>
                        {<CasePresenter customers={customers}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field="priority_field" entity={case_file} edit={editButton}/>}
                    </div>
                    <div className="d-flex w-100 justify-content-between">
                        <span className="mb-1 text-muted">{<CasePresenter field="due_date"
                            entity={case_file}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>} </span>
                        <span>{<CasePresenter field="status_field" entity={case_file}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            edit={editButton}/>}</span>
                    </div>
                    {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={case_file.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                    {actionMenu}
                </ListGroupItem>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
