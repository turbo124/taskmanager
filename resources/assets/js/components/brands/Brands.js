import React, { Component } from 'react'
import axios from 'axios'
import AddBrand from './AddBrand'
import { CardBody, Card } from 'reactstrap'
import DataTable from '../common/DataTable'
import BrandFilters from './BrandFilters'
import BrandItem from './BrandItem'

export default class Brands extends Component {
    constructor (props) {
        super(props)

        this.state = {
            dropdownButtonActions: ['download'],
            brands: [],
            cachedData: [],
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['id', 'brand_id', 'parent_id', 'account_id', 'user_id', 'is_deleted', 'updated_at', 'status', 'deleted_at', 'created_at'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterBrands = this.filterBrands.bind(this)
        this.getCustomers = this.getCustomers.bind(this)
    }

    addUserToState (brands) {
        const cachedData = !this.state.cachedData.length ? brands : this.state.cachedData
        this.setState({
            brands: brands,
            cachedData: cachedData
        })
    }

    getCustomers () {
        axios.get('/api/customers')
            .then((r) => {
                this.setState({
                    customers: r.data
                })
            })
            .catch((e) => {
                console.error(e)
            })
    }

    componentDidMount () {
        this.getCustomers()
    }

    filterBrands (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { brands, customers } = this.state
        return <BrandItem showCheckboxes={props.showCheckboxes} customers={customers} brands={brands}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            onChangeBulk={props.onChangeBulk}/>
    }

    getUsers () {
        axios.get('api/users')
            .then((r) => {
                this.setState({
                    users: r.data
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    err: e
                })
            })
    }

    render () {
        const { searchText, status, start_date, end_date } = this.state.filters
        const { view, brands, customers } = this.state
        const fetchUrl = `/api/brands?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `

        return (
            <div className="data-table">
                <Card>
                    <CardBody>
                        <BrandFilters brands={brands}
                            customers={customers}
                            updateIgnoredColumns={this.updateIgnoredColumns}
                            filters={this.state.filters} filter={this.filterBrands}
                            saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                        <AddBrand
                            customers={customers}
                            brands={brands}
                            action={this.addUserToState}
                        />
                    </CardBody>
                </Card>

                <Card>
                    <CardBody>
                        <DataTable
                            columnMapping={{ customer_id: 'CUSTOMER' }}
                            dropdownButtonActions={this.state.dropdownButtonActions}
                            entity_type="Brand"
                            bulk_save_url="/api/brands/bulk"
                            view={view}
                            ignore={this.state.ignoredColumns}
                            userList={this.userList}
                            fetchUrl={fetchUrl}
                            updateState={this.addUserToState}
                        />
                    </CardBody>
                </Card>
            </div>
        )
    }
}
