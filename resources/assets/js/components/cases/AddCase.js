import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_icons'
import CustomerDropdown from '../common/CustomerDropdown'
import Datepicker from '../common/Datepicker'
import CaseCategoryDropdown from '../common/CaseCategoryDropdown'
import { consts } from "../common/_consts";

export default class AddCase extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            subject: '',
            message: '',
            customer_id: '',
            due_date: '',
            priority_id: '',
            category_id: '',
            private_notes: '',
            loading: false,
            errors: []
        }

        this.toggle = this.toggle.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'caseForm')) {
            const storedValues = JSON.parse(localStorage.getItem('caseForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        }, () => localStorage.setItem('caseForm', JSON.stringify(this.state)))
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

    handleClick () {
        axios.post('/api/cases', {
            subject: this.state.subject,
            message: this.state.message,
            customer_id: this.state.customer_id,
            due_date: this.state.due_date,
            priority_id: this.state.priority_id,
            private_notes: this.state.private_notes,
            category_id: this.state.category_id
        })
            .then((response) => {
                const newUser = response.data
                this.props.cases.push(newUser)
                this.props.action(this.props.cases)
                localStorage.removeItem('caseForm')
                this.setState({
                    subject: '',
                    private_notes: '',
                    priority_id: '',
                    category_id: '',
                    due_date: '',
                    message: '',
                    customer_id: ''
                })
                this.toggle()
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState({
                    subject: '',
                    message: '',
                    customer_id: '',
                    due_date: '',
                    private_notes: '',
                    priority_id: '',
                    category_id: ''
                }, () => localStorage.removeItem('caseForm'))
            }
        })
    }

    render () {
        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.add_case}
                    </ModalHeader>
                    <ModalBody>
                        <FormGroup>
                            <Label for="subject">{translations.subject} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('subject') ? 'is-invalid' : ''} type="text" name="subject"
                                id="subject" value={this.state.subject} placeholder={translations.subject}
                                onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('subject')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="message">{translations.message}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('message') ? 'is-invalid textarea-lg' : 'textarea-lg'} type="textarea" name="message"
                                id="message" value={this.state.message} placeholder={translations.message}
                                onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('message')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="description">{translations.customer}(*):</Label>
                            <CustomerDropdown
                                customer={this.state.customer_id}
                                errors={this.state.errors}
                                renderErrorFor={this.renderErrorFor}
                                handleInputChanges={this.handleInput}
                                customers={this.props.customers}
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="examplePassword">{translations.due_date}</Label>
                            <Datepicker className="form-control" name="due_date" date={this.state.due_date}
                                handleInput={this.handleInput}/>
                        </FormGroup>

                        <FormGroup>
                            <Label for="examplePassword">{translations.private_notes}</Label>
                            <Input value={this.state.private_notes} type="text"
                                name="private_notes"
                                onChange={this.handleInput} id="private_notes"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="examplePassword">{translations.priority}</Label>
                            <Input value={this.state.priority_id} type="select"
                                name="priority_id"
                                onChange={this.handleInput} id="priority_id"
                            >
                                <option value="">{translations.select_option}</option>
                                <option value={consts.low_priority}>{translations.low}</option>
                                <option value={consts.medium_priority}>{translations.medium}</option>
                                <option value={consts.high_priority}>{translations.high}</option>
                            </Input>
                        </FormGroup>

                        <FormGroup>
                            <Label>{translations.category}</Label>
                            <CaseCategoryDropdown
                                name="category_id"
                                category={this.state.category_id}
                                errors={this.state.errors}
                                renderErrorFor={this.renderErrorFor}
                                handleInputChanges={this.handleInput}
                            />
                        </FormGroup>
                    </ModalBody>

                    <ModalFooter>
                        <Button color="primary" onClick={this.handleClick.bind(this)}>{translations.save}</Button>
                        <Button color="secondary" onClick={this.toggle}>{translations.close}</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
