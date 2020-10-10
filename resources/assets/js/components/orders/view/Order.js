import React, { Component } from 'react'
import FileUploads from '../../documents/FileUploads'
import { Alert, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import FormatDate from '../../common/FormatDate'
import { translations } from '../../utils/_translations'
import OrderModel from '../../models/OrderModel'
import FormatMoney from '../../common/FormatMoney'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'
import Audit from '../../common/Audit'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'
import ViewContacts from '../../common/entityContainers/ViewContacts'
import Overview from './Overview'

export default class Order extends Component {
    constructor (props) {
        super(props)
        this.state = {
            entity: this.props.entity,
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.orderModel = new OrderModel(this.state.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
        this.refresh = this.refresh.bind(this)
    }

    refresh (entity) {
        this.orderModel = new OrderModel(entity)
        this.setState({ entity: entity })
    }

    triggerAction (action) {
        this.orderModel.completeAction(this.state.entity, action).then(response => {
            this.setState({ show_success: true }, () => {
                if (action !== 'cloneOrderToInvoice') {
                    this.props.updateState(response, this.refresh)
                }
            })

            setTimeout(
                function () {
                    this.setState({ show_success: false })
                }
                    .bind(this),
                2000
            )
        })
    }

    loadPdf () {
        this.orderModel.loadPdf().then(url => {
            console.log('url', url)
            this.setState({ obj_url: url }, () => URL.revokeObjectURL(url))
        })
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab }, () => {
                if (this.state.activeTab === '5') {
                    this.loadPdf()
                }
            })
        }
    }

    render () {
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.state.entity.customer_id))

        let user = null

        if (this.state.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.state.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const fields = []

        if (this.state.entity.custom_value1.length) {
            const label1 = this.orderModel.getCustomFieldLabel('Order', 'custom_value1')
            fields[label1] = this.orderModel.formatCustomValue(
                'Order',
                'custom_value1',
                this.state.entity.custom_value1
            )
        }

        if (this.state.entity.custom_value2.length) {
            const label2 = this.orderModel.getCustomFieldLabel('Order', 'custom_value2')
            fields[label2] = this.orderModel.formatCustomValue(
                'Order',
                'custom_value2',
                this.state.entity.custom_value2
            )
        }

        if (this.state.entity.custom_value3.length) {
            const label3 = this.orderModel.getCustomFieldLabel('Order', 'custom_value3')
            fields[label3] = this.orderModel.formatCustomValue(
                'Order',
                'custom_value3',
                this.state.entity.custom_value3
            )
        }

        if (this.state.entity.custom_value4.length) {
            const label4 = this.orderModel.getCustomFieldLabel('Order', 'custom_value4')
            fields[label4] = this.orderModel.formatCustomValue(
                'Order',
                'custom_value4',
                this.state.entity.custom_value4
            )
        }

        fields.date = <FormatDate date={this.state.entity.date}/>

        if (this.state.entity.po_number && this.state.entity.po_number.length) {
            fields.po_number = this.state.entity.po_number
        }

        if (this.state.entity.due_date && this.state.entity.due_date.length) {
            fields.due_date = <FormatDate date={this.state.entity.due_date}/>
        }

        if (this.state.entity.discount_total && this.state.entity.discount_total.toString().length) {
            fields.discount = <FormatMoney customers={this.props.customers}
                amount={this.state.entity.discount_total}/>
        }

        let buttonAction = ''
        let buttonText = ''

        if (!this.orderModel.isSent && this.orderModel.isEditable) {
            buttonAction = 'markSent'
            buttonText = translations.mark_sent
        } else if (!this.orderModel.isApproved && !this.orderModel.isCompleted && this.orderModel.isEditable) {
            buttonAction = 'dispatch'
            buttonText = translations.dispatch
        } else if (this.orderModel.isBackorder && this.orderModel.isEditable) {
            buttonAction = 'fulfill'
            buttonText = translations.fulfill
        } else {
            buttonAction = 'cloneOrderToInvoice'
            buttonText = translations.clone_order_to_invoice
        }

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
                            {translations.contacts}
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '3' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('3')
                            }}
                        >
                            {translations.documents} ({this.orderModel.fileCount})
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '4' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('4')
                            }}
                        >
                            {translations.history}
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview entity={this.state.entity} customers={this.props.customers} customer={customer}
                            user={user} fields={fields}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <ViewContacts entity={this.orderModel} customers={this.props.customers}/>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="3">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader> {translations.documents} </CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Order" entity={this.state.entity}
                                            user_id={this.state.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="4">
                        <Row>
                            <Col>
                                <Audit entity="Order" audits={this.state.entity.audits}/>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="5">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader> {translations.pdf} </CardHeader>
                                    <CardBody>
                                        <iframe style={{ width: '400px', height: '400px' }}
                                            className="embed-responsive-item" id="viewer" src={this.state.obj_url}/>
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

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('5')}
                    button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction(buttonAction)}
                    button2={{ label: buttonText }}/>

            </React.Fragment>

        )
    }
}
