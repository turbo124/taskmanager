import axios from 'axios'
import React, { Component } from 'react'
import { Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditDeal from './edit/EditDeal'
import DealPresenter from '../presenters/DealPresenter'
import LeadPresenter from '../presenters/LeadPresenter'

export default class DealItem extends Component {
    constructor (props) {
        super(props)

        this.deleteDeal = this.deleteDeal.bind(this)
    }

    deleteDeal (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/deals/archive/${id}` : `/api/deals/${id}`

        axios.delete(url)
            .then(function (response) {
                const arrDeals = [...self.props.deals]
                const index = arrDeals.findIndex(deal => deal.id === id)
                arrDeals.splice(index, 1)
                self.props.addUserToState(arrDeals)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { deals, custom_fields, users, ignoredColumns, customers } = this.props
        if (deals && deals.length && users.length) {
            return deals.map((deal, index) => {
                const restoreButton = deal.deleted_at
                    ? <RestoreModal id={deal.id} entities={deals} updateState={this.props.addUserToState}
                        url={`/api/deals/restore/${deal.id}`}/> : null
                const archiveButton = !deal.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteDeal} id={deal.id}/> : null
                const deleteButton = !deal.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteDeal} id={deal.id}/> : null
                const editButton = !deal.deleted_at ? <EditDeal
                    modal={true}
                    listView={true}
                    custom_fields={custom_fields}
                    users={users}
                    deal={deal}
                    deals={deals}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(deal).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key} onClick={() => this.props.toggleViewedEntity(deal, deal.number, editButton)}
                        data-label={key}><DealPresenter toggleViewedEntity={this.props.toggleViewedEntity}
                            field={key} entity={deal} custom_fields={custom_fields}
                            edit={editButton}
                            users={users}
                            customers={this.props.customers}
                            deals={deals}
                            action={this.props.action}
                            deal={deal}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(deal.id)
                const selectedRow = this.props.viewId === deal.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                const is_mobile = window.innerWidth <= 768

                if (!this.props.show_list) {
                    return <tr className={selectedRow} key={deal.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={deal.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr>
                }

                return !is_mobile ? <div className="list-group-item-dark d-flex d-inline">
                    <div className="list-action">
                        {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={deal.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                        {actionMenu}
                    </div>
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(deal, deal.name, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 style={{ minWidth: '300px' }} className="mb-1">{<DealPresenter customers={customers} field="name" entity={deal}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>}</h5>
                            <span><DealPresenter field="customer_id" customers={customers}
                                entity={deal}
                                edit={editButton}/>
                            <br />
                            {!!deal.project && deal.project.name &&
                                <DealPresenter field="project" entity={deal}
                                    toggleViewedEntity={this.props.toggleViewedEntity}
                                    edit={editButton}/>
                            }
                            </span>
                            <span>
                                <DealPresenter customers={customers}
                                    field="due_date" entity={deal}
                                    toggleViewedEntity={this.props.toggleViewedEntity}
                                    edit={editButton}/>
                            </span>
                            <span><DealPresenter field="status_field" entity={deal}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/></span>
                        </div>
                    </ListGroupItem>
                </div> : <div className="list-group-item-dark">
                    <div className="list-action">
                        {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={deal.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                        {actionMenu}
                    </div>
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(deal, deal.name, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1">{<DealPresenter customers={customers} field="name" entity={deal}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>}</h5>
                            {<DealPresenter customers={customers}
                                field="due_date" entity={deal}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>}
                        </div>
                        <div className="d-flex w-100 justify-content-between">
                            <span className="mb-1 text-muted">{<DealPresenter field="customer_id" customers={customers}
                                entity={deal}
                                edit={editButton}/>} </span>
                            <span>{<DealPresenter field="status_field" entity={deal}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>}</span>
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
