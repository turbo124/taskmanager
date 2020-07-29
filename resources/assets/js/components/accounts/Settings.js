import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import {
    Button,
    Card,
    CardBody,
    CardHeader,
    CustomInput,
    FormGroup,
    Label,
    Nav,
    NavItem,
    NavLink,
    TabContent,
    TabPane
} from 'reactstrap'
import axios from 'axios'
import { toast, ToastContainer } from 'react-toastify'
import { translations } from '../common/_translations'
import { icons } from '../common/_icons'
import BlockButton from '../common/BlockButton'

class Settings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: this.props.match.params.add && this.props.match.params.add === 'true' ? null : localStorage.getItem('account_id'),
            loaded: false,
            settings: {},
            company_logo: null,
            activeTab: '1'
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getAccount = this.getAccount.bind(this)
        this.toggle = this.toggle.bind(this)
    }

    componentDidMount () {
        this.getAccount()
    }

    toggle (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    getAccount () {
        if (this.state.id === null) {
            this.setState({ loaded: true })
            return
        }

        axios.get(`api/accounts/${this.state.id}`)
            .then((r) => {
                this.setState({
                    loaded: true,
                    settings: r.data.settings
                })
            })
            .catch((e) => {
                toast.error('There was an issue updating the settings')
            })
    }

    handleChange (event) {
        this.setState({ [event.target.name]: event.target.value })
    }

    handleSettingsChange (event) {
        const name = event.target.name
        let value = event.target.type === 'checkbox' ? event.target.checked : event.target.value
        value = value === 'true' ? true : value
        value = value === 'false' ? false : value

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }))
    }

    handleFileChange (e) {
        this.setState({
            [e.target.name]: e.target.files[0]
        })
    }

    handleSubmit (e) {
        const url = this.state.id === null ? '/api/accounts' : `/api/accounts/${this.state.id}`

        const formData = new FormData()
        formData.append('settings', JSON.stringify(this.state.settings))
        formData.append('company_logo', this.state.company_logo)

        if (this.state.id !== null) {
            formData.append('_method', 'PUT')
        }

        axios.post(url, formData, {
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

    getAddressFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'address1',
                    label: translations.address_1,
                    type: 'text',
                    placeholder: translations.address_1,
                    value: settings.address1,
                    group: 2
                },
                {
                    name: 'address2',
                    label: translations.address_2,
                    type: 'text',
                    placeholder: translations.address_2,
                    value: settings.address2,
                    group: 2
                },
                {
                    name: 'city',
                    label: translations.city,
                    type: 'text',
                    placeholder: translations.city,
                    value: settings.city,
                    group: 2
                },
                {
                    name: 'state',
                    label: translations.town,
                    type: 'text',
                    placeholder: translations.town,
                    value: settings.state,
                    group: 2
                },
                {
                    name: 'postal_code',
                    label: translations.postcode,
                    type: 'text',
                    placeholder: translations.postcode,
                    value: settings.postal_code,
                    group: 2
                },
                {
                    name: 'country_id',
                    label: translations.country,
                    type: 'country',
                    placeholder: translations.country,
                    value: settings.country_id,
                    group: 2
                }
            ]
        ]

        return formFields
    }

    getFormFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'name',
                    label: translations.name,
                    type: 'text',
                    placeholder: translations.name,
                    value: settings.name,
                    group: 1
                },
                {
                    name: 'website',
                    label: translations.website,
                    type: 'text',
                    placeholder: translations.website,
                    value: settings.website,
                    group: 1
                },
                {
                    name: 'phone',
                    label: translations.phone_number,
                    type: 'text',
                    placeholder: translations.phone_number,
                    value: settings.phone,
                    group: 1
                },
                {
                    name: 'email',
                    label: translations.email,
                    type: 'text',
                    placeholder: translations.email,
                    value: settings.email,
                    group: 1
                },
                {
                    name: 'vat_number',
                    label: translations.vat_number,
                    type: 'text',
                    placeholder: translations.vat_number,
                    value: settings.vat_number,
                    group: 1
                },

                {
                    name: 'currency_id',
                    label: translations.currency,
                    type: 'currency',
                    placeholder: translations.currency,
                    value: settings.currency_id,
                    group: 3
                },
                {
                    name: 'email_style',
                    label: translations.email_style,
                    type: 'select',
                    value: settings.design,
                    group: 3,
                    options: [
                        {
                            value: 'plain',
                            text: translations.plain
                        },
                        {
                            value: 'light',
                            text: translations.light
                        },
                        {
                            value: 'dark',
                            text: translations.dark
                        },
                        {
                            value: 'custom',
                            text: translations.custom
                        }
                    ]
                },
                {
                    name: 'inclusive_taxes',
                    label: translations.inclusive_taxes,
                    type: 'select',
                    value: settings.inclusive_taxes,
                    group: 3,
                    options: [
                        {
                            value: true,
                            text: translations.yes
                        },
                        {
                            value: false,
                            text: translations.no
                        }
                    ]
                },
                {
                    name: 'charge_gateway_to_customer',
                    label: translations.charge_gateway_to_customer,
                    type: 'select',
                    value: settings.charge_gateway_to_customer,
                    group: 3,
                    options: [
                        {
                            value: true,
                            text: translations.yes
                        },
                        {
                            value: false,
                            text: translations.no
                        }
                    ]
                }
            ]
        ]

        return formFields
    }

    getPaymentTermFields () {
        const { settings } = this.state

        return [
            [
                {
                    name: 'payment_type_id',
                    label: translations.payment_type,
                    type: 'payment_type',
                    placeholder: translations.payment_type,
                    value: settings.payment_type_id,
                    group: 1
                },
                {
                    name: 'payment_terms',
                    label: translations.payment_terms,
                    type: 'payment_terms',
                    placeholder: translations.payment_terms,
                    value: settings.payment_terms,
                    group: 1
                }
            ]
        ]
    }

    getPaymentEmailFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_send_email_for_manual_payment',
                    label: translations.should_send_email_for_manual_payment,
                    help_text: translations.should_send_email_for_manual_payment_help_text,
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    placeholder: translations.should_send_email_for_manual_payment,
                    value: settings.should_send_email_for_manual_payment,
                    class_name: 'col-12'
                },
                {
                    name: 'should_send_email_for_online_payment',
                    label: translations.should_send_email_for_online_payment,
                    help_text: translations.should_send_email_for_online_payment_help_text,
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    placeholder: translations.should_send_email_for_online_payment,
                    value: settings.should_send_email_for_online_payment,
                    class_name: 'col-12'
                }
            ]
        ]

        return formFields
    }

    getDefaultFields () {
        const { settings } = this.state

        const defaults = []

        const modules = JSON.parse(localStorage.getItem('modules'))

        if (modules.invoices) {
            defaults.push({
                name: 'invoice_terms',
                label: translations.invoice_terms,
                type: 'textarea',
                placeholder: translations.invoice_terms,
                value: settings.invoice_terms,
                group: 1
            })
            defaults.push({
                name: 'invoice_footer',
                label: translations.invoice_footer,
                type: 'textarea',
                placeholder: translations.invoice_footer,
                value: settings.invoice_footer,
                group: 1
            })
        }

        if (modules.quotes) {
            defaults.push({
                name: 'quote_terms',
                label: translations.quote_terms,
                type: 'textarea',
                placeholder: translations.quote_terms,
                value: settings.quote_terms,
                group: 1
            })

            defaults.push({
                name: 'quote_footer',
                label: translations.quote_footer,
                type: 'textarea',
                placeholder: translations.quote_footer,
                value: settings.quote_footer,
                group: 1
            })
        }

        if (modules.credits) {
            defaults.push({
                name: 'credit_terms',
                label: translations.credit_terms,
                type: 'textarea',
                placeholder: translations.credit_terms,
                value: settings.credit_terms,
                group: 1
            })

            defaults.push({
                name: 'credit_footer',
                label: translations.credit_footer,
                type: 'textarea',
                placeholder: translations.credit_footer,
                value: settings.credit_footer,
                group: 1
            })
        }

        if (modules.orders) {
            defaults.push({
                name: 'order_terms',
                label: translations.order_terms,
                type: 'textarea',
                placeholder: translations.order_terms,
                value: settings.order_terms,
                group: 1
            })

            defaults.push({
                name: 'order_footer',
                label: translations.order_footer,
                type: 'textarea',
                placeholder: translations.order_footer,
                value: settings.order_footer,
                group: 1
            })
        }

        const formFields = []
        formFields.push(defaults)
        return formFields
    }

    render () {
        return this.state.loaded === true ? (
            <React.Fragment>
                <ToastContainer/>
                <Card className="mt-3">
                    <CardBody className="d-flex justify-content-between align-items-center">
                        <Nav tabs className="setting-tabs">
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '1' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggle('1')
                                    }}>
                                    {translations.details}
                                </NavLink>
                            </NavItem>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '2' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggle('2')
                                    }}>
                                    {translations.address}
                                </NavLink>
                            </NavItem>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '3' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggle('3')
                                    }}>
                                    {translations.logo}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '4' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggle('4')
                                    }}>
                                    {translations.defaults}
                                </NavLink>
                            </NavItem>
                        </Nav>

                        <a className="pull-right" onClick={this.handleSubmit}>{translations.save}</a>
                    </CardBody>
                </Card>

                <div className="scrollable-settings">
                    <TabContent activeTab={this.state.activeTab}>
                        <TabPane tabId="1">
                            <Card>
                                <CardHeader>{translations.details}</CardHeader>
                                <CardBody>
                                    <FormBuilder
                                        handleChange={this.handleSettingsChange}
                                        formFieldsRows={this.getFormFields()}
                                    />
                                </CardBody>
                            </Card>
                        </TabPane>
                        <TabPane tabId="2">
                            <Card>
                                <CardHeader>{translations.address}</CardHeader>
                                <CardBody>
                                    <FormBuilder
                                        handleChange={this.handleSettingsChange}
                                        formFieldsRows={this.getAddressFields()}
                                    />
                                </CardBody>
                            </Card>
                        </TabPane>
                        <TabPane tabId="3">
                            <Card>
                                <CardHeader>{translations.logo}</CardHeader>
                                <CardBody>
                                    <FormGroup>

                                        <Label>{translations.logo}</Label>
                                        <CustomInput className="mt-4 mb-4" onChange={this.handleFileChange.bind(this)}
                                            type="file"
                                            id="company_logo" name="company_logo"
                                            label="Logo"/>
                                    </FormGroup>
                                </CardBody>
                            </Card>
                        </TabPane>

                        <TabPane tabId="4">
                            <Card>
                                <CardBody>
                                    <FormBuilder
                                        handleChange={this.handleSettingsChange}
                                        formFieldsRows={this.getPaymentTermFields()}
                                    />

                                    <BlockButton icon={icons.cog} button_text={translations.configure_payment_terms}
                                        button_link="/#/payment_terms"/>
                                </CardBody>
                            </Card>

                            <Card>
                                <CardBody>
                                    <FormBuilder
                                        handleChange={this.handleSettingsChange}
                                        formFieldsRows={this.getPaymentEmailFields()}
                                    />
                                </CardBody>
                            </Card>

                            <Card>
                                <CardBody>
                                    <FormBuilder
                                        handleChange={this.handleSettingsChange}
                                        formFieldsRows={this.getDefaultFields()}
                                    />
                                </CardBody>
                            </Card>
                        </TabPane>
                    </TabContent>
                </div>

            </React.Fragment>
        ) : null
    }
}

export default Settings
