import React, { Component } from 'react'
import {
    TabContent,
    Alert,
    TabPane,
    Nav,
    NavItem,
    NavLink,
    Card,
    CardBody,
    Row,
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    Col, CardHeader
} from 'reactstrap'
import ExpenseModel from '../models/ExpenseModel'
import ExpensePresenter from '../presenters/ExpensePresenter'
import FormatDate from '../common/FormatDate'
import { translations } from '../common/_translations'
import FileUploads from '../attachments/FileUploads'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SimpleSectionItem from '../common/entityContainers/SimpleSectionItem'

export default class Expense extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.expenseModel = new ExpenseModel(this.props.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        this.expenseModel.completeAction(this.props.entity, action).then(response => {
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
        const convertedAmount = this.expenseModel.convertedAmount
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))

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
                            {translations.documents} ({this.expenseModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.amount} value_1={this.props.entity.amount}
                            heading_2={translations.converted} value_2={convertedAmount}/>

                        <ExpensePresenter entity={this.props.entity} field="status_field" />

                        <Row>
                            <ListGroup className="mt-4 col-12">
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i className="fa fa-user-circle-o mr-2"/>
                                        {customer[0].name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>

                            <ul className="col-12 mt-4">
                                <SimpleSectionItem heading={translations.date}
                                    value={<FormatDate date={this.props.entity.date}/>}/>

                                <SimpleSectionItem heading={translations.transaction_reference}
                                    value={this.props.entity.transaction_reference}/>

                                <SimpleSectionItem heading={translations.exchange_rate}
                                    value={this.props.entity.exchange_rate}/>

                                <SimpleSectionItem heading={translations.payment_date}
                                    value={this.props.entity.payment_date}/>
                            </ul>
                        </Row>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Expense" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardBody>
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
