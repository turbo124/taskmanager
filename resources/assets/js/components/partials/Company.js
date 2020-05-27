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
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    ListGroupItemText,
    Col
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'
import PaymentModel from '../models/PaymentModel'

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
                                <a href={`/#/expenses?company_id=${this.props.entity.id}`}>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        <ListGroupItemHeading><i style={{ fontSize: '24px' }}
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
                                                    {contact.first_name} {contact.last_name} <br/>
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
                                        <ListGroupItemHeading><i
                                            className={`fa ${icons.link} mr-4`}/></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading>
                                            {this.props.entity.website}</ListGroupItemHeading>
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
                                        <ListGroupItemHeading>
                                            {this.props.entity.vat_number}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.vat_number}
                                        </ListGroupItemText>
                                    </Col>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading> <i
                                        className={`fa ${icons.list} mr-4`}/> {this.props.entity.number}
                                    </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {translations.number}
                                    </ListGroupItemText>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i
                                            className={`fa ${icons.map_marker}} mr-4`}/></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading>
                                            {this.props.entity.address_1} <br/>
                                            {this.props.entity.address_2} <br/>
                                            {this.props.entity.town} <br/>
                                            {this.props.entity.city} {this.props.entity.postcode}

                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.billing_address}
                                        </ListGroupItemText>
                                    </Col>

                                </ListGroupItem>
                            </ListGroup>
                        </Row>
                    </TabPane>
                </TabContent>

            </React.Fragment>
        )
    }
}
