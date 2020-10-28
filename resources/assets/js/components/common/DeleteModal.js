import React, { Component } from 'react'
import { Button, DropdownItem, Modal, ModalBody, ModalFooter, ModalHeader } from 'reactstrap'
import { icons } from '../utils/_icons'
import { translations } from '../utils/_translations'

export default class DeleteModal extends Component {
    constructor (props) {
        super(props)
        this.state = {
            roles: [],
            modal: false
        }

        this.toggle = this.toggle.bind(this)
        this.delete = this.delete.bind(this)
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    delete () {
        this.props.deleteFunction(this.props.id, this.props.archive)
        this.toggle()
    }

    render () {
        const text = this.props.archive === true ? translations.archive : translations.delete
        const message = this.props.archive === true ? translations.archive_message : translations.delete_message
        const icon = this.props.archive === true ? `${icons.archive}` : `${icons.delete}`
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icon}`}/>{text}</DropdownItem>

                <Modal centered={true} backdrop="static" isOpen={this.state.modal} toggle={this.toggle}
                    className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>{text.toUpperCase()}</ModalHeader>
                    <ModalBody className={theme}>
                        {message}
                    </ModalBody>
                    <ModalFooter>
                        <Button onClick={this.delete}
                            color="danger">{translations.yes}</Button>
                        <Button onClick={this.toggle} color="secondary">{translations.no}</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
