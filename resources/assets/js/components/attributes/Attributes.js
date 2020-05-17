import React, { Component } from 'react'
import axios from 'axios'
import AddAttribute from './AddAttribute'
import { CardBody, Card } from 'reactstrap'
import DataTable from '../common/DataTable'
import AttributeFilters from './AttributeFilters'
import AttributeItem from './AttributeItem'

export default class Attributes extends Component {
    constructor (props) {
        super(props)

        this.state = {
            dropdownButtonActions: ['download'],
            attributes: [],
            cachedData: [],
            view: {
                ignore: ['values'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['values'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterAttributes = this.filterAttributes.bind(this)
    }

    addUserToState (attributes) {
        const cachedData = !this.state.cachedData.length ? attributes : this.state.cachedData
        this.setState({
            attributes: attributes,
            cachedData: cachedData
        })
    }

    filterAttributes (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { attributes } = this.state
        console.log('attributes', attributes)
        return <AttributeItem showCheckboxes={props.showCheckboxes} attributes={attributes}
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
        const { view, attributes } = this.state
        const fetchUrl = `/api/attributes?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date} `

        return (
            <div className="data-table">

                <Card>
                    <CardBody>
                        <AttributeFilters attributes={attributes}
                            updateIgnoredColumns={this.updateIgnoredColumns}
                            filters={this.state.filters} filter={this.filterAttributes}
                            saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                        <AddAttribute
                            attributes={attributes}
                            action={this.addUserToState}
                        />

                        <DataTable
                            dropdownButtonActions={this.state.dropdownButtonActions}
                            entity_type="Attribute"
                            bulk_save_url="/api/attributes/bulk"
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
