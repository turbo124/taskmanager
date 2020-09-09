import React, { Component } from 'react'
import axios from 'axios'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditBrand from './edit/EditBrand'
import { Input } from 'reactstrap'
import BrandPresenter from '../presenters/BrandPresenter'

export default class CaseTemplateItem extends Component {
    constructor (props) {
        super(props)

        this.deleteCaseTemplate = this.deleteCaseTemplate.bind(this)
    }

    deleteBrand (id, archive = false) {
        const url = archive === true ? `/api/case_template/archive/${id}` : `/api/case_template/${id}`
        const self = this
        axios.delete(url)
            .then(function (response) {
                const arrBrands = [...self.props.templates]
                const index = arrBrands.findIndex(brand => brand.id === id)
                arrBrands.splice(index, 1)
                self.props.addUserToState(arrBrands)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { templates, ignoredColumns, customers } = this.props
        if (templates && templates.length) {
            return brands.map(brand => {
                const restoreButton = brand.deleted_at
                    ? <RestoreModal id={brand.id} entities={brands} updateState={this.props.addUserToState}
                        url={`/api/case_template/restore/${brand.id}`}/> : null
                const deleteButton = !brand.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteCaseTemplate} id={brand.id}/> : null
                const archiveButton = !brand.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteCaseTemplate} id={brand.id}/> : null

                const editButton = !brand.deleted_at ? <EditBrand
                    templates={templates}
                    template={brand}
                    action={this.props.addUserToState}
                /> : null

                const columnList = Object.keys(brand).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <BrandPresenter key={key} customers={customers}
                        toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={brand}/>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(brand.id)
                const selectedRow = this.props.viewId === brand.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                return <tr className={selectedRow} key={brand.id}>
                    <td>
                        <Input checked={isChecked} className={checkboxClass} value={brand.id} type="checkbox"
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
