import React, { Component } from 'react'
import {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    Button,
    UncontrolledTooltip
} from 'reactstrap'
import axios from 'axios'
import { translations } from './_translations'

export default class AboutModal extends Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            check: false,
            errors: [],
            showSuccessMessage: false,
            showErrorMessage: false,
            message: ''
        }

        this.toggle = this.toggle.bind(this)
        this.sendMessage = this.sendMessage.bind(this)
        this.handleChange = this.handleChange.bind(this)
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    getData () {
        axios.get('/api/support/messages/send')
            .then(function (response) {

            })
            .catch(function (error) {
                alert(error)
                console.log(error)
            })
    }

    sendMessage () {
        axios.post('/api/support/messages/send', { message: this.state.message, send_logs: this.state.check })
            .then(function (response) {

            })
            .catch(function (error) {
                alert(error)
                console.log(error)
            })
    }

    handleChange (e) {
        this.setState({
            [e.target.name]: e.target.value
        })
    }

    render () {
        return (
            <React.Fragment>
                <UncontrolledTooltip placement="right" target="contactTooltip">
                    About
                </UncontrolledTooltip>

                <i id="contactTooltip" onClick={this.toggle}
                    style={{ marginLeft: '12px', marginRight: 'auto', color: '#fff', fontSize: '20px', cursor: 'pointer' }}
                    className="fa fa-question-circle"/>

                <Modal centered={true} backdrop="static" isOpen={this.state.modal} toggle={this.toggle}
                    className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>TamTam CRM</ModalHeader>
                    <ModalBody>
                         <p>{translations.about_message}</p>
                         <p>{translations.about_link}</p>
                         <p>https://michael-hampton.github.io/tamtam</p>
                    </ModalBody>
                    <ModalFooter>
                        <Button onClick={this.toggle} color="secondary">Cancel</Button>
                        <Button onClick={this.sendMessage}
                            color="primary">Upgrade</Button>{' '}
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
