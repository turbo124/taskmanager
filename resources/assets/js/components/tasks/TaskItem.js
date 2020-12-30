import axios from 'axios'
import React, { Component } from 'react'
import { Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditTask from './edit/EditTask'
import TaskPresenter from '../presenters/TaskPresenter'
import EditTaskDesktop from './edit/EditTaskDesktop'

export default class TaskItem extends Component {
    constructor (props) {
        super(props)

        this.deleteTask = this.deleteTask.bind(this)
    }

    deleteTask (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/tasks/archive/${id}` : `/api/tasks/${id}`

        axios.delete(url)
            .then(function (response) {
                const arrTasks = [...self.props.tasks]
                const index = arrTasks.findIndex(task => task.id === id)
                arrTasks.splice(index, 1)
                self.props.addUserToState(arrTasks)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { tasks, custom_fields, users, ignoredColumns } = this.props
        const is_mobile = window.innerWidth <= 768

        if (tasks && tasks.length && users.length) {
            return tasks.map(task => {
                const restoreButton = task.deleted_at && !task.is_deleted
                    ? <RestoreModal id={task.id} entities={tasks} updateState={this.props.addUserToState}
                        url={`/api/tasks/restore/${task.id}`}/> : null
                const archiveButton = !task.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteTask} id={task.id}/> : null
                const deleteButton = !task.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteTask} id={task.id}/> : null
                const editButton = is_mobile ? <EditTask
                    modal={true}
                    listView={true}
                    custom_fields={custom_fields}
                    users={users}
                    task={task}
                    allTasks={tasks}
                    action={this.props.addUserToState}
                /> : <EditTaskDesktop
                    add={false}
                    modal={true}
                    listView={true}
                    custom_fields={custom_fields}
                    users={users}
                    task={task}
                    tasks={tasks}
                    action={this.props.addUserToState}
                />

                const columnList = Object.keys(task).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <TaskPresenter key={key} toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={task} custom_fields={custom_fields}
                        users={users}
                        customers={this.props.customers}
                        tasks={tasks}
                        action={this.props.action}
                        edit={editButton}
                        task={task}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(task.id)
                const selectedRow = this.props.viewId === task.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return !this.props.show_list ? <tr className={selectedRow} key={task.id}>
                    <td>
                        {!!this.props.onChangeBulk && 
                        <Input checked={isChecked} className={checkboxClass} value={task.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                        {actionMenu}
                    </td>
                    {columnList}
                </tr> : <ListGroupItem key={task.id}
                    className="list-group-item-dark list-group-item-action flex-column align-items-start">
                    <div className="d-flex w-100 justify-content-between">
                        <h5 className="mb-1">{actionMenu} {<TaskPresenter field="customer" entity={task} edit={editButton}/>}</h5>
                        {<TaskPresenter key={key} customers={customers}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field="name" entity={task} edit={editButton}/>}
                    </div>
                    <div className="d-flex w-100 justify-content-between">
                        <span className="mb-1 text-muted">{task.number} . {<TaskPresenter field="date" entity={task} edit={editButton}/>} </span>
                        <span>{<TaskPresenter field="status_field" entity={task}/>}</span>
                    </div>
                </ListGroupItem>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
