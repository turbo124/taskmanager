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
import CustomFieldsForm from '../common/CustomFieldsForm'
import Notes from '../common/Notes'
import DealModel from '../models/DealModel'
import Details from './Details'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'

export default class EditDeal extends Component {
    constructor (props) {
        super(props)

        this.dealModel = new DealModel(this.props.deal, this.props.customers)
        this.initialState = this.dealModel.fields
        this.dealModel.start_date = this.initialState.start_date
        this.dealModel.due_date = this.initialState.due_date

        this.state = this.initialState

        this.handleSave = this.handleSave.bind(this)
        this.handleDelete = this.handleDelete.bind(this)
        this.handleChange = this.handleChange.bind(this)
       
       
        this.toggle = this.toggle.bind(this)
       
        this.toggleMenu = this.toggleMenu.bind(this)
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

    getFormData () {
        return {
            customer_id: this.state.customer_id,
            rating: this.state.rating,
            source_type: this.state.source_type,
            valued_at: this.state.valued_at,
            title: this.state.title,
            description: this.state.description,
            assigned_to: this.state.assigned_to,
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
        this.dealModel.update(this.getFormData()).then(response => {
            if (!response) {
                this.setState({ errors: this.dealModel.errors, message: this.dealModel.error_message })
                return
            }

            const index = this.props.deals.findIndex(deal => deal.id === this.props.deal.id)
            this.props.deals[index] = response
            this.props.action(this.props.deals)
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
            this.props.onDelete(this.props.deal)
        }
    }

    handleChange (e) {
        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value
        this.setState({
            [e.target.name]: value,
            changesMade: true
        })
    }

    render () {
       const form = <React.Fragment>
           <DealDropdownMenu model={this.dealModel} id={this.props.deal.id} formData={this.getFormData()}/>
                    <Card>
                        <CardHeader>Details</CardHeader>
                        <CardBody>
                            <Details task={this.state}
                                customers={this.props.customers}
                                errors={this.state.errors}
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
                        </CardBody>
                    </Card>
               
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
