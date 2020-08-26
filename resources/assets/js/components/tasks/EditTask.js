import React, { Component } from 'react'
import 'react-dates/initialize' // necessary for latest version
import 'react-dates/lib/css/_datepicker.css'
import { DateRangePicker } from 'react-dates'
import {
    Card,
    CardBody,
    CardHeader,
    DropdownItem,
    FormGroup,
    Label,
    Modal,
    ModalBody,
    Nav,
    NavItem,
    NavLink,
    TabContent,
    TabPane
} from 'reactstrap'
import moment from 'moment'
import EditTaskTimes from './EditTaskTimes'
import TaskDropdownMenu from './TaskDropdownMenu'
import CustomFieldsForm from '../common/CustomFieldsForm'
import Notes from '../common/Notes'
import TaskModel from '../models/TaskModel'
import TaskDetails from './TaskDetails'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'
import Emails from '../emails/Emails'
import FileUploads from '../attachments/FileUploads'
import Comments from '../comments/Comments'
import RecurringForm from '../common/RecurringForm'

class EditTask extends Component {
    constructor (props) {
        super(props)

        this.taskModel = new TaskModel(this.props.task, this.props.customers)
        this.initialState = this.taskModel.fields
        this.taskModel.start_date = this.initialState.start_date
        this.taskModel.due_date = this.initialState.due_date

        this.state = this.initialState

        this.handleSave = this.handleSave.bind(this)
        this.handleDelete = this.handleDelete.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleMultiSelect = this.handleMultiSelect.bind(this)
        this.timerAction = this.timerAction.bind(this)
        this.toggle = this.toggle.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
        this.toggleMenu = this.toggleMenu.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    toggle () {
        if (this.state.modal && this.state.changesMade) {
            if (window.confirm('Your changes have not been saved?')) {
                this.setState({ ...this.initialState })
            }

            return
        }

        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    toggleMenu (event) {
        this.setState({
            dropdownOpen: !this.state.dropdownOpen
        })
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    timerAction (e) {
        const data = {
            action: e.target.id
        }

        this.taskModel.timerAction(data).then(response => {
            if (!response) {
                this.setState({ errors: this.taskModel.errors, message: this.taskModel.error_message })
                return
            }

            this.setState({ action: e.target.id })
        })
    }

    getFormData () {
        return {
            is_recurring: this.state.is_recurring,
            recurring_start_date: this.state.recurring_start_date,
            recurring_end_date: this.state.recurring_end_date,
            recurring_due_date: this.state.recurring_due_date,
            last_sent_date: this.state.last_sent_date,
            next_send_date: this.state.next_send_date,
            recurring_frequency: this.state.recurring_frequency,
            customer_id: this.state.customer_id,
            rating: this.state.rating,
            source_type: this.state.source_type,
            valued_at: this.state.valued_at,
            title: this.state.title,
            content: this.state.description,
            contributors: this.state.selectedUsers,
            due_date: moment(this.state.due_date).format('YYYY-MM-DD'),
            start_date: moment(this.state.start_date).format('YYYY-MM-DD'),
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4,
            public_notes: this.state.public_notes,
            private_notes: this.state.private_notes
        }
    }

    handleSave () {
        this.taskModel.update(this.getFormData()).then(response => {
            if (!response) {
                this.setState({ errors: this.taskModel.errors, message: this.taskModel.error_message })
                return
            }

            const index = this.props.allTasks.findIndex(task => task.id === this.props.task.id)
            this.props.allTasks[index] = response
            this.props.action(this.props.allTasks)
            this.setState({
                editMode: false,
                changesMade: false
            })
            this.toggle()
        })
    }

    handleDelete () {
        this.setState({
            editMode: false
        })
        if (this.props.onDelete) {
            this.props.onDelete(this.props.task)
        }
    }

    handleChange (e) {
        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value
        this.setState({
            [e.target.name]: value,
            changesMade: true
        })
    }

    handleMultiSelect (e) {
        this.setState({ selectedUsers: Array.from(e.target.selectedOptions, (item) => item.value) })
    }

    render () {
        const email_editor = this.state.id
            ? <Emails model={this.taskModel} emails={this.state.emails} template="email_template_task" show_editor={true}
                customers={this.props.customers} entity_object={this.state} entity="task"
                entity_id={this.state.id}/> : null
        const form = <React.Fragment>
            <Nav tabs>
                <NavItem>
                    <NavLink
                        className={this.state.activeTab === '1' ? 'active' : ''}
                        onClick={() => {
                            this.toggleTab('1')
                        }}>
                        {translations.details}
                    </NavLink>
                </NavItem>

                <NavItem>
                    <NavLink
                        className={this.state.activeTab === '2' ? 'active' : ''}
                        onClick={() => {
                            this.toggleTab('2')
                        }}>
                        {translations.times}
                    </NavLink>
                </NavItem>

                <NavItem>
                    <NavLink
                        className={this.state.activeTab === '3' ? 'active' : ''}
                        onClick={() => {
                            this.toggleTab('3')
                        }}>
                        {translations.documents}
                    </NavLink>
                </NavItem>

                <NavItem>
                    <NavLink
                        className={this.state.activeTab === '4' ? 'active' : ''}
                        onClick={() => {
                            this.toggleTab('4')
                        }}>
                        {translations.email}
                    </NavLink>
                </NavItem>

                <NavItem>
                    <NavLink
                        className={this.state.activeTab === '5' ? 'active' : ''}
                        onClick={() => {
                            this.toggleTab('5')
                        }}>
                        {translations.comments}
                    </NavLink>
                </NavItem>
            </Nav>

            <TabContent activeTab={this.state.activeTab}>
                <TabPane tabId="1">

                    <TaskDropdownMenu model={this.taskModel} id={this.props.task.id} formData={this.getFormData()}/>
                    <Card>
                        <CardHeader>{translations.details}</CardHeader>
                        <CardBody>
                            <TaskDetails renderErrorFor={this.renderErrorFor} hasErrorFor={this.hasErrorFor} task={this.state} setTimeRange={this.setTimeRange}
                                customers={this.props.customers}
                                errors={this.state.errors} handleMultiSelect={this.handleMultiSelect}
                                users={this.props.users} handleInput={this.handleChange}/>

                            <FormGroup>
                                <Label>Start / End date</Label>
                                <DateRangePicker
                                    startDate={this.state.start_date} // momentPropTypes.momentObj or null,
                                    startDateId="your_unique_start_date_id" // PropTypes.string.isRequired,
                                    endDate={this.state.due_date} // momentPropTypes.momentObj or null,
                                    endDateId="due_date" // PropTypes.string.isRequired,
                                    displayFormat="DD-MM-YYYY"
                                    onDatesChange={({ startDate, endDate }) => this.setState({
                                        start_date: startDate,
                                        due_date: endDate
                                    })} // PropTypes.func.isRequired,
                                    focusedInput={this.state.focusedInput} // PropTypes.oneOf([START_DATE, END_DATE]) or null,
                                    onFocusChange={focusedInput => this.setState({ focusedInput })} // PropTypes.func.isRequired,
                                />
                            </FormGroup>

                            <CustomFieldsForm handleInput={this.handleChange} custom_value1={this.state.custom_value1}
                                custom_value2={this.state.custom_value2}
                                custom_value3={this.state.custom_value3}
                                custom_value4={this.state.custom_value4}
                                custom_fields={this.props.custom_fields}/>

                            <Notes private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                                handleInput={this.handleChange}/>

                            <RecurringForm renderErrorFor={this.renderErrorFor} hasErrorFor={this.hasErrorFor}
                                recurring={this.state} handleInput={this.handleChange}/>
                        </CardBody>
                    </Card>
                </TabPane>

                <TabPane tabId="2">
                    <Card>
                        <CardHeader>{translations.times}</CardHeader>
                        <CardBody>
                            <EditTaskTimes timers={this.props.task.timers} model={this.taskModel}
                                task_id={this.props.task.id}/>
                        </CardBody>
                    </Card>
                </TabPane>

                <TabPane tabId="3">
                    <Card>
                        <CardHeader>{translations.documents}</CardHeader>
                        <CardBody>
                            <FileUploads entity_type="Task" entity={this.state}
                                user_id={this.state.user_id}/>
                        </CardBody>
                    </Card>
                </TabPane>

                <TabPane tabId="4">
                    {email_editor}
                </TabPane>

                <TabPane tabId="5">
                    <Comments entity_type="Task" entity={this.state}
                        user_id={this.state.user_id}/>
                </TabPane>
            </TabContent>
        </React.Fragment>

        const button = this.props.listView && this.props.listView === true
            ? <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>Edit</DropdownItem>
            : null
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        return this.props.modal && this.props.modal === true
            ? <React.Fragment>
                {button}
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.edit_task}/>

                    <ModalBody className={theme}>
                        {form}
                    </ModalBody>
                    <DefaultModalFooter show_success={true} toggle={this.toggle}
                        saveData={this.handleSave.bind(this)}
                        loading={false}/>
                </Modal>
            </React.Fragment> : form
    }
}

export default EditTask
