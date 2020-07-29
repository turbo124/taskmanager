import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import {
    DropdownItem,
    Modal,
    ModalBody,
    ModalHeader,
    ModalFooter,
    Button,
    CustomInput,
    FormGroup,
    Label,
    Card,
    CardHeader,
    CardBody,
    Nav,
    NavItem,
    NavLink,
    TabContent,
    TabPane, Input
} from 'reactstrap'
import axios from 'axios'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import { toast } from 'react-toastify'

class EditGroupSetting extends Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            id: this.props.group.id,
            name: this.props.group.name,
            activeTab: '1',
            settings: this.props.group.settings,
            loading: false,
            changesMade: false,
            errors: []
        }

        this.initialState = this.state
        this.toggle = this.toggle.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.handleSettingsChange = this.handleSettingsChange.bind(this)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    handleSettingsChange (event) {
        const name = event.target.name
        let value = event.target.value
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
                },
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
        const formFields = [
            [
                {
                    name: 'payment_terms',
                    label: translations.payment_terms,
                    type: 'payment_terms',
                    placeholder: translations.payment_terms,
                    value: settings.payment_terms,
                    group: 1
                },
                {
                    name: 'payment_type_id',
                    label: translations.payment_type,
                    type: 'payment_type',
                    placeholder: translations.payment_type,
                    value: settings.payment_type_id,
                    group: 1
                },
                {
                    name: 'invoice_terms',
                    label: translations.invoice_terms,
                    type: 'textarea',
                    placeholder: translations.invoice_terms,
                    value: settings.invoice_terms,
                    group: 1
                },
                {
                    name: 'invoice_footer',
                    label: translations.invoice_footer,
                    type: 'textarea',
                    placeholder: translations.invoice_footer,
                    value: settings.invoice_footer,
                    group: 1
                },
                {
                    name: 'quote_terms',
                    label: translations.quote_terms,
                    type: 'textarea',
                    placeholder: translations.quote_terms,
                    value: settings.quote_terms,
                    group: 1
                },
                {
                    name: 'quote_footer',
                    label: translations.quote_footer,
                    type: 'textarea',
                    placeholder: translations.quote_footer,
                    value: settings.quote_footer,
                    group: 1
                },
                {
                    name: 'credit_terms',
                    label: translations.credit_terms,
                    type: 'textarea',
                    placeholder: translations.credit_terms,
                    value: settings.credit_terms,
                    group: 1
                },
                {
                    name: 'credit_footer',
                    label: translations.credit_footer,
                    type: 'textarea',
                    placeholder: translations.credit_footer,
                    value: settings.credit_footer,
                    group: 1
                },
                {
                    name: 'order_terms',
                    label: translations.order_terms,
                    type: 'textarea',
                    placeholder: translations.order_terms,
                    value: settings.order_terms,
                    group: 1
                },
                {
                    name: 'order_footer',
                    label: translations.order_footer,
                    type: 'textarea',
                    placeholder: translations.order_footer,
                    value: settings.order_footer,
                    group: 1
                }
            ]
        ]

        return formFields
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
        const formData = new FormData()
        formData.append('name', this.state.name)
        formData.append('settings', JSON.stringify(this.state.settings))
        formData.append('company_logo', this.state.company_logo)
        formData.append('_method', 'PUT')

        axios.post(`/api/groups/${this.state.id}`, formData, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then((response) => {
                const index = this.props.groups.findIndex(group => group.id === this.state.id)
                this.props.groups[index].name = this.state.name
                this.props.action(this.props.groups)
                this.setState({ changesMade: false })
                this.toggle()
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors
                })
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
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>Edit</DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <ModalHeader toggle={this.toggle}>
                        {translations.edit_group}
                    </ModalHeader>
                    <ModalBody>
                        <Nav tabs>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '1' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('1')
                                    }}>
                                    {translations.details}
                                </NavLink>
                            </NavItem>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '2' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('2')
                                    }}>
                                    {translations.address}
                                </NavLink>
                            </NavItem>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '3' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('3')
                                    }}>
                                    {translations.logo}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '4' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('4')
                                    }}>
                                    {translations.defaults}
                                </NavLink>
                            </NavItem>
                        </Nav>

                        <TabContent activeTab={this.state.activeTab}>
                            <TabPane tabId="1">
                                <Card>
                                    <CardHeader>{translations.details}</CardHeader>
                                    <CardBody>
                                        <FormGroup>
                                            <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                                                id="name" value={this.state.name} placeholder={translations.name}
                                                onChange={this.handleInput.bind(this)}/>
                                            {this.renderErrorFor('name')}
                                        </FormGroup>

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
                                    <CardHeader>{translations.defaults}</CardHeader>
                                    <CardBody>
                                        <FormBuilder
                                            handleChange={this.handleSettingsChange}
                                            formFieldsRows={this.getDefaultFields()}
                                        />
                                    </CardBody>
                                </Card>
                            </TabPane>
                        </TabContent>
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

export default EditGroupSetting
