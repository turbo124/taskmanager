import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label, DropdownItem } from 'reactstrap'
import axios from 'axios'
import { icons, translations } from '../common/_icons'
import Details from './Details'

export default class EditCase extends React.Component {
    constructor (props) {
        super(props)
        /* this.state = {
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
        }*/

        const data = this.props.case ? this.props.case : null
        this.caseModel = new CaseModel(data, this.props.customers)
        this.initialState = this.caseModel.fields
        this.state = this.initialState

        //this.initialState = this.state
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
        const formData = {
            subject: this.state.subject,
            message: this.state.message,
            customer_id: this.state.customer_id,
            due_date: this.state.due_date,
            priority_id: this.state.priority_id,
            private_notes: this.state.private_notes,
            category_id: this.state.category_id
        }

        this.caseModel.save(formData).then(response => {
            if (!response) {
                this.setState({ errors: this.caseModel.errors, message: this.caseModel.error_message })
                return
            }

            const index = this.props.cases.findIndex(case => case.id === this.props.case.id)
            this.props.cases[index] = response
            this.props.action(this.props.cases)
            this.setState({
                editMode: false,
                changesMade: false
            })
            this.toggle()
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
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_case}
                </DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_case}
                    </ModalHeader>
                    <ModalBody>
                        <Details customers={this.props.customers} errors={this.state.errors}
                            hasErrorFor={this.hasErrorFor} case={this.state}
                            handleInput={this.handleInput} renderErrorFor={this.renderErrorFor}/>
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
