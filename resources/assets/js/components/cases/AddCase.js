import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_icons'
import Details from './Details'

export default class AddCase extends React.Component {
    constructor (props) {
        super(props)
        /* this.state = {
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
        }*/
        this.caseModel = new CaseModel(null, this.props.customers)
        this.initialState = this.caseModel.fields
        this.state = this.initialState

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
        const data = {
            subject: this.state.subject,
            message: this.state.message,
            customer_id: this.state.customer_id,
            due_date: this.state.due_date,
            priority_id: this.state.priority_id,
            private_notes: this.state.private_notes,
            category_id: this.state.category_id
        }

        this.caseModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.caseModel.errors, message: this.caseModel.error_message })
                return
            }
            this.props.cases.push(response)
            this.props.action(this.props.cases)
            this.setState(this.initialState)
            localStorage.removeItem('caseForm')
            this.toggle()
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
