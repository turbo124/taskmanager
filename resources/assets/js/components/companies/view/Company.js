import React, { Component } from 'react'
import { Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import PaymentModel from '../../models/PaymentModel'
import CompanyModel from '../../models/CompanyModel'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import Overview from './Overview'
import Details from './Details'

export default class Company extends Component {
    constructor (props) {
        super(props)

        this.state = {
            activeTab: '1',
            show_success: false
        }

        this.companyModel = new CompanyModel(this.props.entity)
        this.triggerAction = this.triggerAction.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
    }

    triggerAction (action) {
        const paymentModel = new PaymentModel(null, this.props.entity)
        paymentModel.completeAction(this.props.entity, action)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab }, () => {
                if (this.state.activeTab === '3') {
                    this.loadPdf()
                }
            })
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

        const fields = []

        if (this.companyModel.hasCurrency) {
            fields.currency =
                JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === this.companyModel.currencyId)[0].name
        }

        if (this.props.entity.custom_value1.length) {
            const label1 = this.companyModel.getCustomFieldLabel('Company', 'custom_value1')
            fields[label1] = this.companyModel.formatCustomValue(
                'Company',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.companyModel.getCustomFieldLabel('Company', 'custom_value2')
            fields[label2] = this.companyModel.formatCustomValue(
                'Company',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.companyModel.getCustomFieldLabel('Company', 'custom_value3')
            fields[label3] = this.companyModel.formatCustomValue(
                'Company',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.companyModel.getCustomFieldLabel('Company', 'custom_value4')
            fields[label4] = this.companyModel.formatCustomValue(
                'Company',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

        const address = <React.Fragment>
            {this.props.entity.address_1} <br/>
            {this.props.entity.address_2} <br/>
            {this.props.entity.town} <br/>
            {this.props.entity.city} {this.props.entity.postcode}
        </React.Fragment>

        return (
            <React.Fragment>
                <Nav tabs className="nav-justified disable-scrollbars">
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
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview entity={this.props.entity} user={user} fields={fields}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Details entity={this.props.entity} address={address}/>
                    </TabPane>
                </TabContent>

            </React.Fragment>
        )
    }
}
