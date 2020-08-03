import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditCase from './EditCase'
import { Input } from 'reactstrap'
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
            return cases.map(case_file => {
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
                    return <CasePresenter key={key} customers={customers}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={case_file}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(case_file.id)
                const selectedRow = this.props.viewId === case_file.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                    restore={restoreButton}/> : null

                return <tr className={selectedRow} key={case_file.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={case_file.id} type="checkbox"
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
