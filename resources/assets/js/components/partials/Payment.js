import React, { Component } from 'react'
import {
    Alert,
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    ListGroupItemText,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import PaymentPresenter from '../presenters/PaymentPresenter'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import PaymentModel from '../models/PaymentModel'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SimpleSectionItem from '../common/entityContainers/SimpleSectionItem'
import Refund from '../payments/Refund'
import BottomNavigationAction from '@material-ui/core/BottomNavigationAction'
import BottomNavigation from '@material-ui/core/BottomNavigation'

export default class Payment extends Component {
    constructor (props) {
        super(props)

        this.state = {
            activeTab: '1',
            show_success: false
        }

        this.paymentModel = new PaymentModel(this.props.entity.invoices, this.props.entity, this.props.entity.credits)
        this.triggerAction = this.triggerAction.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    triggerAction (action) {
        this.paymentModel.completeAction(this.props.entity, action).then(response => {
            this.setState({ show_success: true })

            setTimeout(
                function () {
                    this.setState({ show_success: false })
                }
                    .bind(this),
                2000
            )
        })
    }

    render () {
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))
        const paymentableInvoices = this.paymentModel.paymentable_invoices
        const paymentableCredits = this.paymentModel.paymentable_credits

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
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.amount} value_1={this.props.entity.amount}
                            heading_2={translations.applied} value_2={this.props.entity.applied}/>

                        <PaymentPresenter entity={this.props.entity} field="status_field"/>

                        <Row>
                            <ListGroup className="col-12 mt-4 mb-2">
                                {paymentableInvoices && paymentableInvoices.map((line_item, index) => (
                                    <a key={index} href={`/#/invoice?number=${line_item.number}`}>
                                        <ListGroupItem className="list-group-item-dark">
                                            <ListGroupItemHeading>
                                                <i className={`fa ${icons.document} mr-4`}/> {translations.invoice} > {line_item.number}

                                            </ListGroupItemHeading>

                                            <ListGroupItemText>
                                                <FormatMoney amount={line_item.amount}/> - <FormatDate
                                                    date={line_item.date}/>
                                            </ListGroupItemText>
                                        </ListGroupItem>
                                    </a>
                                ))}
                            </ListGroup>

                            <ListGroup className="col-12 mt-4">
                                {paymentableCredits && paymentableCredits.map((line_item, index) => (
                                    <a key={index} href={`/#/credits?number=${line_item.number}`}>
                                        <ListGroupItem className="list-group-item-dark">
                                            <ListGroupItemHeading>
                                                <i className={`fa ${icons.document} mr-4`}/> {translations.credit} > {line_item.number}

                                            </ListGroupItemHeading>

                                            <ListGroupItemText>
                                                <FormatMoney amount={line_item.amount}/> - <FormatDate
                                                    date={line_item.date}/>
                                            </ListGroupItemText>
                                        </ListGroupItem>
                                    </a>
                                ))}
                            </ListGroup>
                        </Row>

                        <Row>
                            <ListGroup className="mt-4 mb-4 col-12">
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i className={`fa ${icons.customer} mr-4`}/>
                                        {customer[0].name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>
                        </Row>

                        <Row>
                            <ul className="col-12">
                                <SimpleSectionItem heading={translations.date}
                                    value={<FormatDate date={this.props.entity.date}/>}/>
                                <SimpleSectionItem heading={translations.transaction_reference}
                                    value={this.props.entity.transaction_reference}/>
                            </ul>
                        </Row>
                    </TabPane>

                    <TabPane tabId="2">
                        <Refund customers={this.props.customer} payment={this.props.entity}
                            modal={false}
                            allInvoices={[]}
                            allCredits={[]}
                            invoices={null}
                            credits={null}
                            paymentables={this.props.entity.paymentables}
                        />
                    </TabPane>
                </TabContent>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                <BottomNavigation showLabels className="bg-dark text-white">
                    <BottomNavigationAction style={{ fontSize: '14px !important' }} className="text-white"
                        onClick={() => {
                            this.toggleTab('2')
                        }} label={translations.refund} value={translations.refund}/>
                    <BottomNavigationAction style={{ fontSize: '14px !important' }} className="text-white"
                        onClick={() => {
                            this.triggerAction('archive')
                        }} label={translations.archive} value={translations.archive}/>
                </BottomNavigation>
            </React.Fragment>
        )
    }
}
