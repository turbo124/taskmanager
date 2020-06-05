import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label, DropdownItem } from 'reactstrap'
import axios from 'axios'
import { icons, translations } from '../common/_icons'
import CustomerDropdown from '../common/CustomerDropdown'
import Datepicker from '../common/Datepicker'
import CaseCategoryDropdown from '../common/CaseCategoryDropdown'
import { consts } from '../common/_consts'

export default class EditCase extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            id: this.props.case.id,
            subject: this.props.case.subject,
            customer_id: this.props.case.customer_id,
            due_date: this.props.case.due_date,
            priority_id: this.props.case.priority_id,
            category_id: this.props.case.category_id,
            private_notes: this.props.case.private_notes,
            message: this.props.case.message,
            loading: false,
            changesMade: false,
            errors: []
        }

        this.initialState = this.state
        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value,
            changesMade: true
        })
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
        axios.put(`/api/cases/${this.state.id}`, {
            subject: this.state.subject,
            message: this.state.message,
            customer_id: this.state.customer_id,
            due_date: this.state.due_date,
            priority_id: this.state.priority_id,
            private_notes: this.state.private_notes,
            category_id: this.state.category_id
        })
            .then((response) => {
                const index = this.props.cases.findIndex(case_file => case_file.id === this.state.id)
                this.props.cases[index] = response.data
                this.props.action(this.props.cases)
                this.setState({ changesMade: false })
                this.toggle()
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
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

    render () {
        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_case}</DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_case}
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
                            <Input value={this.state.private_notes} type="textarea"
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
