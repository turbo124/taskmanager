import React, { Component } from 'react'
import { Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import PaymentModel from '../../models/PaymentModel'
import CompanyModel from '../../models/CompanyModel'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import Overview from './Overview'
import Details from './Details'
import FileUploads from '../../documents/FileUploads'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'

export default class Company extends Component {
    constructor ( props ) {
        super ( props )

        this.state = {
            entity: this.props.entity,
            activeTab: '1',
            show_success: false
        }

        this.companyModel = new CompanyModel ( this.state.entity )
        this.triggerAction = this.triggerAction.bind ( this )
        this.toggleTab = this.toggleTab.bind ( this )
    }

    triggerAction ( action ) {
        if ( action === 'newExpense' ) {
            location.href = `/#/expenses?entity_type=company&entity_id=${this.state.entity.id}`
        }

        const paymentModel = new PaymentModel ( null, this.state.entity )
        paymentModel.completeAction ( this.state.entity, action )
    }

    toggleTab ( tab ) {
        if ( this.state.activeTab !== tab ) {
            this.setState ( { activeTab: tab } )
        }
    }

    render () {
        let user = null

        if ( this.state.entity.assigned_to ) {
            const assigned_user = JSON.parse ( localStorage.getItem ( 'users' ) ).filter ( user => user.id === parseInt ( this.state.entity.assigned_to ) )
            user = <EntityListTile entity={translations.user}
                                   title={`${assigned_user[ 0 ].first_name} ${assigned_user[ 0 ].last_name}`}
                                   icon={icons.user}/>
        }

        const fields = []

        if ( this.companyModel.hasCurrency ) {
            fields.currency =
                JSON.parse ( localStorage.getItem ( 'currencies' ) ).filter ( currency => currency.id === this.companyModel.currencyId )[ 0 ].name
        }

        if ( this.state.entity.custom_value1.length ) {
            const label1 = this.companyModel.getCustomFieldLabel ( 'Company', 'custom_value1' )
            fields[ label1 ] = this.companyModel.formatCustomValue (
                'Company',
                'custom_value1',
                this.state.entity.custom_value1
            )
        }

        if ( this.state.entity.custom_value2.length ) {
            const label2 = this.companyModel.getCustomFieldLabel ( 'Company', 'custom_value2' )
            fields[ label2 ] = this.companyModel.formatCustomValue (
                'Company',
                'custom_value2',
                this.state.entity.custom_value2
            )
        }

        if ( this.state.entity.custom_value3.length ) {
            const label3 = this.companyModel.getCustomFieldLabel ( 'Company', 'custom_value3' )
            fields[ label3 ] = this.companyModel.formatCustomValue (
                'Company',
                'custom_value3',
                this.state.entity.custom_value3
            )
        }

        if ( this.state.entity.custom_value4.length ) {
            const label4 = this.companyModel.getCustomFieldLabel ( 'Company', 'custom_value4' )
            fields[ label4 ] = this.companyModel.formatCustomValue (
                'Company',
                'custom_value4',
                this.state.entity.custom_value4
            )
        }

        const address = <React.Fragment>
            {this.state.entity.address_1} <br/>
            {this.state.entity.address_2} <br/>
            {this.state.entity.town} <br/>
            {this.state.entity.city} {this.state.entity.postcode}
        </React.Fragment>

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
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab ( '2' )
                            }}
                        >
                            {translations.details}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '3' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab ( '3' )
                            }}
                        >
                            {translations.documents} ({this.companyModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview entity={this.state.entity} user={user} fields={fields}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Details entity={this.state.entity} address={address}/>
                    </TabPane>

                    <TabPane tabId="3">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Company" entity={this.state.entity}
                                                     user_id={this.state.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>
                </TabContent>

                <BottomNavigationButtons button1_click={( e ) => this.triggerAction ( 'archive' )}
                                         button1={{ label: translations.archive }}
                                         button2_click={( e ) => this.triggerAction ( 'newExpense' )}
                                         button2={{ label: translations.new_expense }}/>

            </React.Fragment>
        )
    }
}
