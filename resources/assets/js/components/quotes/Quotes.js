import React, { Component } from 'react'
import axios from 'axios'
import EditQuote from './EditQuote'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import QuoteItem from './QuoteItem'
import QuoteFilters from './QuoteFilters'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Quotes extends Component {
    constructor (props) {
        super(props)
        this.state = {
            isOpen: window.innerWidth > 670,
            error: '',
            per_page: 5,
            view: {
                ignore: ['user_id', 'next_send_date', 'updated_at', 'use_inclusive_taxes', 'last_sent_date', 'uses_inclusive_taxes', 'line_items', 'next_sent_date', 'first_name', 'last_name', 'design_id', 'status_id', 'custom_surcharge_tax1', 'custom_surcharge_tax2'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            quotes: [],
            cachedData: [],
            customers: [],
            custom_fields: [],
            bulk: [],
            dropdownButtonActions: ['download', 'clone_quote_to_invoice'],
            filters: {
                status_id: 'active',
                customer_id: '',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            showRestoreButton: false,
            ignoredColumns: ['account_id', 'assigned_to', 'gateway_fee', 'gateway_percentage', 'files', 'shipping_cost_tax', 'audits', 'user_id', 'customer_name', 'emails', 'transaction_fee', 'transaction_fee_tax', 'shipping_cost', 'custom_surcharge_tax2', 'design_id', 'invitations', 'next_send_date', 'id', 'company_id', 'custom_value1', 'invoice_id', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'use_inclusive_taxes', 'terms', 'footer', 'last_sent_date', 'uses_inclusive_taxes', 'line_items', 'next_sent_date', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total']

        }

        this.updateInvoice = this.updateInvoice.bind(this)
        this.userList = this.userList.bind(this)
        this.filterInvoices = this.filterInvoices.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
        this.getCustomFields()
    }

    updateInvoice (quotes) {
        const cachedData = !this.state.cachedData.length ? quotes : this.state.cachedData
        this.setState({
            quotes: quotes,
            cachedData: cachedData
        })
    }

    filterInvoices (filters) {
        this.setState({ filters: filters })
    }

    handleClose () {
        this.setState({ error: '' })
    }

    userList (props) {
        const { quotes, custom_fields, customers } = this.state
        return <QuoteItem showCheckboxes={props.showCheckboxes} quotes={quotes} customers={customers}
            custom_fields={custom_fields}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} updateInvoice={this.updateInvoice}
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
        axios.get('api/accounts/fields/Quote')
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

    render () {
        const { quotes, custom_fields, customers, view, filters, error, isOpen } = this.state
        const { status_id, customer_id, searchText, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/quote?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = customers.length ? <EditQuote
            custom_fields={custom_fields}
            customers={customers}
            invoice={{}}
            add={true}
            action={this.updateInvoice}
            invoices={quotes}
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
                                <QuoteFilters setFilterOpen={this.setFilterOpen.bind(this)} quotes={quotes}
                                    customers={customers}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={filters} filter={this.filterInvoices}
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
                                    entity_type="Quote"
                                    bulk_save_url="/api/quote/bulk"
                                    view={view}
                                    columnMapping={{ status_id: 'STATUS', customer_id: 'CUSTOMER' }}
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
