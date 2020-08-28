import React from 'react'
import { Dropdown, DropdownItem, DropdownMenu, DropdownToggle, Modal, ModalBody } from 'reactstrap'
import CustomerTabs from './CustomerTabs'
import axios from 'axios'
import SuccessMessage from '../../common/SucessMessage'
import ErrorMessage from '../../common/ErrorMessage'
import { icons } from '../../common/_icons'
import { translations } from '../../common/_translations'
import DefaultModalHeader from '../../common/ModalHeader'
import DefaultModalFooter from '../../common/ModalFooter'

class EditCustomer extends React.Component {
    constructor (props) {
        super(props)

        this.state = {
            modal: false,
            id: this.props.customer.id,
            dropdownOpen: false,
            loading: false,
            submitSuccess: false,
            showSuccessMessage: false,
            showErrorMessage: false
        }

        this.toggle = this.toggle.bind(this)
        this.toggleMenu = this.toggleMenu.bind(this)
        this.changeStatus = this.changeStatus.bind(this)
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    toggleMenu (event) {
        this.setState({
            dropdownOpen: !this.state.dropdownOpen
        })
    }

    changeStatus (action) {
        if (!this.state.id) {
            return false
        }

        const data = this.getFormData()
        axios.post(`/api/customer/${this.state.id}/${action}`, data)
            .then((response) => {
                if (action === 'download') {
                    this.downloadPdf(response)
                }

                this.setState({ showSuccessMessage: true })
            })
            .catch((error) => {
                this.setState({ showErrorMessage: true })
                console.warn(error)
            })
    }

    render () {
        const { submitSuccess, loading } = this.state

        const sendEmailButton = <DropdownItem className="primary" onClick={() => this.changeStatus('email')}>Send
            Email</DropdownItem>

        const deleteButton = this.state.status_id === 1
            ? <DropdownItem className="primary"
                onClick={() => this.changeStatus('delete')}>Delete</DropdownItem> : null

        const archiveButton = this.state.status_id === 1
            ? <DropdownItem className="primary"
                onClick={() => this.changeStatus('archive')}>Archive</DropdownItem> : null

        const cloneButton =
            <DropdownItem className="primary"
                onClick={() => this.changeStatus('clone_to_customer')}>Clone</DropdownItem>

        const dropdownMenu = <Dropdown isOpen={this.state.dropdownOpen} toggle={this.toggleMenu}>
            <DropdownToggle caret>
                {translations.actions}
            </DropdownToggle>

            <DropdownMenu>
                {sendEmailButton}
                {deleteButton}
                {archiveButton}
                {cloneButton}
            </DropdownMenu>
        </Dropdown>

        const successMessage = this.state.showSuccessMessage === true
            ? <SuccessMessage message="Invoice was updated successfully"/> : null
        const errorMessage = this.state.showErrorMessage === true
            ? <ErrorMessage message="Something went wrong"/> : null
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        if (this.props.modal) {
            return (
                <React.Fragment>
                    <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>Edit</DropdownItem>
                    <Modal size="lg" isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                        <DefaultModalHeader toggle={this.toggle} title={translations.edit_customer}/>

                        <ModalBody className={theme}>
                            {submitSuccess && (
                                <div className="alert alert-info" role="alert">
                                    Customer's details has been edited successfully </div>
                            )}

                            {dropdownMenu}
                            {successMessage}
                            {errorMessage}

                            <CustomerTabs custom_fields={this.props.custom_fields} toggle={this.toggle}
                                customers={this.props.customers}
                                customer={this.props.customer} action={this.props.action}/>

                        </ModalBody>
                        <DefaultModalFooter show_success={false} toggle={this.toggle}
                            loading={loading}/>
                    </Modal>
                </React.Fragment>
            )
        }

        return (
            <div>
                {submitSuccess && (
                    <div className="mt-3 alert alert-info" role="alert">
                        Customer's details has been edited successfully </div>
                )}
                <CustomerTabs customers={this.props.customers} customer={this.props.customer}
                    action={this.props.action}/>
            </div>
        )
    }
}

export default EditCustomer
