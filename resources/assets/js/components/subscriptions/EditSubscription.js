import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label, DropdownItem } from 'reactstrap'
import axios from 'axios'

export default class EditSubscription extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            id: this.props.subscription.id,
            name: this.props.subscription.name,
            target_url: this.props.subscription.target_url,
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
        axios.put(`/api/subscriptions/${this.state.id}`, {
            name: this.state.name,
            target_url: this.state.target_url,
            settings: this.state.settings
        })
            .then((response) => {
                const index = this.props.subscriptions.findIndex(subscription => subscription.id === this.state.id)
                this.props.subscriptions[index].name = this.state.name
                this.props.subscriptions[index].target_url = this.state.target_url
                this.props.action(this.props.subscriptions)
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
                <DropdownItem onClick={this.toggle}><i className="fa fa-edit"/>Edit</DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        Edit Subscription
                    </ModalHeader>
                    <ModalBody>
                        <FormGroup>
                            <Label for="name">Name <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''}
                                value={this.state.name}
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Name" onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="target_url">Target Url <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('target_url') ? 'is-invalid' : ''}
                                value={this.state.target_url}
                                type="text"
                                name="target_url"
                                id="target_url"
                                placeholder="target_url" onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('target_url')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="event_id">Event<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('event_id') ? 'is-invalid' : ''} type="select" name="event_id"
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
                                <option value="9">Payment Created</option>
                                <option value="10">Payment Deleted</option>
                                <option value="11">Quote Created</option>
                                <option value="12">Quote Deleted</option>
                                <option value="13">Lead Created</option>
                            </Input>
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
