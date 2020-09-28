import React, { Component } from 'react'
import axios from 'axios'
import DataTable from '../common/DataTable'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import TaskFilters from './TaskFilters'
import TaskItem from './TaskItem'
import AddModal from './edit/AddTask'
import queryString from 'query-string'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../utils/_translations'
import CustomerRepository from '../repositories/CustomerRepository'
import UserRepository from '../repositories/UserRepository'

export default class TaskList extends Component {
    constructor (props) {
        super(props)

        this.state = {
            isOpen: window.innerWidth > 670,
            dropdownButtonActions: ['download', 'mark_in_progress'],
            tasks: [],
            users: [],
            customers: [],
            errors: [],
            kanban: false,
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            error: '',
            show_success: false,
            error_message: translations.unexpected_error,
            success_message: translations.success_message,
            filters: {
                project_id: queryString.parse(this.props.location.search).project_id || '',
                status_id: 'active',
                task_status: '',
                user_id: '',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                task_type: '',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            custom_fields: [],

            ignoredColumns: [
                'files',
                'emails',
                'task_rate',
                'timers',
                'public_notes',
                'private_notes',
                'deleted_at',
                'users',
                'customer',
                'contributors',
                'users',
                'comments',
                'is_completed',
                'task_status',
                'task_type',
                'rating',
                'customer_id',
                'user_id',
                'valued_at',
                'rating',
                'is_active',
                'source_type',
                'start_time',
                'duration',
                'custom_value1',
                'custom_value2',
                'custom_value3',
                'custom_value4',
                'is_deleted',
                'time_log',
                'project_id',
                'is_running',
                'task_status_sort_order',
                'notes',
                'is_recurring',
                'recurring_start_date',
                'recurring_end_date',
                'recurring_due_date',
                'last_sent_date',
                'next_send_date',
                'recurring_frequency'
            ],
            showRestoreButton: false
        }

        this.addUserToState = this.addUserToState.bind(this)
        this.userList = this.userList.bind(this)
        this.filterTasks = this.filterTasks.bind(this)
        this.getCustomers = this.getCustomers.bind(this)
        this.getUsers = this.getUsers.bind(this)
    }

    componentDidMount () {
        this.getUsers()
        this.getCustomers()
        this.getCustomFields()
    }

    addUserToState (tasks) {
        this.setState({ tasks: tasks })
    }

    handleClose () {
        this.setState({ error: '', show_success: false })
    }

    filterTasks (filters) {
        console.log('filters', filters)
        this.setState({ filters: filters })

        return true
    }

    userList (props) {
        const { tasks, custom_fields, users, customers } = this.state

        return <TaskItem showCheckboxes={props.showCheckboxes} action={this.addUserToState} tasks={tasks} users={users}
            custom_fields={custom_fields} customers={customers}
            viewId={props.viewId}
            ignoredColumns={props.ignoredColumns} addUserToState={this.addUserToState}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    getCustomFields () {
        axios.get('api/accounts/fields/Task')
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

    getUsers () {
        const userRepository = new UserRepository()
        userRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ users: response }, () => {
                console.log('users', this.state.users)
            })
        })
    }

    getCustomers () {
        const customerRepository = new CustomerRepository()
        customerRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ customers: response }, () => {
                console.log('customers', this.state.customers)
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
        const { tasks, users, customers, custom_fields, isOpen, error_message, success_message, show_success } = this.state
        const { project_id, task_status, task_type, customer_id, user_id, searchText, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/tasks?search_term=${searchText}&project_id=${project_id}&task_status=${task_status}&task_type=${task_type}&customer_id=${customer_id}&user_id=${user_id}&start_date=${start_date}&end_date=${end_date}`
        const { error, view } = this.state
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable-large fixed-margin-datatable-large-mobile'

        const addButton = customers.length && users.length ? <AddModal
            custom_fields={custom_fields}
            modal={true}
            status={1}
            task_type={1}
            customers={customers}
            users={users}
            action={this.addUserToState}
            tasks={tasks}
        /> : null

        return customers.length ? (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <TaskFilters setFilterOpen={this.setFilterOpen.bind(this)} users={users}
                                    tasks={tasks} updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={this.state.filters} filter={this.filterTasks}
                                    saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>

                                {addButton}
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
                                    entity_type="Task"
                                    bulk_save_url="/api/task/bulk"
                                    view={view}
                                    disableSorting={['id']}
                                    defaultColumn='name'
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
        ) : null
    }
}
