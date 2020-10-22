import React, { Component } from 'react'
import AddGateway from './edit/AddGateway'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import GatewayItem from './GatewayItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../utils/_translations'
import queryString from 'query-string'
import GatewayModel from '../models/GatewayModel'
import axios from 'axios'
import CustomerModel from '../models/CustomerModel'
import GroupModel from '../models/GroupModel'
import AccountModel from '../models/AccountModel'
import FormBuilder from '../settings/FormBuilder'
import Header from '../settings/Header'
import { getSettingsIcon, icons } from '../utils/_icons'
import BlockButton from '../common/BlockButton'

export default class GatewaySettings extends Component {
    constructor (props) {
        super(props)

        this.gatewayModel = new GatewayModel()

        this.state = {
            success: false,
            error: false,
            loaded: false,
            settings: {},
            gateway_ids: this.gatewayModel.gateway_ids.split(','),
            customer_id: queryString.parse(this.props.location.search).customer_id || '',
            group_id: queryString.parse(this.props.location.search).group_id || '',
            isOpen: window.innerWidth > 670,
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
            ignoredColumns: ['gateway', 'id', 'config', 'fees_and_limits', 'account_id', 'user_id', 'updated_at', 'status', 'deleted_at', 'created_at', 'show_billing_address', 'show_shipping_address', 'require_cvv', 'accepted_credit_cards', 'update_details'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.account_id = JSON.parse(localStorage.getItem('appState')).user.account_id

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterGateways = this.filterGateways.bind(this)
        this.setList = this.setList.bind(this)
        this.removeFromList = this.removeFromList.bind(this)
        this.save = this.save.bind(this)
        this.loadCustomer = this.loadCustomer.bind(this)
        this.loadGroup = this.loadGroup.bind(this)
        this.loadAccount = this.loadAccount.bind(this)
        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
    }

    componentDidMount () {
        if (this.state.customer_id.length) {
            this.loadCustomer()
        } else if (this.state.group_id.length) {
            this.loadGroup()
        }

        this.loadAccount()
    }

    handleSettingsChange (event) {
        const name = event.target.name
        const value = event.target.value

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }))
    }

    handleSubmit (e) {
        const formData = new FormData()
        formData.append('settings', JSON.stringify(this.state.settings))
        formData.append('_method', 'PUT')

        axios.post(`/api/accounts/${this.account_id}`, formData, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then((response) => {
                this.setState({ success: true })
            })
            .catch((error) => {
                this.setState({ error: true })
            })
    }

    loadCustomer () {
        axios.get(`/api/customers/${this.state.customer_id}`)
            .then((r) => {
                console.log('data', r.data)
                this.model = new CustomerModel(r.data)
                this.setState({ gateway_ids: this.model.gateways })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    loadGroup () {
        axios.get(`/api/group/${this.state.group_id}`)
            .then((r) => {
                console.log('data', r.data)
                this.model = new GroupModel(r.data)
                this.setState({ gateway_ids: this.model.gateways })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    loadAccount () {
        axios.get(`/api/accounts/${this.account_id}`)
            .then((response) => {
                this.model = new AccountModel(response.data)
                this.setState({ loaded: true, gateway_ids: this.model.gateways, settings: response.data.settings })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    addUserToState (gateways) {
        const cachedData = !this.state.cachedData.length ? gateways : this.state.cachedData
        this.setState({
            gateways: gateways,
            cachedData: cachedData
        })
    }

    save () {
        this.model.saveSettings().then(response => {
            if (!response) {
                this.setState({
                    showErrorMessage: true,
                    loading: false,
                    errors: this.model.errors,
                    message: this.model.error_message
                })
            }
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
        const { gateways, customer_id, group_id, gateway_ids } = this.state

        return <GatewayItem removeFromList={this.removeFromList}
            isFiltered={this.state.customer_id.length || this.state.group_id.length}
            setList={this.setList}
            gateway_ids={gateway_ids}
            showCheckboxes={props.showCheckboxes}
            gateways={gateways}
            viewId={props.viewId}
            customer_id={customer_id}
            group_id={group_id}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    getBillingFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'under_payments_allowed',
                    label: translations.under_payments_allowed,
                    type: 'switch',
                    placeholder: translations.under_payments_allowed,
                    value: settings.under_payments_allowed,
                    help_text: translations.under_payments_allowed_help_text
                },
                {
                    name: 'over_payments_allowed',
                    label: translations.over_payments_allowed,
                    type: 'switch',
                    placeholder: translations.over_payments_allowed,
                    value: settings.over_payments_allowed,
                    help_text: translations.over_payments_allowed_help_text
                },
                {
                    name: 'credit_payments_enabled',
                    label: translations.credit_payments_enabled,
                    type: 'switch',
                    placeholder: translations.credit_payments_enabled,
                    value: settings.credit_payments_enabled,
                    help_text: translations.over_payments_allowed_help_text
                },
                {
                    name: 'minimum_amount_required',
                    label: translations.minimum_amount_required,
                    type: 'text',
                    placeholder: translations.minimum_amount_required,
                    value: settings.minimum_amount_required
                }
            ]
        ]
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

    arraysEqual (arr1, arr2) {
        if (arr1.length !== arr2.length) {
            return false
        }
        for (var i = arr1.length; i--;) {
            if (arr1[i] != arr2[i]) {
                return false
            }
        }

        return true
    }

    removeFromList (gateway, archive = false) {
        const gateway_ids = this.model.removeGateway(gateway)

        this.setState({ gateway_ids: gateway_ids }, () => {
            setTimeout(() => {
                this.save()
            }, 2000)
        })
    }

    setList (list) {
        const ids = []
        list.map(gateway => {
            ids.push(gateway.id)
        })

        const has_changed = this.arraysEqual(ids, this.state.gateway_ids)

        this.setState({ gateway_ids: ids }, () => {
            if (has_changed) {
                return
            }

            this.model.gateway_ids = ids
            console.log('ids', ids)

            setTimeout(() => {
                this.save()
            }, 2000)
        })
    }

    render () {
        const { error } = this.state.filters
        const { error_message, success_message, show_success } = this.state
        const margin_class = 'fixed-margin-extra border-0 card'

        return (
            <Row>
                <div className="col-12">
                    <Snackbar open={this.state.success} autoHideDuration={3000}
                        onClose={this.handleClose.bind(this)}>
                        <Alert severity="success">
                            {translations.settings_saved}
                        </Alert>
                    </Snackbar>

                    <Snackbar open={this.state.error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                        <Alert severity="danger">
                            {translations.settings_not_saved}
                        </Alert>
                    </Snackbar>

                    <Header title={translations.customer_portal} handleSubmit={this.handleSubmit.bind(this)}/>

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
                        {!!this.state.loaded &&
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getBillingFields()}
                                />
                            </CardBody>
                        </Card>
                        }
                    </div>

                    <BlockButton icon={getSettingsIcon('gateway-settings')} button_text={translations.configure_gateways}
                        button_link="/#/gateways"/>
                </div>
            </Row>
        )
    }
}
