import React, { Component } from 'react'
import axios from 'axios'
import AddProject from './AddStory'
import DataTable from '../common/DataTable'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import ProjectFilters from './ProjectFilters'
import ProjectItem from './ProjectItem'
import queryString from 'query-string'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class ProjectList extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: window.innerWidth > 670,
            customers: [],
            projects: [],
            cachedData: [],
            errors: [],
            bulk: [],
            dropdownButtonActions: ['download'],
            error: '',
            show_success: false,
            error_message: translations.unexpected_error,
            success_message: translations.success_message,
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            filters: {
                status_id: 'active',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            custom_fields: [],
            ignoredColumns: [
                'tasks',
                'created_at',
                'deleted_at',
                'updated_at',
                'is_completed',
                'customer_id',
                'is_deleted',
                'archived_at',
                'task_rate',
                'account_id',
                'custom_value1',
                'custom_value2',
                'custom_value3',
                'custom_value4',
                'user_id',
                'assigned_to',
                'notes'
            ],
            showRestoreButton: false
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterProjects = this.filterProjects.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
        this.getCustomFields()
    }

    addUserToState (projects) {
        const cachedData = !this.state.cachedData.length ? projects : this.state.cachedData
        this.setState({
            projects: projects,
            cachedData: cachedData
        })
    }

    handleClose () {
        this.setState({ error: '', show_success: false })
    }

    filterProjects (filters) {
        this.setState({ filters: filters })
    }

    userList (props) {
        const { projects, custom_fields, customers } = this.state
        return <ProjectItem showCheckboxes={props.showCheckboxes} projects={projects} customers={customers}
            custom_fields={custom_fields}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    getCustomFields () {
        axios.get('api/accounts/fields/Project')
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

    getCustomers () {
        axios.get('api/customers')
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
        const { projects, customers, custom_fields, ignoredColumns, view, error, isOpen, error_message, success_message, show_success } = this.state
        const { status_id, customer_id, searchText, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/projects?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&start_date=${start_date}&end_date=${end_date}`
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return this.state.customers.length ? (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <ProjectFilters setFilterOpen={this.setFilterOpen.bind(this)} projects={projects}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={this.state.filters} filter={this.filterProjects}
                                    saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                                <AddProject customers={customers} projects={projects} action={this.addUserToState}
                                    custom_fields={custom_fields}/>
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
                        <Card>
                            <CardBody>
                                <DataTable
                                    customers={customers}
                                    setSuccess={this.setSuccess.bind(this)}
                                    setError={this.setError.bind(this)}
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="Project"
                                    bulk_save_url="/api/project/bulk"
                                    view={view}
                                    disableSorting={['id']}
                                    defaultColumn='title'
                                    ignore={ignoredColumns}
                                    userList={this.userList}
                                    fetchUrl={fetchUrl}
                                    updateState={this.addUserToState}
                                />
                            </CardBody>
                        </Card>
                    </div>
                </div>
            </Row>
        ) : null
    }
}
