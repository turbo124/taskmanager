import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label } from 'reactstrap'
import axios from 'axios'
import AddButtons from '../common/AddButtons'

export default class AddSubscription extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            name: '',
            target_url: '',
            loading: false,
            errors: []
        }

        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'subscriptionForm')) {
            const storedValues = JSON.parse(localStorage.getItem('subscriptionForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        }, () => localStorage.setItem('subscriptionForm', JSON.stringify(this.state)))
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
        axios.post('/api/subscriptions', {
            name: this.state.name,
            target_url: this.state.target_url,
            event_id: 1
        })
            .then((response) => {
                const newUser = response.data
                this.props.subscriptions.push(newUser)
                this.props.action(this.props.subscriptions)
                localStorage.removeItem('subscriptionForm')
                this.setState({
                    name: '',
                    target_url: ''
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
                    name: '',
                    target_url: ''
                }, () => localStorage.removeItem('subscriptionForm'))
            }
        })
    }

    render () {
        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        Add Subscription
                    </ModalHeader>
                    <ModalBody>
                        <FormGroup>
                            <Label for="name">Name <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                                id="name" value={this.state.name} placeholder="Name"
                                onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="target_url">Target URL <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('target_url') ? 'is-invalid' : ''} type="text" name="target_url"
                                id="target_url" value={this.state.target_url} placeholder="Target URL"
                                onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('target_url')}
                        </FormGroup>

                         <FormGroup>
                            <Label for="event_id">Event<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('target_url') ? 'is-invalid' : ''} type="select" name="event_id"
                                id="event_id" value={this.state.event_id}
                                onChange={this.handleInput.bind(this)}>

                                <option value="">Select Event</option>
                                <option value="1">Order Created</option>
                                <option value="2">Order Deleted</option>
                                <option value="3">Credit Created</option>
                                <option value="4">Credit Deleted</option>
                                <option value="5">Customer Created</option>
                                <option value="6">Customer Deleted</option>
                                <option value="7">Invoice Created</option>
                                <option value="8">Invoice Deleted</option>
                                <option value="9">Payment Created/option>
                                <option value="10">Payment Deleted</option>
                                <option value="11">Quote Created</option>
                                <option value="12">Quote Deleted</option>
                                <option value="13">Lead Created</option>
                            {this.renderErrorFor('event_id')}
                        </FormGroup>
                    </ModalBody>

                    <ModalFooter>
                        <Button color="primary" onClick={this.handleClick.bind(this)}>Add</Button>
                        <Button color="secondary" onClick={this.toggle}>Close</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
