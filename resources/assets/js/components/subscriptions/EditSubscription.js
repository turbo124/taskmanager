import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label, DropdownItem } from 'reactstrap'
import axios from 'axios'
import { icons, translations } from '../common/_icons'
import { consts } from '../common/_consts'
import SubscriptionModel from '../models/SubscriptionModel'
import Details from './Details'

export default class EditSubscription extends React.Component {
    constructor (props) {
        super(props)

        this.subscriptionModel = new SubscriptionModel(this.props.subscription)
        this.initialState = this.subscriptionModel.fields
        this.state = this.initialState

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
        const data = {
            name: this.state.name,
            target_url: this.state.target_url,
            event_id: this.state.event_id
        }

        this.subscriptionModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.subscriptionModel.errors, message: this.subscriptionModel.error_message })
                return
            }

            const index = this.props.subscriptions.findIndex(subscription => subscription.id === this.props.subscription.id)
            this.props.subscriptions[index] = response
            this.props.action(this.props.subscriptions)
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
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_subscription}</DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_subscription}
                    </ModalHeader>
                    <ModalBody>
                        <Details hasErrorFor={this.hasErrorFor} subscription={this.state}
                            renderErrorFor={this.renderErrorFor} handleInput={this.handleInput.bind(this)}/>
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
