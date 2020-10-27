import React, { Component } from 'react'
import { Alert, Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import FormatMoney from '../../common/FormatMoney'
import FormatDate from '../../common/FormatDate'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import PaymentModel from '../../models/PaymentModel'
import Refund from '../edit/Refund'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'
import GatewayModel from '../../models/GatewayModel'
import SectionItem from '../../common/entityContainers/SectionItem'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import Overview from './Overview'

export default class Payment extends Component {
    constructor ( props ) {
        super ( props )

        this.state = {
            entity: this.props.entity,
            activeTab: '1',
            show_success: false,
            gateways: []
        }

        this.gatewayModel = new GatewayModel ()
        this.paymentModel = new PaymentModel ( this.state.entity.invoices, this.state.entity, this.state.entity.credits )
        this.triggerAction = this.triggerAction.bind ( this )
        this.toggleTab = this.toggleTab.bind ( this )
        this.refresh = this.refresh.bind ( this )
    }

    componentDidMount () {
        this.getGateways ()
    }

    refresh ( entity ) {
        this.paymentModel = new PaymentModel ( this.state.entity.invoices, entity, this.state.entity.credits )
        this.setState ( { entity: entity } )
    }

    getGateways () {
        this.gatewayModel.getGateways ().then ( response => {
            if ( !response ) {
                alert ( 'error' )
            }

            this.setState ( { gateways: response }, () => {
                console.log ( 'gateways', this.state.gateways )
            } )
        } )
    }

    toggleTab ( tab ) {
        if ( this.state.activeTab !== tab ) {
            this.setState ( { activeTab: tab } )
        }
    }

    triggerAction ( action ) {
        this.paymentModel.completeAction ( this.state.entity, action ).then ( response => {
            this.setState ( { show_success: true }, () => {
                this.props.updateState ( response, this.refresh )
            } )

            setTimeout (
                function () {
                    this.setState ( { show_success: false } )
                }
                    .bind ( this ),
                2000
            )
        } )
    }

    render () {
        const customer = this.props.customers.filter ( customer => customer.id === parseInt ( this.state.entity.customer_id ) )
        const paymentableInvoices = this.paymentModel.paymentable_invoices
        const paymentableCredits = this.paymentModel.paymentable_credits
        const listClass = !Object.prototype.hasOwnProperty.call ( localStorage, 'dark_theme' ) || (localStorage.getItem ( 'dark_theme' ) && localStorage.getItem ( 'dark_theme' ) === 'true') ? 'list-group-item-dark' : ''

        const companyGateway = this.state.gateways.length ? this.state.gateways.filter ( gateway => gateway.id === parseInt ( this.state.entity.company_gateway_id ) ) : []
        let gateway = null

        if ( companyGateway.length ) {
            const link = this.gatewayModel.getPaymentUrl ( companyGateway[ 0 ].gateway_key, this.state.entity.transaction_reference )
            gateway = <SectionItem link={link}
                                   icon={icons.credit_card}
                                   title={`${translations.token} > ${companyGateway[ 0 ].name}`}/>
        }

        const fields = []

        if ( this.state.entity.custom_value1.length ) {
            const label1 = this.paymentModel.getCustomFieldLabel ( 'Payment', 'custom_value1' )
            fields[ label1 ] = this.paymentModel.formatCustomValue (
                'Payment',
                'custom_value1',
                this.state.entity.custom_value1
            )
        }

        if ( this.state.entity.custom_value2.length ) {
            const label2 = this.paymentModel.getCustomFieldLabel ( 'Payment', 'custom_value2' )
            fields[ label2 ] = this.paymentModel.formatCustomValue (
                'Payment',
                'custom_value2',
                this.state.entity.custom_value2
            )
        }

        if ( this.state.entity.custom_value3.length ) {
            const label3 = this.paymentModel.getCustomFieldLabel ( 'Payment', 'custom_value3' )
            fields[ label3 ] = this.paymentModel.formatCustomValue (
                'Payment',
                'custom_value3',
                this.state.entity.custom_value3
            )
        }

        if ( this.state.entity.custom_value4.length ) {
            const label4 = this.paymentModel.getCustomFieldLabel ( 'Payment', 'custom_value4' )
            fields[ label4 ] = this.paymentModel.formatCustomValue (
                'Payment',
                'custom_value4',
                this.state.entity.custom_value4
            )
        }

        if ( this.state.entity.date.length ) {
            fields.date = <FormatDate date={this.state.entity.date}/>
        }
        if ( this.state.entity.type_id.toString ().length ) {
            const paymentType = JSON.parse ( localStorage.getItem ( 'payment_types' ) ).filter ( payment_type => payment_type.id === parseInt ( this.state.entity.type_id ) )
            if ( paymentType.length ) {
                fields.payment_type = paymentType[ 0 ].name
            }
        }
        if ( this.state.entity.transaction_reference.length ) {
            fields.transaction_reference = this.state.entity.transaction_reference
        }
        if ( this.state.entity.refunded !== 0 ) {
            fields.refunded = <FormatMoney amount={this.state.entity.refunded} customers={this.props.customers}/>
        }

        let user = null

        if ( this.state.entity.assigned_to ) {
            const assigned_user = JSON.parse ( localStorage.getItem ( 'users' ) ).filter ( user => user.id === parseInt ( this.state.entity.assigned_to ) )
            user = <EntityListTile entity={translations.user}
                                   title={`${assigned_user[ 0 ].first_name} ${assigned_user[ 0 ].last_name}`}
                                   icon={icons.user}/>
        }

        return (
            <React.Fragment>
                <Nav tabs className="nav-justified disable-scrollbars">
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '1' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab ( '1' )
                            }}
                        >
                            {translations.overview}
                        </NavLink>
                    </NavItem>
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview customers={this.props.customers} entity={this.state.entity} user={user}
                                  customer={customer} paymentableInvoices={paymentableInvoices}
                                  paymentableCredits={paymentableCredits} fields={fields} gateway={gateway}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Refund customers={this.props.customer} payment={this.state.entity}
                                modal={false}
                                allInvoices={[]}
                                allCredits={[]}
                                invoices={null}
                                credits={null}
                                paymentables={this.state.entity.paymentables}
                        />
                    </TabPane>
                </TabContent>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                <BottomNavigationButtons button1_click={( e ) => this.toggleTab ( '2' )}
                                         button1={{ label: translations.refund }}
                                         button2_click={( e ) => this.triggerAction ( 'archive' )}
                                         button2={{ label: translations.archive }}/>
            </React.Fragment>
        )
    }
}
