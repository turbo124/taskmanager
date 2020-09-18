import React, { Component } from 'react'
import FormBuilder from '../../settings/FormBuilder'
import {
    Card,
    CardBody,
    CardHeader,
    CustomInput,
    FormGroup,
    Input,
    Label,
    Nav,
    NavItem,
    NavLink,
    TabContent,
    TabPane
} from 'reactstrap'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import { consts } from '../../utils/_consts'
import GroupModel from '../../models/GroupModel'

export default class Settings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            activeTab: '1'
        }

        this.groupModel = new GroupModel(this.props.group)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    getAddressFields () {
        const settings = this.props.settings

        return [
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
    }

    getFormFields () {
        const settings = this.props.settings

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
                    name: 'language_id',
                    label: translations.language,
                    type: 'language',
                    placeholder: translations.language,
                    value: settings.language_id,
                    group: 3
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
        const { settings } = this.props
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

    getInvoiceFields () {
        const settings = this.props.settings

        const formFields = [
            [
                {
                    name: 'should_lock_invoice',
                    label: translations.lock_invoice,
                    type: 'select',
                    value: settings.should_lock_invoice,
                    options: [
                        {
                            value: consts.lock_invoices_off,
                            text: translations.off
                        },
                        {
                            value: consts.lock_invoices_sent,
                            text: translations.when_sent
                        },
                        {
                            value: consts.lock_invoices_paid,
                            text: translations.when_paid
                        }
                    ]
                },
                {
                    name: 'should_email_invoice',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_invoice,
                    group: 1
                },
                {
                    name: 'should_archive_invoice',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_invoice,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getOrderFields () {
        const settings = this.props.settings

        const formFields = [
            [
                {
                    name: 'should_email_order',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_order,
                    group: 1
                },
                {
                    name: 'should_archive_order',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_order,
                    group: 1
                },
                {
                    name: 'should_convert_order',
                    label: 'Auto Convert',
                    icon: `fa ${icons.book}`,
                    type: 'switch',
                    value: settings.should_convert_order,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getLeadFields () {
        const settings = this.props.settings

        const formFields = [
            [
                {
                    name: 'should_email_lead',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_lead,
                    group: 1
                },
                {
                    name: 'should_archive_lead',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_lead,
                    group: 1
                },
                {
                    name: 'should_convert_lead',
                    label: 'Auto Convert',
                    icon: `fa ${icons.book}`,
                    type: 'switch',
                    value: settings.should_convert_lead,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getQuoteFields () {
        const settings = this.props.settings

        const formFields = [
            [
                {
                    name: 'should_email_quote',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_quote,
                    group: 1
                },
                {
                    name: 'should_archive_quote',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_quote,
                    group: 1
                },
                {
                    name: 'should_convert_quote',
                    label: 'Auto Convert',
                    icon: `fa ${icons.book}`,
                    type: 'switch',
                    value: settings.should_convert_quote,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getInvoiceNumberFields () {
        const settings = this.props.settings

        console.log('settings', settings)

        const formFields = [
            [
                {
                    name: 'invoice_number_pattern',
                    label: 'Invoice Number Pattern',
                    type: 'text',
                    placeholder: 'Invoice Number Pattern',
                    value: settings.invoice_number_pattern,
                    group: 1
                },
                {
                    name: 'invoice_number_counter',
                    label: 'Invoice Counter',
                    type: 'text',
                    placeholder: 'Invoice Counter',
                    value: settings.invoice_number_counter
                }
            ]
        ]

        return formFields
    }

    getOrderNumberFields () {
        const settings = this.props.settings

        console.log('settings', settings)

        const formFields = [
            [
                {
                    name: 'order_number_pattern',
                    label: 'Order Number Pattern',
                    type: 'text',
                    placeholder: 'Order Number Pattern',
                    value: settings.order_number_pattern,
                    group: 1
                },
                {
                    name: 'order_number_counter',
                    label: 'Order Counter',
                    type: 'text',
                    placeholder: 'Order Counter',
                    value: settings.order_number_counter
                }
            ]
        ]

        return formFields
    }

    getQuoteNumberFields () {
        const settings = this.props.settings

        const formFields = [
            [
                {
                    name: 'quote_number_pattern',
                    label: 'Quote Number Pattern',
                    type: 'text',
                    placeholder: 'Quote Number Pattern',
                    value: settings.quote_number_pattern,
                    group: 1
                },
                {
                    name: 'quote_number_counter',
                    label: 'Quote Counter',
                    type: 'text',
                    placeholder: 'Quote Counter',
                    value: settings.quote_number_counter
                },
                {
                    name: 'quote_design_id',
                    label: 'Quote Design',
                    type: 'select',
                    value: settings.quote_design_id,
                    options: [
                        {
                            value: '1',
                            text: 'Clean'
                        },
                        {
                            value: '2',
                            text: 'Bold'
                        },
                        {
                            value: '3',
                            text: 'Modern'
                        },
                        {
                            value: '4',
                            text: 'Plain'
                        }
                    ],
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getCreditNumberFields () {
        const settings = this.props.settings

        const formFields = [
            [
                {
                    name: 'credit_number_pattern',
                    label: 'Credit Number Pattern',
                    type: 'text',
                    placeholder: 'Credit Number Pattern',
                    value: settings.credit_number_pattern,
                    group: 1
                },
                {
                    name: 'credit_number_counter',
                    label: 'Credit Counter',
                    type: 'text',
                    placeholder: 'Credit Counter',
                    value: settings.credit_number_counter
                }
                // {
                //     name: 'credit_design_id',
                //     label: 'Credit Design',
                //     type: 'select',
                //     value: settings.credit_design_id,
                //     options: [
                //         {
                //             value: '1',
                //             text: 'Clean'
                //         },
                //         {
                //             value: '2',
                //             text: 'Bold'
                //         },
                //         {
                //             value: '3',
                //             text: 'Modern'
                //         },
                //         {
                //             value: '4',
                //             text: 'Plain'
                //         }
                //     ],
                //     group: 1
                // }
            ]
        ]

        return formFields
    }

    getPaymentNumberFields () {
        const settings = this.props.settings

        const formFields = [
            [
                {
                    name: 'payment_number_counter',
                    label: 'Payment Counter',
                    type: 'text',
                    placeholder: 'Payment Counter',
                    value: settings.payment_number_counter
                },
                {
                    name: 'payment_terms',
                    label: 'Payment Terms',
                    type: 'select',
                    placeholder: 'Payment Terms',
                    value: settings.payment_terms,
                    options: [
                        {
                            value: '1',
                            text: 'Yes'
                        },
                        {
                            value: '0',
                            text: 'No'
                        }
                    ]
                }
            ]
        ]

        return formFields
    }

    render () {
        return (
            <React.Fragment>
                <Nav tabs className="nav-justified disable-scrollbars">
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
                    {/* <NavItem> */}
                    {/*    <NavLink */}
                    {/*        className={this.state.activeTab === '3' ? 'active' : ''} */}
                    {/*        onClick={() => { */}
                    {/*            this.toggleTab('3') */}
                    {/*        }}> */}
                    {/*        {translations.logo} */}
                    {/*    </NavLink> */}
                    {/* </NavItem> */}

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '4' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('4')
                            }}>
                            {translations.defaults}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '5' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('5')
                            }}>
                            {translations.invoices}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '6' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('6')
                            }}>
                            {translations.quotes}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '7' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('7')
                            }}>
                            {translations.leads}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '8' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('8')
                            }}>
                            {translations.orders}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '9' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('9')
                            }}>
                            {translations.credits}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '10' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('10')
                            }}>
                            {translations.payments}
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
                                    <Input className={this.props.hasErrorFor('name') ? 'is-invalid' : ''}
                                        type="text" name="name"
                                        id="name" value={this.props.group.name} placeholder={translations.name}
                                        onChange={this.props.handleInput}/>
                                    {this.props.renderErrorFor('name')}
                                </FormGroup>

                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
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
                                    handleChange={this.props.handleSettingsChange}
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
                                    <CustomInput className="mt-4 mb-4"
                                        onChange={this.props.handleFileChange}
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
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getDefaultFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="5">
                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getInvoiceFields()}
                                />
                            </CardBody>
                        </Card>

                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getInvoiceNumberFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="6">
                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getQuoteFields()}
                                />
                            </CardBody>
                        </Card>

                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getQuoteNumberFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="7">
                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getLeadFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="8">
                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getOrderFields()}
                                />
                            </CardBody>
                        </Card>

                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getOrderNumberFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="9">
                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getCreditNumberFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="10">
                        <Card>
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.props.handleSettingsChange}
                                    formFieldsRows={this.getPaymentNumberFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                </TabContent>
            </React.Fragment>
        )
    }
}