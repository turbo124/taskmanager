import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Alert, Card, CardBody } from 'reactstrap'
import axios from 'axios'
import { translations } from '../common/_translations'
import Snackbar from '@material-ui/core/Snackbar'

class ProductSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {},
            success: false,
            error: false
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
                this.setState({ success: true })
            })
            .catch((error) => {
                console.error(error)
                this.setState({ error: true })
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

    handleClose () {
        this.setState({ success: false, error: false })
    }

    render () {
        return this.state.loaded === true ? (
            <React.Fragment>
                <Snackbar open={this.state.success} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                    <Alert severity="success">
                        {translations.settings_saved}
                    </Alert>
                </Snackbar>

                <Snackbar open={this.state.error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                    <Alert severity="danger">
                        {translations.settings_not_saved}
                    </Alert>
                </Snackbar>

                <div className="topbar">
                    <Card className="m-0">
                        <CardBody className="p-0">
                            <div className="d-flex justify-content-between align-items-center">
                                <h4 className="pl-3 pt-2 pb-2">{translations.product_settings}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>
                        </CardBody>
                    </Card>
                </div>

                <Card className="fixed-margin-extra border-0">
                    <CardBody>
                        <FormBuilder
                            handleCheckboxChange={this.handleCheckboxChange}
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getProductFields()}
                        />
                    </CardBody>
                </Card>

                <Card className="border-0">
                    <CardBody>
                        <FormBuilder
                            handleCheckboxChange={this.handleCheckboxChange}
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getInventoryFields()}
                        />
                    </CardBody>
                </Card>
            </React.Fragment>
        ) : null
    }
}

export default ProductSettings
