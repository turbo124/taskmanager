import React, { Component } from 'react'
import {
    Button,
    Card,
    CardBody,
    CardHeader,
    Col,
    ListGroup,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import PaymentModel from '../models/PaymentModel'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SectionItem from '../common/entityContainers/SectionItem'
import InfoItem from '../common/entityContainers/InfoItem'
import Transaction from '../customers/Transaction'
import CustomerSettings from '../customers/CustomerSettings'
import CustomerModel from '../models/CustomerModel'
import GatewayModel from '../models/GatewayModel'
import FileUploads from '../attachments/FileUploads'
import CustomerGateways from '../gateways/CustomerGateways'
import BottomNavigationButtons from '../common/BottomNavigationButtons'

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
        const tokenMap = []
        const gatewayMap = []
        const linkMap = {}

        const gateway_tokens = this.state.gateways.length ? this.customerModel.gateway_tokens.map((gatewayToken) => {
            const companyGateway = this.state.gateways.filter(gateway => gateway.id === parseInt(gatewayToken.company_gateway_id));
           
            const link = this.gatewayModel.getClientUrl(
                gatewayId: companyGateway.gatewayId,
                customerReference: gatewayToken.customer_reference,
            )
             return <SectionItem link={link}
                                    icon={icons.credit_card} title={`${translations.token} > ${companyGateway.gateway.name}`} subtitle={gatewayToken.customer_reference} />
                                }
        }) : null

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
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.paid_to_date} value_1={this.props.entity.paid_to_date}
                            heading_2={translations.balance} value_2={this.props.entity.balance}/>

                        {this.props.entity.private_notes.length &&
                        <Alert color="dark col-12">
                            {this.props.entity.private_notes}
                        </Alert>
                        }

                        <Row>
                            <ListGroup className="col-12">
                                {this.modules && this.modules.invoices &&
                                <SectionItem link={`/#/invoice?customer_id=${this.props.entity.id}`}
                                    icon={icons.document} title={translations.invoices}/>
                                }

                                {this.modules && this.modules.payments &&
                                <SectionItem link={`/#/payments?customer_id=${this.props.entity.id}`}
                                    icon={icons.credit_card} title={translations.payments}/>
                                }

                                {this.modules && this.modules.invoices &&
                                <SectionItem link={`/#/projects?customer_id=${this.props.entity.id}`}
                                    icon={icons.project} title={translations.projects}/>

                                }

                                {this.modules && this.modules.tasks &&
                                <SectionItem link={`/#/tasks?customer_id=${this.props.entity.id}`} icon={icons.task}
                                    title={translations.tasks}/>
                                }

                                {this.modules && this.modules.expenses &&
                                <SectionItem link={`/#/expenses?customer_id=${this.props.entity.id}`}
                                    icon={icons.expense} title={translations.expenses}/>
                                }

                                {this.modules && this.modules.orders &&
                                <SectionItem link={`/#/orders?customer_id=${this.props.entity.id}`} icon={icons.order}
                                    title={translations.orders}/>
                                }

                            </ListGroup>
                        </Row>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <ListGroup className="col-12">
                                {this.props.entity.contacts.map((contact, index) => (
                                    <React.Fragment>
                                        <InfoItem icon={icons.envelope}
                                            first_value={`${contact.first_name} ${contact.last_name}`}
                                            value={`${contact.email}`} title={translations.email}/>
                                        <InfoItem icon={icons.phone}
                                            first_value={`${contact.first_name} ${contact.last_name}`}
                                            value={`${contact.phone}`} title={translations.phone_number}/>
                                    </React.Fragment>
                                ))}

                                <InfoItem icon={icons.link} value={this.props.entity.website}
                                    title={translations.website}/>
                                <InfoItem icon={icons.building} value={this.props.entity.vat_number}
                                    title={translations.vat_number}/>
                                <InfoItem icon={icons.list} value={this.props.entity.number}
                                    title={translations.number}/>
                                <InfoItem icon={icons.map_marker} value={billing} title={translations.billing_address}/>
                                <InfoItem icon={icons.map_marker} value={shipping}
                                    title={translations.shipping_address}/>
                            </ListGroup>
                        </Row>
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

                    <TabPane tabId="6">
                        <CustomerGateways model={this.customerModel}/>

                        <Button block onClick={(e) => {
                            e.preventDefault()
                            window.location.href = `/#/gateway-settings?customer_id=${this.props.entity.id}`
                        }}>{translations.gateways} </Button>
                    </TabPane>
                </TabContent>

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('5')} button1={{ label: translations.settings }}
                    button2_click={(e) => this.toggleTab('6')} button2={{ label: translations.gateways }}/>

            </React.Fragment>

        )
    }
}
