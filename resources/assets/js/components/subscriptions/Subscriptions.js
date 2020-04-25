import React, { Component } from 'react'
import axios from 'axios'
import AddSubscription from './AddSubscription'
import { CardBody, Card } from 'reactstrap'
import DataTable from '../common/DataTable'
import SubscriptionFilters from './SubscriptionFilters'
import SubscriptionItem from './SubscriptionItem'

export default class Subscriptions extends Component {
    constructor (props) {
        super(props)

        this.state = {
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
            ignoredColumns: ['settings', 'deleted_at', 'created_at'],
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
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
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
                    err: e
                })
            })
    }

    render () {
        const { searchText, status, start_date, end_date } = this.state.filters
        const { view, subscriptions } = this.state
        const fetchUrl = `/api/subscriptions?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `

        return (
            <div className="data-table">

                <Card>
                    <CardBody>
                        <SubscriptionFilters subscriptions={subscriptions}
                            updateIgnoredColumns={this.updateIgnoredColumns}
                            filters={this.state.filters} filter={this.filterSubscriptions}
                            saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                        <AddSubscription
                            subscriptions={subscriptions}
                            action={this.addUserToState}
                        />

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
        )
    }
}
