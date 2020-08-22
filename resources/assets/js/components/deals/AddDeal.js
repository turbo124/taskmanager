import React from 'react'
import { DateRangePicker } from 'react-dates'
import { Button, Form, FormGroup, Label, Modal, ModalBody } from 'reactstrap'
import moment from 'moment'
import AddLead from './AddLead'
import AddButtons from '../common/AddButtons'
import CustomFieldsForm from '../common/CustomFieldsForm'
import Notes from '../common/Notes'
import TaskModel from '../models/TaskModel'
import TaskDetails from './TaskDetails'
import { translations } from '../common/_translations'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'

export default class AddDeal extends React.Component {
    constructor (props) {
        super(props)

        this.dealModel = new DealModel(null, this.props.customers)
        this.initialState = this.taskModel.fields
        this.dealModel.start_date = this.initialState.start_date
        this.dealModel.due_date = this.initialState.due_date

        this.state = this.initialState
        this.toggle = this.toggle.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.buildForm = this.buildForm.bind(this)
        this.handleMultiSelect = this.handleMultiSelect.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'taskForm')) {
            // const storedValues = JSON.parse(localStorage.getItem('taskForm'))
            // this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value
        this.setState({
            [e.target.name]: value
        }, () => localStorage.setItem('taskForm', JSON.stringify(this.state)))
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState(this.initialState, () => localStorage.removeItem('taskForm'))
            }
        })
    }
 
    handleClick (event) {
        this.setState({
            submitSuccess: false,
            loading: true
        })

        const data = {
            rating: this.state.rating,
            source_type: this.state.source_type,
            valued_at: this.state.valued_at,
            customer_id: this.state.customer_id,
            title: this.state.title,
            content: this.state.content,
            task_status: parseInt(this.props.status),
            contributors: this.state.selectedUsers,
            due_date: moment(this.state.due_date).format('YYYY-MM-DD'),
            start_date: moment(this.state.start_date).format('YYYY-MM-DD'),
            project_id: parseInt(this.props.project_id),
            created_by: this.state.created_by,
            task_type: this.props.task_type,
            parent_id: this.props.task_id ? this.props.task_id : 0,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4,
            public_notes: this.state.public_notes,
            private_notes: this.state.private_notes
        }

        this.dealModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.dealModel.errors, message: this.taskModel.error_message })
                return
            }
            this.props.deals.push(response)
            this.props.action(this.props.deals)
            this.setState(this.initialState)
            localStorage.removeItem('dealForm')
        })
    }

    buildForm () {
        return (
            <Form>
                <Details task={this.state} customers={this.props.customers}
                    errors={this.state.errors} handleMultiSelect={this.handleMultiSelect}
                    users={this.props.users} handleInput={this.handleInput}/>

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

                <CustomFieldsForm handleInput={this.handleInput} custom_value1={this.state.custom_value1}
                    custom_value2={this.state.custom_value2}
                    custom_value3={this.state.custom_value3}
                    custom_value4={this.state.custom_value4}
                    custom_fields={this.props.custom_fields}/>

                <Notes private_notes={this.state.private_notes} public_notes={this.state.public_notes}
                    handleInput={this.handleInput}/>

            </Form>
        )
    }

    handleMultiSelect (e) {
        this.setState({ selectedUsers: Array.from(e.target.selectedOptions, (item) => item.value) })
    }

    render () {
        const form = this.buildForm()
        const saveButton = <Button color="primary" onClick={this.handleClick.bind(this)}> Add</Button>
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        if (this.props.modal) {
            return (
                <div>
                    <AddButtons toggle={this.toggle}/>
                    <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                        <DefaultModalHeader toggle={this.toggle} title={translations.add_task}/>

                        <ModalBody className={theme}>
                            {form}
                            {leadForm}
                        </ModalBody>

                        <DefaultModalFooter show_success={true} toggle={this.toggle}
                            saveData={this.handleClick.bind(this)}
                            loading={false}/>
                    </Modal>
                </div>
            )
        }

        return (
            <div>
                {this.state.submitSuccess && (
                    <div className="mt-3 alert alert-info" role="alert">
                        The event has been created successfully </div>
                )}
                {form}
                {leadForm}
                {saveButton}
            </div>
        )
    }
}
