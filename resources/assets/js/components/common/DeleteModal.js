import React, { Component } from 'react'
import { Modal, ModalHeader, ModalBody, ModalFooter, Button, DropdownItem } from 'reactstrap'
import { icons } from './_icons'

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
        const text = this.props.archive === true ? 'Archive' : 'Delete'
        const icon = this.props.archive === true ? `${icons.archive}` : `${icons.delete}`
        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icon}`}/>{text}</DropdownItem>

                <Modal centered={true} backdrop="static" isOpen={this.state.modal} toggle={this.toggle}
                    className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>DELETE</ModalHeader>
                    <ModalBody>
                        Do you want to delete this?
                    </ModalBody>
                    <ModalFooter>
                        <Button onClick={this.delete}
                            color="danger">Yes</Button>
                        <Button onClick={this.toggle} color="secondary">No</Button>
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}
