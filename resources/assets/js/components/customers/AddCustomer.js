import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap'
import axios from 'axios'
import CustomerTabs from './CustomerTabs'
import AddButtons from '../common/AddButtons'
import {translations} from "../common/_icons";

class AddCustomer extends React.Component {
    constructor (props) {
        super(props)
        this.initialState = {
            modal: false,
            name: '',
            default_payment_method: null,
            group_settings_id: null,
            phone: '',
            address_1: '',
            address_2: '',
            company__id: '',
            custom_value1: '',
            custom_value2: '',
            custom_value3: '',
            custom_value4: '',
            zip: '',
            city: '',
            description: '',
            values: [],
            loading: false,
            submitSuccess: false,
            count: 2,
            errors: []
        }

        this.state = this.initialState
        this.toggle = this.toggle.bind(this)
    }

    handleClick (event) {
        this.setState({ loading: true })
        const formData = {
            name: this.state.name,
            phone: this.state.phone,
            address_1: this.state.address_1,
            address_2: this.state.address_2,
            zip: this.state.zip,
            city: this.state.city,
            company_id: this.state.company_id,
            group_settings_id: this.state.group_settings_id,
            description: this.state.description,
            default_payment_method: this.state.default_payment_method,
            custom_value1: this.state.custom_value1,
            custom_value2: this.state.custom_value2,
            custom_value3: this.state.custom_value3,
            custom_value4: this.state.custom_value4
        }
        this.setState({
            submitSuccess: true,
            values: [...this.state.values, formData],
            loading: false
        })
        axios.post('/api/customers', formData)
            .then((response) => {
                this.toggle()
                const newCustomer = response.data
                this.props.customers.push(newCustomer)
                this.props.action(this.props.customers)
                this.setState(this.initialState)
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
            })
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState(this.initialState, () => localStorage.removeItem('customerForm'))
            }
        })
    }

    render () {
        const { submitSuccess, loading } = this.state

        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle} />
                <Modal size="lg" isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.add_customer}
                    </ModalHeader>

                    <ModalBody>

                        {!submitSuccess && (
                            <div className="alert alert-info" role="alert">
                                Fill the form below to create a new post
                            </div>
                        )}

                        {submitSuccess && (
                            <div className="alert alert-info" role="alert">
                                The form was successfully submitted!
                            </div>
                        )}

                        <CustomerTabs custom_fields={this.props.custom_fields} toggle={this.toggle}
                            customers={this.props.customers} action={this.props.action}
                            type="add"/>
                    </ModalBody>

                    <ModalFooter>
                        <Button color="secondary" onClick={this.toggle}>{translations.close}</Button>

                        {loading &&
                        <span className="fa fa-circle-o-notch fa-spin"/>
                        }
                    </ModalFooter>
                </Modal>
            </React.Fragment>
        )
    }
}

export default AddCustomer
