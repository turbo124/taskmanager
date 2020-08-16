import React, { Component } from 'react'
import AddGateway from './AddGateway'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import GatewayFilters from './GatewayFilters'
import GatewayItem from './GatewayItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'
import queryString from 'query-string'
import GatewayModel from '../models/GatewayModel'

export default class Gateways extends Component {
    constructor (props) {
        super(props)

        this.gatewayModel = new GatewayModel()

        this.state = {
            gateway_ids: this.gatewayModel.gateway_ids,
            customer_id: queryString.parse(this.props.location.search).customer_id || '',
            group_id: queryString.parse(this.props.location.search).group_id || '',
            isOpen: window.innerWidth > 670,
            error: '',
            show_success: false,
            error_message: translations.unexpected_error,
            success_message: translations.success_message,
            dropdownButtonActions: ['download'],
            gateways: [],
            cachedData: [],
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['id', 'config', 'fees_and_limits', 'account_id', 'user_id', 'updated_at', 'status', 'deleted_at', 'created_at', 'show_billing_address', 'show_shipping_address', 'require_cvv', 'accepted_credit_cards', 'update_details'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterGateways = this.filterGateways.bind(this)
    }

    addUserToState (gateways) {
        const cachedData = !this.state.cachedData.length ? gateways : this.state.cachedData
        this.setState({
            gateways: gateways,
            cachedData: cachedData
        })
    }

    filterGateways (filters) {
        this.setState({ filters: filters })
    }

    handleClose () {
        this.setState({ error: '', show_success: false })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { gateways, customer_id, group_id } = this.state

        return <GatewayItem showCheckboxes={props.showCheckboxes} gateways={gateways}
            viewId={props.viewId}
            customer_id={customer_id}
            group_id={group_id}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
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
        const { searchText, error } = this.state.filters
        const { view, gateways, isOpen, customer_id, group_id, error_message, success_message, show_success } = this.state
        const fetchUrl = `/api/company_gateways?search_term=${searchText} `
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <GatewayFilters setFilterOpen={this.setFilterOpen.bind(this)} gateways={gateways}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={this.state.filters} filter={this.filterGateways}
                                    saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                                <AddGateway
                                    customer_id={customer_id}
                                    group_id={group_id}
                                    gateways={gateways}
                                    action={this.addUserToState}
                                />
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
                        {customer_id &&
                        <Alert color="info">
                            {translations.filtered_by_customer}
                        </Alert>
                        }

                        <Card>
                            <CardBody>
                                <DataTable
                                    setSuccess={this.setSuccess.bind(this)}
                                    setError={this.setError.bind(this)}
                                    columnMapping={{ customer_id: 'CUSTOMER' }}
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="Gateway"
                                    bulk_save_url="/api/gateways/bulk"
                                    view={view}
                                    ignore={this.state.ignoredColumns}
                                    userList={this.userList}
                                    fetchUrl={fetchUrl}
                                    updateState={this.addUserToState}
                                />
                            </CardBody>
                        </Card>
                    </div>
                </div>
            </Row>
        )
    }
}
