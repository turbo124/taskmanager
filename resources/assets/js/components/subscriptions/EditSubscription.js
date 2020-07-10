import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label, DropdownItem } from 'reactstrap'
import axios from 'axios'
import { icons, translations } from '../common/_icons'
import { consts } from '../common/_consts'
import SubscriptionModel from '../models/SubscriptionModel'

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
                        <FormGroup>
                            <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''}
                                value={this.state.name}
                                type="text"
                                name="name"
                                id="name"
                                placeholder={translations.name} onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="target_url">{translations.target_url}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('target_url') ? 'is-invalid' : ''}
                                value={this.state.target_url}
                                type="text"
                                name="target_url"
                                id="target_url"
                                placeholder={translations.target_url} onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('target_url')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="event_id">{translations.event}<span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('event_id') ? 'is-invalid' : ''} type="select" name="event_id"
                                id="event_id" value={this.state.event_id}
                                onChange={this.handleInput.bind(this)}>
                                <option value="">{translations.select_event}</option>
                                <option value={consts.order_created_subscription}>{translations.order_created}</option>
                                <option value={consts.order_deleted_subscription}>{translations.order_deleted}</option>
                                <option value={consts.order_backordered_subscription}>{translations.order_backordered}</option>
                                <option value={consts.order_held_subscription}>{translations.order_held}</option>
                                <option value={consts.credit_created_subscription}>{translations.credit_created}</option>
                                <option value={consts.credit_deleted_subscription}>{translations.credit_deleted}</option>
                                <option value={consts.customer_created_subscription}>{translations.customer_created}</option>
                                <option value={consts.customer_deleted_subscription}>{translations.customer_deleted}</option>
                                <option value={consts.invoice_created_subscription}>{translations.invoice_created}</option>
                                <option value={consts.invoice_deleted_subscription}>{translations.invoice_deleted}</option>
                                <option value={consts.payment_created_subscription}>{translations.payment_created}</option>
                                <option value={consts.payment_deleted_subscription}>{translations.payment_deleted}</option>
                                <option value={consts.quote_created_subscription}>{translations.quote_created}</option>
                                <option value={consts.quote_deleted_subscription}>{translations.quote_deleted}</option>
                                <option value={consts.lead_created_subscription}>{translations.lead_created}</option>
                            </Input>
                            {this.renderErrorFor('event_id')}
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
