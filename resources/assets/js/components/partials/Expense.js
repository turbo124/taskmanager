import React, { Component } from 'react'
import {
    Alert,
    Card,
    CardBody,
    CardHeader,
    Col,
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import ExpenseModel from '../models/ExpenseModel'
import ExpensePresenter from '../presenters/ExpensePresenter'
import FormatDate from '../common/FormatDate'
import { translations } from '../common/_translations'
import FileUploads from '../attachments/FileUploads'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SimpleSectionItem from '../common/entityContainers/SimpleSectionItem'
import BottomNavigationAction from '@material-ui/core/BottomNavigationAction'
import BottomNavigation from '@material-ui/core/BottomNavigation'
import BottomNavigationButtons from '../common/BottomNavigationButtons'

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
        const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

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
                            {translations.details}
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}
                        >
                            {translations.documents} ({this.expenseModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.amount} value_1={this.props.entity.amount}
                            heading_2={translations.converted} value_2={convertedAmount}/>

                        <ExpensePresenter entity={this.props.entity} field="status_field"/>

                        <Row>
                            <ListGroup className="mt-4 col-12">
                                <ListGroupItem className={listClass}>
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

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('3')} button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction('clone_to_invoice')} button2={{ label: translations.clone_to_invoice }}/>

            </React.Fragment>

        )
    }
}
