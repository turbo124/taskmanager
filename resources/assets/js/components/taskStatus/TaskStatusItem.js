import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditTaskStatus from './EditTaskStatus'
import { Input } from 'reactstrap'
import TaskStatusPresenter from '../presenters/TaskStatusPresenter'

export default class TaskStatusItem extends Component {
    constructor (props) {
        super(props)

        this.deleteTaskStatus = this.deleteTaskStatus.bind(this)
    }

    deleteTaskStatus (id, archive = false) {
        const url = archive === true ? `/api/taskStatus/archive/${id}` : `/api/statuses/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrTaskStatuss = [...self.props.statuses]
                const index = arrTaskStatuss.findIndex(taskStatus => taskStatus.id === id)
                arrTaskStatuss.splice(index, 1)
                self.props.addUserToState(arrTaskStatuss)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { statuses, ignoredColumns, customers } = this.props
        if (statuses && statuses.length) {
            return statuses.map(taskStatus => {
                const restoreButton = taskStatus.deleted_at
                    ? <RestoreModal id={taskStatus.id} entities={statuses} updateState={this.props.addUserToState}
                        url={`/api/statuses/restore/${taskStatus.id}`}/> : null
                const deleteButton = !taskStatus.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteTaskStatus} id={taskStatus.id}/> : null
                const archiveButton = !taskStatus.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteTaskStatus} id={taskStatus.id}/> : null

                const editButton = !taskStatus.deleted_at ? <EditTaskStatus
                    statuses={statuses}
                    customers={customers}
                    task_status={taskStatus}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(taskStatus).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <TaskStatusPresenter key={key} customers={customers}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={taskStatus}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(taskStatus.id)
                const selectedRow = this.props.viewId === taskStatus.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return <tr className={selectedRow} key={taskStatus.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={taskStatus.id} type="checkbox"
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
