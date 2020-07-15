import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label } from 'reactstrap'
import axios from 'axios'
import CustomerDropdown from '../common/CustomerDropdown'
import UserDropdown from '../common/UserDropdown'
import AddButtons from '../common/AddButtons'
import moment from 'moment'
import { translations } from '../common/_translations'
import ProjectModel from '../models/ProjectModel'

class AddStory extends React.Component {
    constructor (props) {
        super(props)
        this.projectModel = new ProjectModel(null)
        this.initialState = this.projectModel.fields
        this.state = this.initialState
        this.toggle = this.toggle.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.getStoryCount = this.getStoryCount.bind(this)
        this.handleClick = this.handleClick.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'projectForm')) {
            const storedValues = JSON.parse(localStorage.getItem('projectForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    handleChange (event) {
        this.setState({ name: event.target.value })
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        }, () => localStorage.setItem('projectForm', JSON.stringify(this.state)))
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

    /** To be done */
    getStoryCount () {
        axios.get('/story/count')
            .then((r) => {
                this.setState({
                    count: r.data.count,
                    err: ''
                })
            })
            .catch((e) => {
                this.setState({
                    err: e
                })
            })
    }

    handleClick (event) {
        const data = {
            title: this.state.title,
            description: this.state.description,
            customer_id: this.state.customer_id,
            storyId: this.state.count,
            notes: this.state.notes,
            due_date: this.state.due_date,
            assigned_user_id: this.state.assigned_user_id,
            budgeted_hours: this.state.budgeted_hours
        }

        this.projectModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.projectModel.errors, message: this.projectModel.error_message })
                return
            }

            this.props.projects.push(response)
            this.props.action(this.props.projects)
            this.setState(this.initialState)
            localStorage.removeItem('projectForm')
        })
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState(this.initialState, () => localStorage.removeItem('projectForm'))
            }
        })
    }

    render () {
        return (
            <div>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.add_project}
                    </ModalHeader>

                    <ModalBody>
                        <FormGroup>
                            <Label for="title">{translations.title}(*):</Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''} type="text"
                                name="title" onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('title')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="description">{translations.description}(*):</Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''} type="textarea"
                                value={this.state.description} name="description"
                                onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('description')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="description">{translations.customer}(*):</Label>
                            <CustomerDropdown
                                customer={this.state.customer_id}
                                errors={this.state.errors}
                                renderErrorFor={this.renderErrorFor}
                                handleInputChanges={this.handleInput.bind(this)}
                                customers={this.props.customers}
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="postcode">{translations.assigned_user}:</Label>
                            <UserDropdown
                                user_id={this.state.assigned_user_id}
                                name="assigned_user_id"
                                errors={this.state.errors}
                                handleInputChanges={this.handleInput.bind(this)}
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="postcode">{translations.notes}:</Label>
                            <Input
                                value={this.state.notes}
                                type='textarea'
                                name="notes"
                                errors={this.state.errors}
                                onChange={this.handleInput.bind(this)}
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="postcode">Due Date:</Label>
                            <Input
                                value={moment(this.state.due_date).format('YYYY-MM-DD')}
                                type='date'
                                name="due_date"
                                errors={this.state.errors}
                                onChange={this.handleInput.bind(this)}
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="postcode">Budgeted Hours:</Label>
                            <Input
                                type='text'
                                name="budgeted_hours"
                                value={this.state.budgeted_hours}
                                errors={this.state.errors}
                                onChange={this.handleInput.bind(this)}
                            />
                        </FormGroup>
                    </ModalBody>
                    <ModalFooter>
                        <Button color="primary" onClick={this.handleClick.bind(this)}>{translations.save}</Button>
                        <Button color="secondary" onClick={this.toggle}>{translations.close}</Button>
                    </ModalFooter>
                </Modal>
            </div>
        )
    }
}

export default AddStory
