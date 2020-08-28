import React, { Component } from 'react'
import DataTable from '../common/DataTable'
import axios from 'axios'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import CreditFilters from './CreditFilters'
import CreditItem from './CreditItem'
import EditCredit from './edit/EditCredit'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Credits extends Component {
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
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            credits: [],
            cachedData: [],
            customers: [],
            custom_fields: [],
            dropdownButtonActions: ['download', 'email'],
            bulk: [],
            ignoredColumns: ['currency_id', 'exchange_rate', 'account_id', 'gateway_fee', 'gateway_percentage', 'files', 'audits', 'customer_name', 'emails', 'due_date', 'assigned_to', 'invoice_id', 'transaction_fee', 'transaction_fee_tax', 'shipping_cost', 'shipping_cost_tax', 'design_id', 'invitations', 'id', 'user_id', 'status', 'company_id', 'custom_value1', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'terms', 'footer', 'last_send_date', 'line_items', 'next_send_date', 'last_sent_date', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total'],
            filters: {
                status_id: 'active',
                customer_id: '',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            showRestoreButton: false
        }

        this.updateCustomers = this.updateCustomers.bind(this)
        this.customerList = this.customerList.bind(this)
        this.filterCredits = this.filterCredits.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
        this.getCustomFields()
    }

    filterCredits (filters) {
        this.setState({ filters: filters })
    }

    handleClose () {
        this.setState({ error: '', show_success: false })
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
        axios.get('api/accounts/fields/Credit')
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

    updateCustomers (credits) {
        const cachedData = !this.state.cachedData.length ? credits : this.state.cachedData
        this.setState({
            credits: credits,
            cachedData: cachedData
        })
    }

    customerList (props) {
        const { credits, customers, custom_fields } = this.state
        return <CreditItem showCheckboxes={props.showCheckboxes} credits={credits} customers={customers}
            custom_fields={custom_fields}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} updateCustomers={this.updateCustomers}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
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
        const { customers, credits, custom_fields, view, filters, error, isOpen, error_message, success_message, show_success } = this.state
        const fetchUrl = `/api/credits?search_term=${this.state.filters.searchText}&status=${this.state.filters.status_id}&customer_id=${this.state.filters.customer_id} &start_date=${this.state.filters.start_date}&end_date=${this.state.filters.end_date}`
        const addButton = customers.length ? <EditCredit
            custom_fields={custom_fields}
            customers={customers}
            add={true}
            action={this.updateCustomers}
            credits={credits}
            modal={true}
        /> : null
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return customers.length ? (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <CreditFilters setFilterOpen={this.setFilterOpen.bind(this)} credits={credits}
                                    customers={customers}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={filters} filter={this.filterCredits}
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
                                    entity_type="Credit"
                                    bulk_save_url="/api/credit/bulk"
                                    view={view}
                                    columnMapping={{ customer_id: 'CUSTOMER' }}
                                    ignore={this.state.ignoredColumns}
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
        ) : null
    }
}
