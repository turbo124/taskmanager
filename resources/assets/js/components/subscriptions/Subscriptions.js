import React, { Component } from 'react'
import axios from 'axios'
import AddSubscription from './AddSubscription'
import { CardBody, Card, Alert, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import SubscriptionFilters from './SubscriptionFilters'
import SubscriptionItem from './SubscriptionItem'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Subscriptions extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: window.innerWidth > 670,
            error: '',
            dropdownButtonActions: ['download'],
            subscriptions: [],
            cachedData: [],
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['updated_at', 'is_deleted', 'user_id', 'account_id', 'id', 'entity_id', 'settings', 'deleted_at', 'created_at'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterSubscriptions = this.filterSubscriptions.bind(this)
    }

    addUserToState (subscriptions) {
        const cachedData = !this.state.cachedData.length ? subscriptions : this.state.cachedData
        this.setState({
            subscriptions: subscriptions,
            cachedData: cachedData
        })
    }

    filterSubscriptions (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { subscriptions } = this.state
        return <SubscriptionItem showCheckboxes={props.showCheckboxes} subscriptions={subscriptions}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    getUsers () {
        axios.get('api/users')
            .then((r) => {
                this.setState({
                    users: r.data
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    handleClose () {
        this.setState({ error: '' })
    }

    setFilterOpen (isOpen) {
        this.setState({ isOpen: isOpen })
    }

    render () {
        const { searchText, status, start_date, end_date } = this.state.filters
        const { view, subscriptions, error, isOpen } = this.state
        const fetchUrl = `/api/subscriptions?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <SubscriptionFilters setFilterOpen={this.setFilterOpen.bind(this)} subscriptions={subscriptions}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={this.state.filters} filter={this.filterSubscriptions}
                                    saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                                <AddSubscription
                                    subscriptions={subscriptions}
                                    action={this.addUserToState}
                                />
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
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="Subscription"
                                    bulk_save_url="/api/subscriptions/bulk"
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
