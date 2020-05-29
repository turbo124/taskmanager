import React, { Component } from 'react'
import {
    TabContent,
    TabPane,
    Nav,
    NavItem,
    NavLink,
    Row,
    ListGroup
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'
import PaymentModel from '../models/PaymentModel'
import ViewEntityHeader from "../common/entityContainers/ViewEntityHeader";
import SectionItem from "../common/entityContainers/SectionItem";
import InfoItem from "../common/entityContainers/InfoItem";

export default class Company extends Component {
    constructor (props) {
        super(props)

        this.state = {
            activeTab: '1',
            show_success: false
        }

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
        const address = <React.Fragment>
            {this.props.entity.address_1} <br/>
            {this.props.entity.address_2} <br/>
            {this.props.entity.town} <br/>
            {this.props.entity.city} {this.props.entity.postcode}
        </React.Fragment>

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
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.paid_to_date} value_1={this.props.entity.paid_to_date}
                            heading_2={translations.balance} value_2={this.props.entity.balance}/>

                        <Row>
                            <ListGroup className="col-12">
                                <SectionItem link={`/#/expenses?company_id=${this.props.entity.id}`}
                                    icon={icons.expense} title={translations.expenses}/>
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
                                <InfoItem icon={icons.map_marker} value={address} title={translations.billing_address}/>
                            </ListGroup>
                        </Row>
                    </TabPane>
                </TabContent>

            </React.Fragment>
        )
    }
}
