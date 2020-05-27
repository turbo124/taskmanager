import React, { Component } from 'react'
import {
    TabContent,
    TabPane,
    Nav,
    Alert,
    NavItem,
    NavLink,
    Row,
    Card,
    CardText,
    ListGroupItemText,
    ListGroupItemHeading,
    ListGroupItem,
    ListGroup,
    Col
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'
import PaymentModel from '../models/PaymentModel'

export default class Customer extends Component {
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
                        <Card body outline color="primary">
                            <CardText className="text-white">
                                <div className="d-flex">
                                    <div
                                        className="p-2 flex-fill">
                                        <h4 className="text-muted">{translations.paid_to_date}</h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={this.props.entity.paid_to_date}/>}
                                    </div>

                                    <div
                                        className="p-2 flex-fill">
                                        <h4 className="text-muted">{translations.balance}</h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={this.props.entity.balance}/>}
                                    </div>
                                </div>
                            </CardText>
                        </Card>

                        <Row>
                            <ListGroup className="col-12">
                                <a href={`/#/invoice?customer_id=${this.props.entity.id}`}>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        <ListGroupItemHeading><i style={{ fontSize: '20px' }}
                                            className={`fa ${icons.document} mr-4`}/> {translations.invoices}
                                        </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
                                    </ListGroupItem>
                                </a>

                                <a href={`/#/payments?customer_id=${this.props.entity.id}`}>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        <ListGroupItemHeading><i style={{ fontSize: '20px' }}
                                            className={`fa ${icons.credit_card} mr-4`}/>{translations.payments}
                                        </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
                                    </ListGroupItem>
                                </a>

                                <a href={`/#/projects?customer_id=${this.props.entity.id}`}>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        <ListGroupItemHeading><i style={{ fontSize: '20px' }}
                                            className={`fa ${icons.project} mr-4`}/>{translations.projects}
                                        </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
                                    </ListGroupItem>
                                </a>

                                <a href={`/#/tasks?customer_id=${this.props.entity.id}`}>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        <ListGroupItemHeading><i style={{ fontSize: '20px' }}
                                            className={`fa ${icons.task} mr-4`}/>{translations.tasks}
                                        </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
                                    </ListGroupItem>
                                </a>

                                <a href={`/#/expenses?customer_id=${this.props.entity.id}`}>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        <ListGroupItemHeading><i style={{ fontSize: '20px' }}
                                            className={`fa ${icons.expense} mr-4`}/>{translations.expenses}
                                        </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
                                    </ListGroupItem>
                                </a>
                            </ListGroup>
                        </Row>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <ListGroup className="col-12">
                                {this.props.entity.contacts.map((contact, index) => (
                                    <React.Fragment>
                                        <ListGroupItem className="list-group-item-dark">
                                            <Col className="p-0" sm={1}>
                                                <ListGroupItemHeading><i
                                                    className={`fa ${icons.envelope} mr-4`}/></ListGroupItemHeading>
                                            </Col>

                                            <Col sm={11}>
                                                <ListGroupItemHeading>
                                                    {contact.first_name} {contact.last_name}
                                                    <br/>
                                                    {contact.email}
                                                </ListGroupItemHeading>
                                                <ListGroupItemText>
                                                    {translations.email}
                                                </ListGroupItemText>
                                            </Col>
                                        </ListGroupItem>

                                        <ListGroupItem className="list-group-item-dark">
                                            <Col className="p-0" sm={1}>
                                                <ListGroupItemHeading><i
                                                    className={`fa ${icons.phone} mr-4`}/></ListGroupItemHeading>
                                            </Col>

                                            <Col sm={11}>
                                                <ListGroupItemHeading>
                                                    {contact.first_name} {contact.last_name} <br/>
                                                    {contact.phone}
                                                </ListGroupItemHeading>
                                                <ListGroupItemText>
                                                    {translations.phone_number}
                                                </ListGroupItemText>
                                            </Col>
                                        </ListGroupItem>
                                    </React.Fragment>

                                ))}

                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i className={`fa ${icons.link} mr-4`}/></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading> {this.props.entity.website}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.website}
                                        </ListGroupItemText>
                                    </Col>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i
                                            className={`fa ${icons.building} mr-4`}/></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading>{this.props.entity.vat_number}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.vat_number}
                                        </ListGroupItemText>
                                    </Col>

                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i className={`fa ${icons.list} mr-4`}/></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading> {this.props.entity.number}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.number}
                                        </ListGroupItemText>
                                    </Col>
                                </ListGroupItem>

                                {this.props.entity.billing && Object.keys(this.props.entity.billing).length &&
                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i
                                            className={`fa ${icons.map_marker} mr-4`}/></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading>
                                            {this.props.entity.billing.address_1} <br/>
                                            {this.props.entity.billing.address_2} <br/>
                                            {this.props.entity.billing.city} {this.props.entity.billing.zip}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.billing_address}
                                        </ListGroupItemText>
                                    </Col>
                                </ListGroupItem>
                                }

                                {this.props.entity.shipping && Object.keys(this.props.entity.shipping).length &&
                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i
                                            className={`fa ${icons.map_marker}} mr-4`}/></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading>
                                            {this.props.entity.shipping.address_1} <br/>
                                            {this.props.entity.shipping.address_2} <br/>
                                            {this.props.entity.shipping.city} {this.props.entity.shipping.zip}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.shipping_address}
                                        </ListGroupItemText>
                                    </Col>
                                </ListGroupItem>
                                }
                            </ListGroup>
                        </Row>
                    </TabPane>
                </TabContent>

            </React.Fragment>

        )
    }
}
