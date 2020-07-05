import React, { Component } from 'react'
import axios from 'axios'
import AddToken from './AddToken'
import { CardBody, Card } from 'reactstrap'
import DataTable from '../common/DataTable'
import TokenFilters from './TokenFilters'
import TokenItem from './TokenItem'

export default class Tokens extends Component {
    constructor (props) {
        super(props)

        this.state = {
            dropdownButtonActions: ['download'],
            tokens: [],
            cachedData: [],
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['settings', 'deleted_at', 'created_at', 'updated_at', 'archived_at', 'is_deleted'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterTokens = this.filterTokens.bind(this)
    }

    addUserToState (tokens) {
        const cachedData = !this.state.cachedData.length ? tokens : this.state.cachedData
        this.setState({
            tokens: tokens,
            cachedData: cachedData
        })
    }

    filterTokens (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { tokens } = this.state
        return <TokenItem showCheckboxes={props.showCheckboxes} tokens={tokens}
            viewId={props.viewId}
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
        const { view, tokens } = this.state
        const fetchUrl = `/api/tokens?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `

        return (
            <div className="data-table">

                <Card>
                    <CardBody>
                        <TokenFilters tokens={tokens}
                            updateIgnoredColumns={this.updateIgnoredColumns}
                            filters={this.state.filters} filter={this.filterTokens}
                            saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                        <AddToken
                            tokens={tokens}
                            action={this.addUserToState}
                        />
                    </CardBody>
                </Card>

                <Card>
                    <CardBody>
                        <DataTable
                            dropdownButtonActions={this.state.dropdownButtonActions}
                            entity_type="Token"
                            bulk_save_url="/api/tokens/bulk"
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
