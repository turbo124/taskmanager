import React, { Component } from 'react'
import axios from 'axios'
import EditOrder from './edit/EditOrder'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import OrderItem from './OrderItem'
import OrderFilters from './OrderFilters'
import queryString from 'query-string'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../utils/_translations'
import CustomerRepository from '../repositories/CustomerRepository'

export default class Order extends Component {
    constructor (props) {
        super(props)
        this.state = {
            isOpen: window.innerWidth > 670,
            error: '',
            show_success: false,
            error_message: translations.unexpected_error,
            success_message: translations.success_message,
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
            dropdownButtonActions: ['currency_id', 'exchange_rate', 'email', 'download', 'hold_order', 'unhold_order', 'archive', 'mark_sent', 'delete'],
            custom_fields: [],
            ignoredColumns: ['tax_rate', 'tax_rate_name', 'tax_2', 'tax_3', 'tax_rate_name_2', 'tax_rate_name_3', 'account_id', 'gateway_fee', 'gateway_percentage', 'files', 'audits', 'invoice_id', 'customer_name', 'emails', 'transaction_fee', 'transaction_fee_tax', 'shipping_cost', 'shipping_cost_tax', 'design_id', 'invitations', 'id', 'user_id', 'status', 'company_id', 'custom_value1', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'terms', 'footer', 'last_send_date', 'line_items', 'next_send_date', 'last_sent_date', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total'],
            filters: {
                status_id: 'active',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                searchText: queryString.parse(this.props.location.search).number || '',
                start_date: '',
                end_date: ''
            },
            showRestoreButton: false,
            entity_id: queryString.parse(this.props.location.search).entity_id || false,
            entity_type: queryString.parse(this.props.location.search).entity_type || false
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
        this.setState({ error: '', show_success: false })
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
        const customerRepository = new CustomerRepository()
        customerRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ customers: response }, () => {
                console.log('customers', this.state.customers)
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

    setFilterOpen (isOpen) {
        this.setState({ isOpen: isOpen })
    }

    setError (message = null) {
        this.setState({ error: true, error_message: message === null ? translations.unexpected_error : message })
    }

    setSuccess (message = null) {
        this.setState({
            show_success: true,
            success_message: message === null ? translations.success_message : message
        })
    }

    render () {
        const { orders, customers, custom_fields, view, filters, error, isOpen, error_message, success_message, show_success } = this.state
        const { status_id, customer_id, searchText, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/order?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = this.state.customers.length ? <EditOrder
            entity_id={this.state.entity_id}
            entity_type={this.state.entity_type}
            custom_fields={custom_fields}
            customers={customers}
            add={true}
            action={this.updateOrder}
            orders={orders}
            modal={true}
        /> : null
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <OrderFilters setFilterOpen={this.setFilterOpen.bind(this)} orders={orders}
                                    customers={customers}
                                    filters={filters} filter={this.filterOrders}
                                    saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                                {addButton}
                            </CardBody>
                        </Card>
                    </div>

                    {error &&
                    <Snackbar open={error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                        <Alert severity="danger">
                            {error_message}
                        </Alert>
                    </Snackbar>
                    }

                    {show_success &&
                    <Snackbar open={show_success} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                        <Alert severity="success">
                            {success_message}
                        </Alert>
                    </Snackbar>
                    }

                    <div className={margin_class}>
                        <Card>
                            <CardBody>
                                <DataTable
                                    setSuccess={this.setSuccess.bind(this)}
                                    setError={this.setError.bind(this)}
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
                </div>
            </Row>
        )
    }
}
