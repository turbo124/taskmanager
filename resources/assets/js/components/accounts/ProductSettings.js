import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Button, Card, CardHeader, CardBody } from 'reactstrap'
import axios from 'axios'
import { ToastContainer, toast } from 'react-toastify'
import { translations } from "../common/_translations";

class ProductSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {}
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.handleCheckboxChange = this.handleCheckboxChange.bind(this)
        this.getAccount = this.getAccount.bind(this)
    }

    componentDidMount () {
        this.getAccount()
    }

    getAccount () {
        axios.get(`api/accounts/${this.state.id}`)
            .then((r) => {
                this.setState({
                    loaded: true,
                    settings: r.data.settings
                })
            })
            .catch((e) => {
                console.log(e)
                // this.setState({
                //     loading: false,
                //     err: e
                // })
            })
    }

    handleChange (event) {
        this.setState({ [event.target.name]: event.target.value })
    }

    handleSettingsChange (event) {
        const name = event.target.name
        const value = event.target.type === 'checkbox' ? event.target.checked : event.target.value

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }))
    }

    handleSubmit (e) {
        const formData = new FormData()
        formData.append('settings', JSON.stringify(this.state.settings))
        formData.append('_method', 'PUT')

        axios.post(`/api/accounts/${this.state.id}`, formData, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then((response) => {
                toast.success('Settings updated successfully')
            })
            .catch((error) => {
                console.error(error)
                toast.error('There was an issue updating the settings')
            })
    }

    getInventoryFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_update_inventory',
                    label: 'Update Inventory',
                    type: 'switch',
                    placeholder: 'Update Inventory',
                    value: settings.should_update_inventory
                },
                {
                    name: 'allow_backorders',
                    label: 'Allow Backorders',
                    type: 'switch',
                    placeholder: 'Allow Backorders',
                    value: settings.allow_backorders
                },
                {
                    name: 'allow_partial_orders',
                    label: 'Allow Partial Orders',
                    type: 'switch',
                    placeholder: 'Allow Partial Orders',
                    value: settings.allow_partial_orders
                },
                {
                    name: 'inventory_enabled',
                    label: 'Inventory Enabled',
                    type: 'switch',
                    placeholder: 'Inventory Enabled',
                    value: settings.inventory_enabled
                },
                {
                    name: 'show_cost',
                    label: 'Show Cost',
                    type: 'switch',
                    placeholder: 'Show Cost',
                    value: settings.show_cost
                }
            ]
        ]

        return formFields
    }

    getProductFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_update_products',
                    label: 'Update Products',
                    type: 'switch',
                    placeholder: 'Update Products',
                    value: settings.should_update_products
                },
                {
                    name: 'show_cost',
                    label: 'Show Cost',
                    type: 'switch',
                    placeholder: 'Show Cost',
                    value: settings.show_cost
                },
                {
                    name: 'show_product_quantity',
                    label: 'Show Product Quantity',
                    type: 'switch',
                    placeholder: 'Show Product Quantity',
                    value: settings.show_product_quantity
                },
                {
                    name: 'fill_products',
                    label: 'Fill Products',
                    type: 'switch',
                    placeholder: 'Fill Products',
                    value: settings.fill_products
                },
                {
                    name: 'convert_products',
                    label: 'Convert Products',
                    type: 'switch',
                    placeholder: 'Convert Products',
                    value: settings.convert_products
                },
                {
                    name: 'default_quantity',
                    label: 'Default Quantity',
                    type: 'switch',
                    placeholder: 'Default Quantity',
                    value: settings.default_quantity
                }
            ]
        ]

        return formFields
    }

    handleCheckboxChange (e) {
        const value = e.target.checked
        const name = e.target.name

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }))
    }

    render () {
        return this.state.loaded === true ? (
            <div className="mt-3 mb-3">
                <ToastContainer/>

                <Card className="mt-3">
                    <CardBody className="d-flex justify-content-between align-items-center">
                        <h6>{translations.product_settings}</h6>
                        <a className="pull-right" onClick={this.handleSubmit}>{translations.save}</a>
                    </CardBody>
                </Card>
                <Card>
                    <CardBody>
                        <FormBuilder
                            handleCheckboxChange={this.handleCheckboxChange}
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getProductFields()}
                        />
                    </CardBody>
                </Card>

                <Card>
                    <CardBody>
                        <FormBuilder
                            handleCheckboxChange={this.handleCheckboxChange}
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getInventoryFields()}
                        />
                    </CardBody>
                </Card>
            </div>
        ) : null
    }
}

export default ProductSettings
