import React from 'react'
import {
    Button, Modal, ModalHeader, ModalBody, ModalFooter,
    DropdownItem
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import Details from './Details'
import TaxRateModel from '../models/TaxRateModel'

class EditTaxRate extends React.Component {
    constructor (props) {
        super(props)

        this.taxRateModel = new TaxRateModel(this.props.taxRate)
        this.initialState = this.taxRateModel.fields
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
        const formData = {
            name: this.state.name,
            rate: this.state.rate
        }

        this.taxRateModel.save(formData).then(response => {
            if (!response) {
                this.setState({ errors: this.taxRateModel.errors, message: this.taxRateModel.error_message })
                return
            }

            const index = this.props.taxRates.findIndex(taxRate => taxRate.id === this.props.taxRate.id)
            this.props.taxRates[index] = response
            this.props.action(this.props.taxRates)
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
        const { message } = this.state

        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_tax_rate}
                </DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_tax_rate}
                    </ModalHeader>
                    <ModalBody>

                        {message && <div className="alert alert-danger" role="alert">
                            {message}
                        </div>}

                        <Details hasErrorFor={this.hasErrorFor} tax_rate={this.state}
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

export default EditTaxRate
