import React, { Component } from 'react'
import AddGateway from './AddGateway'
import { CardBody, Card, Alert } from 'reactstrap'
import DataTable from '../common/DataTable'
import GatewayFilters from './GatewayFilters'
import GatewayItem from './GatewayItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Gateways extends Component {
    constructor (props) {
        super(props)

        this.state = {
            error: '',
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
        console.log('gateways', gateways)
        const cachedData = !this.state.cachedData.length ? gateways : this.state.cachedData
        this.setState({
            gateways: gateways,
            cachedData: cachedData
        })
    }

    filterGateways (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { gateways } = this.state

        return <GatewayItem showCheckboxes={props.showCheckboxes} gateways={gateways}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    render () {
        const { searchText, status, start_date, end_date, error } = this.state.filters
        const { view, gateways } = this.state
        const fetchUrl = `/api/company_gateways?search_term=${searchText} `
        const margin_class = Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed) === true
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <React.Fragment>
                <div className="topbar">
                    <Card>
                        <CardBody>
                            <GatewayFilters gateways={gateways}
                                updateIgnoredColumns={this.updateIgnoredColumns}
                                filters={this.state.filters} filter={this.filterGateways}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                            <AddGateway
                                gateways={gateways}
                                action={this.addUserToState}
                            />
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
            </React.Fragment>
        )
    }
}
