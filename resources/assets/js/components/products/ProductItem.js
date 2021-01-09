import React, { Component } from 'react'
import axios from 'axios'
import { Badge, Input, ListGroupItem } from 'reactstrap'
import RestoreModal from '../common/RestoreModal'
import DeleteModal from '../common/DeleteModal'
import ActionsMenu from '../common/ActionsMenu'
import EditProduct from './edit/EditProduct'
import ProductPresenter from '../presenters/ProductPresenter'
import { translations } from '../utils/_translations'

export default class ProductItem extends Component {
    constructor (props) {
        super(props)

        this.state = {
            width: window.innerWidth,
        }

        this.deleteProduct = this.deleteProduct.bind(this)
        this.handleWindowSizeChange = this.handleWindowSizeChange.bind(this)
    }

    componentWillMount () {
        window.addEventListener('resize', this.handleWindowSizeChange);
    }

    componentWillUnmount () {
        window.removeEventListener('resize', this.handleWindowSizeChange);
    }

    handleWindowSizeChange () {
        this.setState({ width: window.innerWidth });
    }

    deleteProduct (id, archive = false) {
        const self = this
        const url = archive === true ? `/api/products/archive/${id}` : `/api/products/${id}`
        axios.delete(url)
            .then(function (response) {
                const arrProducts = [...self.props.products]
                const index = arrProducts.findIndex(product => product.id === id)
                arrProducts.splice(index, 1)
                self.props.addProductToState(arrProducts)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    render () {
        const { products, custom_fields, companies, categories, ignoredColumns } = this.props

        if (products && products.length) {
            return products.map((product, index) => {
                const restoreButton = product.deleted_at
                    ? <RestoreModal id={product.id} entities={products} updateState={this.props.addProductToState}
                        url={`/api/products/restore/${product.id}`}/> : null
                const deleteButton = !product.deleted_at
                    ? <DeleteModal archive={false} deleteFunction={this.deleteProduct} id={product.id}/> : null
                const editButton = !product.deleted_at ? <EditProduct
                    custom_fields={custom_fields}
                    companies={companies}
                    categories={categories}
                    product={product}
                    products={products}
                    action={this.props.addProductToState}
                /> : null

                const archiveButton = !product.deleted_at
                    ? <DeleteModal archive={true} deleteFunction={this.deleteProduct} id={product.id}/> : null

                const status = (product.deleted_at && !product.is_deleted) ? (<Badge className="mr-2"
                    color="warning">{translations.archived}</Badge>) : ((product.deleted_at && product.is_deleted) ? (
                    <Badge className="mr-2" color="danger">{translations.deleted}</Badge>) : (''))

                const columnList = Object.keys(product).filter(key => {
                    return ignoredColumns && !ignoredColumns.includes(key)
                }).map(key => {
                    return <td key={key}
                        onClick={() => this.props.toggleViewedEntity(product, product.name, editButton)}
                        data-label={key}><ProductPresenter companies={companies} edit={editButton}
                            toggleViewedEntity={this.props.toggleViewedEntity}
                            field={key} entity={product}/></td>
                })

                const checkboxClass = this.props.showCheckboxes === true ? '' : 'd-none'
                const isChecked = this.props.bulk.includes(product.id)
                const selectedRow = this.props.viewId === product.id ? 'table-row-selected' : ''
                const actionMenu = this.props.showCheckboxes !== true
                    ? <ActionsMenu edit={editButton} delete={deleteButton} archive={archiveButton}
                        restore={restoreButton}/> : null

                const is_mobile = this.state.width <= 500

                if (!this.props.show_list) {
                    return <tr className={selectedRow} key={product.id}>
                        <td>
                            <Input checked={isChecked} className={checkboxClass} value={product.id} type="checkbox"
                                onChange={this.props.onChangeBulk}/>
                            {actionMenu}
                        </td>
                        {columnList}
                        {!!status && <td>{status}</td>}
                    </tr>
                }

                return !is_mobile ? <div className="d-flex d-inline list-group-item-dark">
                    <div className="list-action">
                        {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={product.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                        {actionMenu}
                    </div>
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(product, product.name, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1"><ProductPresenter field="name" entity={product}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/></h5>
                            <span className="mb-1"><ProductPresenter field="description" entity={product}
                                edit={editButton}/> </span>
                            <span>
                                <ProductPresenter
                                    field="price" entity={product} toggleViewedEntity={this.props.toggleViewedEntity}
                                    edit={editButton}/>
                            </span>
                        </div>
                    </ListGroupItem>
                </div> : <div className="d-flex d-inline list-group-item-dark">
                    <div className="list-action">
                        {!!this.props.onChangeBulk &&
                        <Input checked={isChecked} className={checkboxClass} value={product.id} type="checkbox"
                            onChange={this.props.onChangeBulk}/>
                        }
                        {actionMenu}
                    </div>
                    <ListGroupItem key={index}
                        onClick={() => this.props.toggleViewedEntity(product, product.name, editButton)}
                        className="border-top-0 list-group-item-dark list-group-item-action flex-column align-items-start">
                        <div className="d-flex w-100 justify-content-between">
                            <h5 className="mb-1"><ProductPresenter field="name" entity={product}
                                toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/></h5>
                            <ProductPresenter
                                field="price" entity={product} toggleViewedEntity={this.props.toggleViewedEntity}
                                edit={editButton}/>
                        </div>
                        <div className="d-flex w-100 justify-content-between">
                            <span className="mb-1 text-muted"><ProductPresenter field="description" entity={product}
                                edit={editButton}/> </span>

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
