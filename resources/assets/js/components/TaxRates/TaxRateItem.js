import React, { Component } from 'react'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditTaxRate from './edit/EditTaxRate'
import axios from 'axios'
import { Input } from 'reactstrap'
import TaxRatePresenter from '../presenters/TaxRatePresenter'

export default class TaxRateItem extends Component {
    constructor (props) {
        super(props)

        this.deleteTaxRate = this.deleteTaxRate.bind(this)
    }

    deleteTaxRate (id, archive = false) {
        const url = archive === true ? `/api/taxRates/archive/${id}` : `/api/taxRates/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrTaxRates = [...self.props.taxRates]
                const index = arrTaxRates.findIndex(taxRate => taxRate.id === id)
                arrTaxRates.splice(index, 1)
                self.props.addUserToState(arrTaxRates)
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
        const { taxRates, ignoredColumns } = this.props
        if (taxRates && taxRates.length) {
           return taxRates.map((taxRate, index) => {
                const restoreButton = taxRate.deleted_at
                    ? <RestoreModal id={taxRate.id} entities={taxRates} updateState={this.props.addUserToState}
                        url={`/api/taxRate/restore/${taxRate.id}`}/> : null

                const deleteButton = !taxRate.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteTaxRate} id={taxRate.id}/> : null

                const archiveButton = !taxRate.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteTaxRate} id={taxRate.id}/> : null

                const editButton = !taxRate.deleted_at
                    ? <EditTaxRate taxRate={taxRate} taxRates={taxRates} action={this.props.addUserToState}/>
                    : null

                const columnList = Object.keys(taxRate).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(taxRate, taxRate.name, editButton)}
                        data-label={key}><TaxRatePresenter toggleViewedEntity={this.props.toggleViewedEntity}
                            field={key} entity={taxRate} edit={editButton}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(taxRate.id)
                const selectedRow = this.props.viewId === taxRate.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                const is_mobile = window.innerWidth <= 768

                if (!this.props.show_list) {
                    return <tr className={selectedRow} key={taxRate.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={taxRate.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            {actionMenu}
                        </td>
                        {columnList}
                    </tr>
                }

                return is_mobile ? <div className="list-group-item-dark">
                    {!!this.props.onChangeBulk &&
                    <Input checked={isChecked} className={checkboxClass} value={taxRate.id} type="checkbox"
                        onChange={this.props.onChangeBulk}/>
                    }
                    {actionMenu}

                    <ListGroupItem onClick={() => this.props.toggleViewedEntity(taxRate, taxRate.name, editButton)}
                        key={index}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1">{<TaxRatePresenter field="name"
                                entity={taxRate}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>} .
                                {<TaxRatePresenter field="rate"
                                entity={taxRate}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>} </h5>
                        </div>
                    </ListGroupItem>
                </div> : <div className="list-group-item-dark">
                    {!!this.props.onChangeBulk &&
                    <Input checked={isChecked} className={checkboxClass} value={taxRate.id} type="checkbox"
                        onChange={this.props.onChangeBulk}/>
                    }
                    {actionMenu}

                    <ListGroupItem onClick={() => this.props.toggleViewedEntity(taxRate, taxRate.name, editButton)}
                        key={index}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1">{<TaxRatePresenter field="name"
                                entity={taxRate}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>} . 
                                {<TaxRatePresenter field="rate"
                                entity={taxRate}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>}
                            </h5>
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
