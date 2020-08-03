import React, { Component } from 'react'
import axios from 'axios'
import EditOrder from './EditOrder'
import {
    Alert,
    Card, CardBody
} from 'reactstrap'
import DataTable from '../common/DataTable'
import OrderItem from './OrderItem'
import OrderFilters from './OrderFilters'
import queryString from 'query-string'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Order extends Component {
    constructor (props) {
        super(props)
        this.state = {
            error: '',
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
            dropdownButtonActions: ['download', 'hold_order', 'unhold_order', 'archive', 'mark_sent', 'delete'],
            custom_fields: [],
            ignoredColumns: ['gateway_fee', 'gateway_percentage', 'files', 'audits', 'invoice_id', 'customer_name', 'emails', 'transaction_fee', 'transaction_fee_tax', 'shipping_cost', 'shipping_cost_tax', 'design_id', 'invitations', 'id', 'user_id', 'status', 'company_id', 'custom_value1', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'terms', 'footer', 'last_send_date', 'line_items', 'next_send_date', 'last_sent_date', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total'],
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

    handleClose () {
        this.setState({ error: '' })
    }

    userList (props) {
        const { orders, customers, custom_fields } = this.state
        return <OrderItem showCheckboxes={props.showCheckboxes}
            orders={orders} customers={customers}
            custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} updateOrder={this.updateOrder}
            viewId={props.viewId}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
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
                this.setState({
                    loading: false,
                    error: e
                })
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
                    error: e
                })
            })
    }

    render () {
        const { orders, customers, custom_fields, view, filters, error } = this.state
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
        const margin_class = Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed) === true
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <React.Fragment>
                <div className="topbar">
                    <Card>
                        <CardBody>
                            <OrderFilters orders={orders} customers={customers}
                                filters={filters} filter={this.filterOrders}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                            {addButton}
                        </CardBody>
                    </Card>
                </div>

                {error &&
                    <Snackbar open={this.state.error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                        <Alert severity="danger">
                            {translations.unexpected_error}
                        </Alert>
                    </Snackbar>
                }

                <div className={margin_class}>
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
