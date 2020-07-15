import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_translations'
import SubscriptionModel from '../models/SubscriptionModel'
import Details from './Details'

export default class AddSubscription extends React.Component {
    constructor (props) {
        super(props)

        this.subscriptionModel = new SubscriptionModel(null)
        this.initialState = this.subscriptionModel.fields
        this.state = this.initialState

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
        const value = e.target.value
        const name = e.target.name

        this.setState({
            [name]: value
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

            this.props.subscriptions.push(response)
            this.props.action(this.props.subscriptions)
            localStorage.removeItem('subscriptionForm')
            this.setState(this.initialState)
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
                        {translations.add_subscription}
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
