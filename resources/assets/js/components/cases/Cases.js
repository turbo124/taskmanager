import React, { Component } from 'react'
import axios from 'axios'
import AddCase from './AddCase'
import { CardBody, Card, Alert } from 'reactstrap'
import DataTable from '../common/DataTable'
import CaseFilters from './CaseFilters'
import CaseItem from './CaseItem'
import queryString from 'query-string'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Cases extends Component {
    constructor (props) {
        super(props)

        this.state = {
            error: '',
            dropdownButtonActions: ['download'],
            customers: [],
            cases: [],
            cachedData: [],
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            errors: [],
            ignoredColumns: ['customer_name', 'files', 'private_notes', 'id', 'category_id', 'account_id', 'user_id', 'is_deleted', 'updated_at', 'settings', 'deleted_at', 'created_at'],
            filters: {
                searchText: '',
                status: 'active',
                start_date: '',
                end_date: '',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                category_id: queryString.parse(this.props.location.search).category_id || '',
                priority_id: queryString.parse(this.props.location.search).priority_id || ''
            }
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterCases = this.filterCases.bind(this)
        this.getCustomers = this.getCustomers.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
    }

    addUserToState (cases) {
        const cachedData = !this.state.cachedData.length ? cases : this.state.cachedData
        this.setState({
            cases: cases,
            cachedData: cachedData
        })
    }

    handleClose () {
        this.setState({ error: '' })
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

    filterCases (filters) {
        this.setState({ filters: filters })
    }

    resetFilters () {
        this.props.reset()
    }

    userList (props) {
        const { cases, customers } = this.state
        return <CaseItem showCheckboxes={props.showCheckboxes} customers={customers} cases={cases}
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

    render () {
        const { searchText, status, start_date, end_date, customer_id, category_id, priority_id } = this.state.filters
        const { view, cases, customers, error } = this.state
        const fetchUrl = `/api/cases?search_term=${searchText}&status=${status}&start_date=${start_date}&end_date=${end_date}&customer_id=${customer_id}&category_id=${category_id}&priority_id=${priority_id}`
        const margin_class = Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed) === true
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable-large fixed-margin-datatable-large-mobile'

        return customers.length ? (
            <React.Fragment>
                <div className="topbar">
                    <Card>
                        <CardBody>
                            <CaseFilters cases={cases}
                                customers={customers}
                                updateIgnoredColumns={this.updateIgnoredColumns}
                                filters={this.state.filters} filter={this.filterCases}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                            <AddCase
                                customers={customers}
                                cases={cases}
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
                                customers={this.state.customers}
                                columnMapping={{ customer_id: 'CUSTOMER', priority_id: 'PRIORITY', status_id: 'STATUS' }}
                                dropdownButtonActions={this.state.dropdownButtonActions}
                                entity_type="Case"
                                bulk_save_url="/api/cases/bulk"
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
        ) : null
    }
}
