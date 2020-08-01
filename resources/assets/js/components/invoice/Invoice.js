import React, { Component } from 'react'
import axios from 'axios'
import queryString from 'query-string'
import EditInvoice from './EditInvoice'
import {
    Card, CardBody
} from 'reactstrap'
import DataTable from '../common/DataTable'
import InvoiceItem from './InvoiceItem'
import InvoiceFilters from './InvoiceFilters'
import Drawer from '@material-ui/core/Drawer'
import Button from '@material-ui/core/Button'
import List from '@material-ui/core/List';
import Divider from '@material-ui/core/Divider';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';

export default class Invoice extends Component {
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
            invoices: [],
            cachedData: [],
            customers: [],
            bulk: [],
            dropdownButtonActions: ['download', 'cancel', 'archive', 'reverse', 'delete'],
            custom_fields: [],
            ignoredColumns: ['gateway_percentage', 'gateway_fee', 'files', 'audits', 'paymentables', 'customer_name', 'emails', 'transaction_fee', 'transaction_fee_tax', 'shipping_cost', 'shipping_cost_tax', 'design_id', 'invitations', 'id', 'user_id', 'status', 'company_id', 'custom_value1', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'terms', 'footer', 'last_send_date', 'line_items', 'next_send_date', 'last_sent_date', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total'],
            filters: {
                status_id: '',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                searchText: queryString.parse(this.props.location.search).number || '',
                start_date: '',
                end_date: ''
            },
            showRestoreButton: false,
            bottom_drawer_open: false
        }

        this.updateInvoice = this.updateInvoice.bind(this)
        this.userList = this.userList.bind(this)
        this.filterInvoices = this.filterInvoices.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
        this.getCustomFields()
    }

    updateInvoice (invoices) {
        const cachedData = !this.state.cachedData.length ? invoices : this.state.cachedData
        this.setState({
            invoices: invoices,
            cachedData: cachedData
        })
    }

    filterInvoices (filters) {
        this.setState({ filters: filters })
    }

    userList (props) {
        const { invoices, customers, custom_fields } = this.state
        return <InvoiceItem showCheckboxes={props.showCheckboxes}
            invoices={invoices} customers={customers}
            custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} updateInvoice={this.updateInvoice}
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
        axios.get('api/accounts/fields/Invoice')
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

    toggleDrawer () {
        this.setState({ bottom_drawer_open: !this.state.bottom_drawer_open })
    }

    render () {
        const { invoices, customers, custom_fields, view, filters } = this.state
        const { status_id, customer_id, searchText, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/invoice?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = this.state.customers.length ? <EditInvoice
            custom_fields={custom_fields}
            customers={customers}
            add={true}
            action={this.updateInvoice}
            invoices={invoices}
            modal={true}
        /> : null

        return (
            <React.Fragment>
                <div className="data-table">

                    <Card>
                        <CardBody>
                            <InvoiceFilters invoices={invoices} customers={customers}
                                filters={filters} filter={this.filterInvoices}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                            {addButton}
                        </CardBody>
                    </Card>

                    <Card>
                        <CardBody>
                            <DataTable
                                customers={customers}
                                dropdownButtonActions={this.state.dropdownButtonActions}
                                entity_type="Invoice"
                                bulk_save_url="/api/invoice/bulk"
                                view={view}
                                ignore={this.state.ignoredColumns}
                                columnMapping={{ customer_id: 'CUSTOMER' }}
                                // order={['id', 'number', 'date', 'customer_name', 'total', 'balance', 'status_id']}
                                disableSorting={['id']}
                                defaultColumn='number'
                                userList={this.userList}
                                fetchUrl={fetchUrl}
                                updateState={this.updateInvoice}
                            />
                        </CardBody>
                    </Card>
                </div>

                <Button onClick={this.toggleDrawer.bind(this)}>bottom</Button>
                <Drawer anchor="bottom" open={this.state.bottom_drawer_open} onClose={this.toggleDrawer.bind(this)}>
                    <List>
                        {['All mail', 'Trash', 'Spam'].map((text, index) => (
                            <ListItem button key={text}>
                                {/*<ListItemIcon></ListItemIcon>*/}
                                <ListItemText primary={text} />
                            </ListItem>
                        ))}
                    </List>
                </Drawer>
            </React.Fragment>
        )
    }
}
