import React, { Component } from 'react'
import {
    TabContent,
    Alert,
    TabPane,
    Nav,
    NavItem,
    NavLink,
    Card,
    CardText,
    Row,
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    ListGroupItemText, Col, CardTitle
} from 'reactstrap'
import ExpenseModel from '../models/ExpenseModel'
import ExpensePresenter from '../presenters/ExpensePresenter'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import { translations } from '../common/_icons'
import FileUploads from '../attachments/FileUploads'
import CreditModel from '../models/CreditModel'

export default class Expense extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.toggleTab = this.toggleTab.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        const expenseModel = new ExpenseModel(this.props.entity)
        expenseModel.completeAction(this.props.entity, action).then(response => {
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
        const expenseModel = new ExpenseModel(this.props.entity)
        const convertedAmount = expenseModel.convertedAmount

        return (
            <React.Fragment>
                <Nav tabs>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '1' ? 'active' : ''}
                            onClick={() => { this.toggleTab('1') }}
                        >
                            {translations.details}
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => { this.toggleTab('2') }}
                        >
                            {translations.documents}
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Card body outline color="success">
                            <CardText className="text-white">
                                <div className="d-flex">
                                    <div
                                        className="p-2 flex-fill">
                                        <h4 className="text-muted">{translations.amount}</h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={this.props.entity.amount}/>}
                                    </div>

                                    <div
                                        className="p-2 flex-fill">
                                        <h4 className="text-muted">{translations.converted}</h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={convertedAmount}/>}
                                    </div>
                                </div>
                            </CardText>
                        </Card>

                        <ExpensePresenter entity={this.props.entity} field="status_field" />

                        <Row>
                            <ListGroup className="mt-4">
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i className="fa fa-user-circle-o mr-2"/>
                                        {this.props.entity.customer_name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>

                            <ul className="col-12">
                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>{translations.date}</ListGroupItemHeading>
                                    <ListGroupItemText>
                                        <FormatDate date={this.props.entity.expense_date}/>
                                    </ListGroupItemText>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>
                                        {translations.transaction_reference}
                                    </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {this.props.entity.transaction_reference}
                                    </ListGroupItemText>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>
                                        {translations.exchange_rate}
                                    </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {this.props.entity.exchange_rate}
                                    </ListGroupItemText>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>{translations.payment_date}</ListGroupItemHeading>
                                    <ListGroupItemText>
                                        <FormatDate date={this.props.entity.payment_date}/>
                                    </ListGroupItemText>
                                </ListGroupItem>
                            </ul>
                        </Row>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card body>
                                    <CardTitle>{translations.documents}</CardTitle>
                                    <CardText>
                                        <FileUploads entity_type="Expense" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardText>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>
                </TabContent>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                <div className="navbar d-flex p-0 view-buttons">
                    <NavLink className="flex-fill border border-secondary btn btn-dark"
                        onClick={() => {
                            this.triggerAction('3')
                        }}>
                        {translations.pdf}
                    </NavLink>
                    <NavLink className="flex-fill border border-secondary btn btn-dark"
                        onClick={() => {
                            this.triggerAction('4')
                        }}>
                        Link 4
                    </NavLink>
                </div>
            </React.Fragment>

        )
    }
}
