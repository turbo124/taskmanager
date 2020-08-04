import React, { Component } from 'react'
import DataTable from '../common/DataTable'
import AddPayment from './AddPayment'
import {
    Alert,
    Card, CardBody, Row
} from 'reactstrap'
import axios from 'axios'
import PaymentItem from './PaymentItem'
import PaymentFilters from './PaymentFilters'
import queryString from 'query-string'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Payments extends Component {
    constructor (props) {
        super(props)
        this.state = {
            isOpen: window.innerWidth > 670,
            error: '',
            per_page: 5,
            view: {
                ignore: ['paymentables', 'assigned_to', 'id', 'customer', 'invoice_id', 'applied', 'deleted_at', 'customer_id', 'refunded', 'task_id', 'company_id'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            payments: [],
            cachedData: [],
            custom_fields: [],
            dropdownButtonActions: ['download'],
            bulk: [],
            ignoredColumns: ['customer_name', 'custom_value1', 'custom_value2', 'custom_value3', 'custom_value4', 'currency_id', 'exchange_rate', 'exchange_currency_id', 'paymentables', 'private_notes', 'created_at', 'user_id', 'id', 'customer', 'invoice_id', 'assigned_to', 'deleted_at', 'updated_at', 'type_id', 'refunded', 'is_manual', 'task_id', 'company_id', 'invitation_id'],
            filters: {
                status_id: 'active',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                searchText: queryString.parse(this.props.location.search).number || '',
                start_date: '',
                end_date: ''
            },
            invoices: [],
            credits: [],
            customers: [],
            showRestoreButton: false
        }

        this.updateCustomers = this.updateCustomers.bind(this)
        this.customerList = this.customerList.bind(this)
        this.getInvoices = this.getInvoices.bind(this)
        this.getCredits = this.getCredits.bind(this)
        this.filterPayments = this.filterPayments.bind(this)
    }

    componentDidMount () {
        this.getInvoices()
        this.getCredits()
        this.getCustomers()
        this.getCustomFields()
    }

    handleClose () {
        this.setState({ error: '' })
    }

    getCustomFields () {
        axios.get('api/accounts/fields/Payment')
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

    getInvoices () {
        axios.get('/api/invoice')
            .then((r) => {
                this.setState({
                    invoices: r.data
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    getCredits () {
        axios.get('/api/credits')
            .then((r) => {
                this.setState({
                    credits: r.data
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
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
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    updateCustomers (payments) {
        const cachedData = !this.state.cachedData.length ? payments : this.state.cachedData
        this.setState({
            payments: payments,
            cachedData: cachedData
        })
    }

    filterPayments (filters) {
        this.setState({ filters: filters })
    }

    customerList (props) {
        const { payments, custom_fields, invoices, credits, customers } = this.state
        return <PaymentItem showCheckboxes={props.showCheckboxes} payments={payments} customers={customers}
            viewId={props.viewId}
            credits={credits}
            invoices={invoices} custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} updateCustomers={this.updateCustomers}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    setFilterOpen (isOpen) {
        this.setState({ isOpen: isOpen })
    }

    render () {
        const { payments, custom_fields, invoices, credits, view, filters, customers, error, isOpen } = this.state
        const { status_id, searchText, customer_id, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/payments?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = invoices.length ? <AddPayment
            custom_fields={custom_fields}
            invoices={invoices}
            credits={credits}
            action={this.updateCustomers}
            payments={payments}
        /> : null
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return <Row>
            <div className="col-12">
                <div className="topbar">
                    <Card>
                        <CardBody>
                            <PaymentFilters setFilterOpen={this.setFilterOpen.bind(this)} customers={customers} payments={payments} invoices={invoices}
                                updateIgnoredColumns={this.updateIgnoredColumns}
                                filters={filters} filter={this.filterPayments}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                            {addButton}
                        </CardBody>
                    </Card>
                </div>

                {error &&
            <Snackbar open={error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
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
                                entity_type="Payment"
                                bulk_save_url="/api/payment/bulk"
                                view={view}
                                ignore={this.state.ignoredColumns}
                                columnMapping={{ customer_id: 'CUSTOMER' }}
                                // order={['id', 'number', 'date', 'customer_name', 'total', 'balance', 'status_id']}
                                disableSorting={['id']}
                                defaultColumn='number'
                                userList={this.customerList}
                                fetchUrl={fetchUrl}
                                updateState={this.updateCustomers}
                            />
                        </CardBody>
                    </Card>
                </div>
            </div>
        </Row>
    }
}
