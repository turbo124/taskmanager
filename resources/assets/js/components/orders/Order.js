import React, { Component } from 'react'
import axios from 'axios'
import EditOrder from './EditOrder'
import {
    Card, CardBody
} from 'reactstrap'
import DataTable from '../common/DataTable'
import OrderItem from './OrderItem'
import OrderFilters from './OrderFilters'
import queryString from 'query-string'

export default class Order extends Component {
    constructor (props) {
        super(props)
        this.state = {
            per_page: 5,
            view: {
                ignore: ['design_id', 'status_id', 'custom_surcharge_tax1', 'custom_surcharge_tax2'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            orders: [],
            cachedData: [],
            customers: [],
            bulk: [],
            dropdownButtonActions: ['download', 'cancel', 'archive', 'reverse', 'delete'],
            custom_fields: [],
            ignoredColumns: ['invoice_id', 'customer_name', 'emails', 'custom_surcharge1', 'custom_surcharge_tax1', 'custom_surcharge2', 'custom_surcharge_tax2', 'design_id', 'invitations', 'id', 'user_id', 'status', 'company_id', 'custom_value1', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'terms', 'footer', 'last_send_date', 'line_items', 'next_send_date', 'last_sent_date', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total'],
            filters: {
                status_id: 'active',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                searchText: queryString.parse(this.props.location.search).number || '',
                start_date: '',
                end_date: ''
            },
            showRestoreButton: false
        }

        this.updateOrder = this.updateOrder.bind(this)
        this.userList = this.userList.bind(this)
        this.filterOrders = this.filterOrders.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
        this.getCustomFields()
    }

    updateOrder (orders) {
        const cachedData = !this.state.cachedData.length ? orders : this.state.cachedData
        this.setState({
            orders: orders,
            cachedData: cachedData
        })
    }

    filterOrders (filters) {
        this.setState({ filters: filters })
    }

    userList (props) {
        const { orders, customers, custom_fields } = this.state
        return <OrderItem showCheckboxes={props.showCheckboxes}
            orders={orders} customers={customers}
            custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} updateOrder={this.updateOrder}
            viewId={props.viewId}
            toggleViewedEntity={props.toggleViewedEntity}
            onChangeBulk={props.onChangeBulk}/>
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

    getCustomFields () {
        axios.get('api/accounts/fields/Order')
            .then((r) => {
                this.setState({
                    custom_fields: r.data.fields
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
        const { orders, customers, custom_fields, view, filters } = this.state
        const { status_id, customer_id, searchText, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/order?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = this.state.customers.length ? <EditOrder
            custom_fields={custom_fields}
            customers={customers}
            add={true}
            action={this.updateOrder}
            orders={orders}
            modal={true}
        /> : null

        return (
            <React.Fragment>
                <div className="data-table">

                    <Card>
                        <CardBody>
                            <OrderFilters orders={orders} customers={customers}
                                filters={filters} filter={this.filterOrders}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                            {addButton}
                        </CardBody>
                    </Card>

                    <Card>
                        <CardBody>
                            <DataTable
                                customers={customers}
                                dropdownButtonActions={this.state.dropdownButtonActions}
                                entity_type="Order"
                                bulk_save_url="/api/order/bulk"
                                view={view}
                                ignore={this.state.ignoredColumns}
                                columnMapping={{ customer_id: 'CUSTOMER' }}
                                // order={['id', 'number', 'date', 'customer_name', 'total', 'balance', 'status_id']}
                                disableSorting={['id']}
                                defaultColumn='number'
                                userList={this.userList}
                                fetchUrl={fetchUrl}
                                updateState={this.updateOrder}
                            />
                        </CardBody>
                    </Card>
                </div>
            </React.Fragment>
        )
    }
}
