import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter, Input, FormGroup, Label, DropdownItem } from 'reactstrap'
import { icons, translations } from '../common/_icons'
import TokenModel from '../models/TokenModel'

export default class EditToken extends React.Component {
    constructor (props) {
        super(props)

        this.tokenModel = new TokenModel(this.props.token)
        this.initialState = this.tokenModel.fields
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
            settings: this.state.settings
        }

        this.tokenModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.tokenModel.errors, message: this.tokenModel.error_message })
                return
            }

            const index = this.props.tokens.findIndex(token => token.id === this.props.token.id)
            this.props.tokens[index] = response
            this.props.action(this.props.tokens)
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
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_token}
                </DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_token}
                    </ModalHeader>
                    <ModalBody>
                        <FormGroup>
                            <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''}
                                value={this.state.name}
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Name" onChange={this.handleInput.bind(this)}/>
                            {this.renderErrorFor('name')}
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
