import React, { Component } from 'react'
import axios from 'axios'
import { Badge, Input } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditProject from './edit/EditProject'
import ProjectPresenter from '../presenters/ProjectPresenter'
import { translations } from '../utils/_translations'

export default class ProjectItem extends Component {
    constructor (props) {
        super(props)

        this.deleteProject = this.deleteProject.bind(this)
    }

    deleteProject (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/projects/archive/${id}` : `/api/projects/${id}`

        axios.delete(url)
            .then(function (response) {
                const arrProjects = [...self.props.projects]
                const index = arrProjects.findIndex(project => project.id === id)
                arrProjects.splice(index, 1)
                self.props.addUserToState(arrProjects)
            })
            .catch(function (error) {
                console.log(error)
                self.setState(
                    {
                        error: error.response.data
                    }
                )
            })
    }

    render () {
        const { projects, custom_fields, customers, ignoredColumns } = this.props
        if (projects && projects.length) {
            return projects.map(project => {
                const restoreButton = project.deleted_at && !project.is_deleted
                    ? <RestoreModal id={project.id} entities={projects} updateState={this.props.addUserToState}
                        url={`/api/projects/restore/${project.id}`}/> : null
                const archiveButton = !project.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteProject} id={project.id}/> : null
                const deleteButton = !project.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteProject} id={project.id}/> : null
                const editButton = !project.deleted_at ? <EditProject
                    listView={true}
                    custom_fields={custom_fields}
                    customers={customers}
                    project={project}
                    projects={projects}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(project).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <ProjectPresenter key={key} customers={this.props.customers} edit={editButton}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={project}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(project.id)
                const selectedRow = this.props.viewId === project.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                const status = (project.deleted_at && !project.is_deleted) ? (<Badge className="mr-2"
                    color="warning">{translations.archived}</Badge>) : ((project.deleted_at && project.is_deleted) ? (
                    <Badge className="mr-2" color="danger">{translations.deleted}</Badge>) : (''))

                return <tr className={selectedRow} key={project.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={project.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        {actionMenu}
                    </td>
                    {columnList}
                    {!!status && <td>{status}</td>}
                </tr>
            })
        } else {
            return <tr>
                <td className="text-center">No Records Found.</td>
            </tr>
        }
    }
}
