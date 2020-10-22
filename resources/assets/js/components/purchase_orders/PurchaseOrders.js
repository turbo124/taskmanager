import React, { Component } from 'react'
import axios from 'axios'
import EditPurchaseOrder from './edit/EditPurchaseOrder'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import PurchaseOrderItem from './PurchaseOrderItem'
import PurchaseOrderFilters from './PurchaseOrderFilters'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../utils/_translations'
import CompanyRepository from '../repositories/CompanyRepository'
import queryString from 'query-string'

export default class PurchaseOrders extends Component {
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
                ignore: ['user_id', 'next_send_date', 'updated_at', 'use_inclusive_taxes', 'last_sent_date', 'uses_inclusive_taxes', 'line_items', 'next_sent_date', 'first_name', 'last_name', 'design_id', 'status_id', 'custom_surcharge_tax1', 'custom_surcharge_tax2'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            purchase_orders: [],
            cachedData: [],
            companies: [],
            custom_fields: [],
            bulk: [],
            dropdownButtonActions: ['email', 'download', 'clone_quote_to_invoice'],
            filters: {
                status_id: 'active',
                company_id: queryString.parse(this.props.location.search).company_id || '',
                project_id: queryString.parse(this.props.location.search).project_id || '',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            showRestoreButton: false,
            entity_id: queryString.parse(this.props.location.search).entity_id || false,
            entity_type: queryString.parse(this.props.location.search).entity_type || false,
            ignoredColumns: ['tax_rate', 'tax_rate_name', 'tax_2', 'tax_3', 'tax_rate_name_2', 'tax_rate_name_3', 'currency_id', 'exchange_rate', 'account_id', 'assigned_to', 'gateway_fee', 'gateway_percentage', 'files', 'shipping_cost_tax', 'audits', 'user_id', 'customer_name', 'emails', 'transaction_fee', 'transaction_fee_tax', 'shipping_cost', 'custom_surcharge_tax2', 'design_id', 'invitations', 'next_send_date', 'id', 'custom_value1', 'invoice_id', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'use_inclusive_taxes', 'terms', 'footer', 'last_sent_date', 'uses_inclusive_taxes', 'line_items', 'next_sent_date', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total']

        }

        this.updateInvoice = this.updateInvoice.bind(this)
        this.userList = this.userList.bind(this)
        this.filterInvoices = this.filterInvoices.bind(this)
    }

    componentDidMount () {
        this.getCompanies()
        this.getCustomFields()
    }

    updateInvoice (purchase_orders) {
        const cachedData = !this.state.cachedData.length ? purchase_orders : this.state.cachedData
        this.setState({
            purchase_orders: purchase_orders,
            cachedData: cachedData
        })
    }

    filterInvoices (filters) {
        this.setState({ filters: filters })
    }

    handleClose () {
        this.setState({ error: '', show_success: false })
    }

    userList (props) {
        const { purchase_orders, custom_fields, companies } = this.state
        return <PurchaseOrderItem showCheckboxes={props.showCheckboxes} purchase_orders={purchase_orders}
            companies={companies}
            custom_fields={custom_fields}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} updateInvoice={this.updateInvoice}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    getCompanies () {
        const companyRepository = new CompanyRepository()
        companyRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ companies: response }, () => {
                console.log('companies', this.state.companies)
            })
        })
    }

    getCustomFields () {
        axios.get('api/accounts/fields/PurchaseOrder')
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
        const { purchase_orders, custom_fields, companies, view, filters, error, isOpen, error_message, success_message, show_success } = this.state
        const { status_id, company_id, searchText, start_date, end_date, project_id } = this.state.filters
        const fetchUrl = `/api/purchase_order?search_term=${searchText}&status=${status_id}&company_id=${company_id}&project_id=${project_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = companies.length ? <EditPurchaseOrder
            entity_id={this.state.entity_id}
            entity_type={this.state.entity_type}
            custom_fields={custom_fields}
            companies={companies}
            invoice={{}}
            add={true}
            action={this.updateInvoice}
            invoices={purchase_orders}
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
                                <PurchaseOrderFilters setFilterOpen={this.setFilterOpen.bind(this)}
                                    purchase_orders={purchase_orders}
                                    companies={companies}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={filters} filter={this.filterInvoices}
                                    saveBulk={this.saveBulk}
                                    ignoredColumns={this.state.ignoredColumns}/>
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
                                    companies={companies}
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="PurchaseOrder"
                                    bulk_save_url="/api/purchase_order/bulk"
                                    view={view}
                                    columnMapping={{ status_id: 'STATUS', company_id: 'COMPANY' }}
                                    ignore={this.state.ignoredColumns}
                                    disableSorting={['id']}
                                    defaultColumn='number'
                                    userList={this.userList}
                                    fetchUrl={fetchUrl}
                                    updateState={this.updateInvoice}
                                />
                            </CardBody>
                        </Card>
                    </div>
                </div>
            </Row>
        )
    }
}
