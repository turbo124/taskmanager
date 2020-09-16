import React, { Component } from 'react'
import { Button, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import PaymentModel from '../../models/PaymentModel'
import SectionItem from '../../common/entityContainers/SectionItem'
import Transaction from './Transaction'
import CustomerSettings from '../edit/CustomerSettings'
import CustomerModel from '../../models/CustomerModel'
import GatewayModel from '../../models/GatewayModel'
import FileUploads from '../../documents/FileUploads'
import CustomerGateways from '../../gateways/CustomerGateways'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'
import MetaItem from '../../common/entityContainers/MetaItem'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import Overview from './Overview'
import Details from './Details'
import ErrorLog from './ErrorLog'

export default class Customer extends Component {
    constructor (props) {
        super(props)

        this.state = {
            activeTab: '1',
            show_success: false,
            gateways: []
        }

        this.customerModel = new CustomerModel(this.props.entity)
        this.gatewayModel = new GatewayModel()
        this.gateways = this.customerModel.gateways
        this.modules = JSON.parse(localStorage.getItem('modules'))

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        this.user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = this.user_account[0].account.settings

        this.triggerAction = this.triggerAction.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
    }

    componentDidMount () {
        this.getGateways()
    }

    getGateways () {
        this.gatewayModel.getGateways().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ gateways: response }, () => {
                console.log('gateways', this.state.gateways)
            })
        })
    }

    triggerAction (action) {
        const paymentModel = new PaymentModel(null, this.props.entity)
        paymentModel.completeAction(this.props.entity, action)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    render () {
        let user = null

        if (this.props.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.props.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const gateway_tokens = this.state.gateways.length ? this.customerModel.gateway_tokens.map((gatewayToken) => {
            const companyGateway = this.state.gateways.filter(gateway => gateway.id === parseInt(gatewayToken.company_gateway_id))

            console.log('meta', gatewayToken.meta)

            const link = this.gatewayModel.getClientUrl(
                companyGateway[0].gateway_key,
                gatewayToken.customer_reference
            )

            return <SectionItem link={link}
                icon={icons.credit_card}
                title={`${translations.token} > ${companyGateway[0].gateway.name}`}
                subtitle={<MetaItem meta={gatewayToken.meta}/>}/>
        }) : null

        const fields = []

        if (this.customerModel.hasLanguage && this.customerModel.languageId !== parseInt(this.settings.language_id)) {
            fields.language =
                JSON.parse(localStorage.getItem('languages')).filter(language => language.id === this.customerModel.languageId)[0].name
        }

        if (this.customerModel.hasCurrency && this.customerModel.currencyId !== parseInt(this.settings.currency_id)) {
            fields.currency =
                JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === this.customerModel.currencyId)[0].name
        }

        if (this.props.entity.custom_value1.length) {
            const label1 = this.customerModel.getCustomFieldLabel('Customer', 'custom_value1')
            fields[label1] = this.customerModel.formatCustomValue(
                'Customer',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.customerModel.getCustomFieldLabel('Customer', 'custom_value2')
            fields[label2] = this.customerModel.formatCustomValue(
                'Customer',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.customerModel.getCustomFieldLabel('Customer', 'custom_value3')
            fields[label3] = this.customerModel.formatCustomValue(
                'Customer',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.customerModel.getCustomFieldLabel('Customer', 'custom_value4')
            fields[label4] = this.customerModel.formatCustomValue(
                'Customer',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

        const billing = this.props.entity.billing && Object.keys(this.props.entity.billing).length
            ? <React.Fragment>
                {this.props.entity.billing.address_1} <br/>
                {this.props.entity.billing.address_2} <br/>
                {this.props.entity.billing.city} {this.props.entity.billing.zip}

            </React.Fragment> : null

        const shipping = this.props.entity.shipping && Object.keys(this.props.entity.shipping).length
            ? <React.Fragment>
                {this.props.entity.shipping.address_1} <br/>
                {this.props.entity.shipping.address_2} <br/>
                {this.props.entity.shipping.city} {this.props.entity.shipping.zip}

            </React.Fragment> : null

        return (
            <React.Fragment>
                <Nav tabs>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '1' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('1')
                            }}
                        >
                            {translations.overview}
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}
                        >
                            {translations.details}
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '3' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('3')
                            }}
                        >
                            {translations.transactions}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '4' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('4')
                            }}
                        >
                            {translations.documents} ({this.customerModel.fileCount})
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '7' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('7')
                            }}
                        >
                            {translations.error_log}
                        </NavLink>
                    </NavItem>
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview gateway_tokens={gateway_tokens} fields={fields} user={user}
                            entity={this.props.entity}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Details billing={billing} shipping={shipping} entity={this.props.entity}/>
                    </TabPane>

                    <TabPane tabId="3">
                        <Transaction transactions={this.props.entity.transactions}/>
                    </TabPane>

                    <TabPane tabId="4">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader> {translations.documents} </CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Customer" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="5">
                        <CustomerSettings customer={this.props.entity}/>
                    </TabPane>

                    <TabPane tabId="7">
                        <ErrorLog error_logs={this.props.entity.error_logs} />
                    </TabPane>
                </TabContent>

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('5')}
                    button1={{ label: translations.settings }}
                    button2_click={(e) => {
                        e.preventDefault()
                        window.location.href = `/#/gateway-settings?customer_id=${this.props.entity.id}`
                    }}
                    button2={{ label: translations.gateways }}/>

            </React.Fragment>

        )
    }
}
