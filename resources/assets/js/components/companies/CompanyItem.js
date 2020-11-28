import React, { Component } from 'react'
import axios from 'axios'
import { Badge, Input } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditCompany from './edit/EditCompany'
import CompanyPresenter from '../presenters/CompanyPresenter'
import { translations } from '../utils/_translations'

export default class CompanyItem extends Component {
    constructor (props) {
        super(props)

        this.deleteBrand = this.deleteBrand.bind(this)
    }

    deleteBrand (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/companies/archive/${id}` : `/api/companies/${id}`

        axios.delete(url)
            .then(function (response) {
                const arrBrands = [...self.props.brands]
                const index = arrBrands.findIndex(brand => brand.id === id)
                arrBrands.splice(index, 1)
                self.props.addUserToState(arrBrands)
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
        const { brands, custom_fields, users, ignoredColumns } = this.props
        if (brands && brands.length) {
            return brands.map(brand => {
                const restoreButton = brand.deleted_at && !brand.is_deleted
                    ? <RestoreModal id={brand.id} entities={brands} updateState={this.props.addUserToState}
                        url={`/api/companies/restore/${brand.id}`}/> : null
                const archiveButton = !brand.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteBrand} id={brand.id}/> : null
                const deleteButton = !brand.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteBrand} id={brand.id}/> : null
                const editButton = !brand.deleted_at ? <EditCompany
                    custom_fields={custom_fields}
                    users={users}
                    brand={brand}
                    brands={brands}
                    action={this.props.addUserToState}
                /> : null

                const status = (brand.deleted_at && !brand.is_deleted) ? (<Badge className="mr-2"
                    color="warning">{translations.archived}</Badge>) : ((brand.deleted_at && brand.is_deleted) ? (
                    <Badge className="mr-2" color="danger">{translations.deleted}</Badge>) : (''))

                const columnList = Object.keys(brand).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <CompanyPresenter key={key} toggleViewedEntity={this.props.toggleViewedEntity}
                        field={key} entity={brand} edit={editButton}/>
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
                    {!!status && <td>{status}</td>}
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
