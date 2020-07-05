import React, { Component } from 'react'
import axios from 'axios'
import AddCategory from './AddCategory'
import { CardBody, Card } from 'reactstrap'
import DataTable from '../common/DataTable'
import CategoryFilters from './CategoryFilters'
import CategoryItem from './CategoryItem'

export default class Categories extends Component {
    constructor (props) {
        super(props)

        this.state = {
            dropdownButtonActions: ['download'],
            categories: [],
            cachedData: [],
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['id', 'category_id', 'parent_id', 'account_id', 'user_id', 'is_deleted', 'updated_at', 'status', 'deleted_at', 'created_at'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterCategories = this.filterCategories.bind(this)
        this.getCustomers = this.getCustomers.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
    }

    addUserToState (categories) {
        const cachedData = !this.state.cachedData.length ? categories : this.state.cachedData
        this.setState({
            categories: categories,
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

    filterCategories (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { categories, customers } = this.state
        return <CategoryItem showCheckboxes={props.showCheckboxes} customers={customers} categories={categories}
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
        const { view, categories, customers } = this.state
        const fetchUrl = `/api/expense-categories?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `

        return (
            <div className="data-table">
                <Card>
                    <CardBody>
                        <CategoryFilters categories={categories}
                            customers={customers}
                            updateIgnoredColumns={this.updateIgnoredColumns}
                            filters={this.state.filters} filter={this.filterCategories}
                            saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                        <AddCategory
                            customers={customers}
                            categories={categories}
                            action={this.addUserToState}
                        />
                    </CardBody>
                </Card>

                <Card>
                    <CardBody>
                        <DataTable
                            columnMapping={{ customer_id: 'CUSTOMER' }}
                            dropdownButtonActions={this.state.dropdownButtonActions}
                            entity_type="Category"
                            bulk_save_url="/api/expense-categories/bulk"
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
