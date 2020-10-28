import axios from 'axios'
import React, { Component } from 'react'
import { Input } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import EditLead from './edit/EditLeadForm'
import ActionsMenu from '../common/ActionsMenu'
import LeadPresenter from '../presenters/LeadPresenter'

export default class LeadItem extends Component {
    constructor (props) {
        super(props)

        this.deleteLead = this.deleteLead.bind(this)
    }

    deleteLead (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/leads/archive/${id}` : `/api/leads/${id}`

        axios.delete(url)
            .then(function (response) {
                const arrLeads = [...self.props.leads]
                const index = arrLeads.findIndex(lead => lead.id === id)
                arrLeads.splice(index, 1)
                self.props.addUserToState(arrLeads)
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
        const { leads, custom_fields, users, ignoredColumns } = this.props
        if (leads && leads.length) {
            return leads.map(lead => {
                const restoreButton = lead.deleted_at
                    ? <RestoreModal id={lead.id} entities={leads} updateState={this.props.addUserToState}
                        url={`/api/leads/restore/${lead.id}`}/> : null
                const archiveButton = !lead.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteLead} id={lead.id}/> : null
                const deleteButton = !lead.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteLead} id={lead.id}/> : null
                const editButton = !lead.deleted_at ? <EditLead
                    listView={true}
                    custom_fields={custom_fields}
                    users={users}
                    lead={lead}
                    leads={leads}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(lead).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <LeadPresenter key={key} toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={lead} edit={editButton}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(lead.id)
                const selectedRow = this.props.viewId === lead.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return <tr className={selectedRow} key={lead.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={lead.id} type="checkbox"
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
