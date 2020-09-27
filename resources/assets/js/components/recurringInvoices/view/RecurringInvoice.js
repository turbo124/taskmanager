import React, { Component } from 'react'
import FileUploads from '../../documents/FileUploads'
import { Alert, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import FormatMoney from '../../common/FormatMoney'
import FormatDate from '../../common/FormatDate'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import RecurringInvoiceModel from '../../models/RecurringInvoiceModel'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import Audit from '../../common/Audit'
import ViewContacts from '../../common/entityContainers/ViewContacts'
import Overview from './Overview'

export default class RecurringInvoice extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.invoiceModel = new RecurringInvoiceModel(this.props.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        this.invoiceModel.completeAction(this.props.entity, action).then(response => {
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

    loadPdf () {
        this.invoiceModel.loadPdf().then(url => {
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
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))
        let user = null

        if (this.props.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.props.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const fields = []

        if (this.props.entity.custom_value1.length) {
            const label1 = this.invoiceModel.getCustomFieldLabel('RecurringInvoice', 'custom_value1')
            fields[label1] = this.invoiceModel.formatCustomValue(
                'RecurringInvoice',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.invoiceModel.getCustomFieldLabel('RecurringInvoice', 'custom_value2')
            fields[label2] = this.invoiceModel.formatCustomValue(
                'RecurringInvoice',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.invoiceModel.getCustomFieldLabel('RecurringInvoice', 'custom_value3')
            fields[label3] = this.invoiceModel.formatCustomValue(
                'RecurringInvoice',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.invoiceModel.getCustomFieldLabel('RecurringInvoice', 'custom_value4')
            fields[label4] = this.invoiceModel.formatCustomValue(
                'RecurringInvoice',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

        fields.date = <FormatDate date={this.props.entity.date}/>
        fields.due_date = <FormatDate date={this.props.entity.due_date}/>

        if (this.props.entity.po_number && this.props.entity.po_number.length) {
            fields.po_number = this.props.entity.po_number
        }

        if (this.props.entity.discount_total && this.props.entity.discount_total.toString().length) {
            fields.discount = <FormatMoney customers={this.props.customers}
                amount={this.props.entity.discount_total}/>
        }

        if (this.props.entity.frequency && this.props.entity.frequency.toString().length) {
            fields.frequency = this.props.entity.frequency
        }

        if (this.props.entity.start_date && this.props.entity.start_date.length) {
            fields.start_date = <FormatDate date={this.props.entity.start_date}/>
        }

        if (this.props.entity.expiry_date && this.props.entity.expiry_date.length) {
            fields.expiry_date = <FormatDate date={this.props.entity.expiry_date}/>
        }

        if (this.props.entity.date_to_send && this.props.entity.date_to_send.length) {
            fields.date_to_send = <FormatDate date={this.props.entity.date_to_send}/>
        }

        if (this.props.entity.cycles_remaining && this.props.entity.cycles_remaining.length) {
            fields.cycles_remaining = parseInt(this.props.entity.cycles_remaining) === 9000 ? translations.frequency_endless : this.props.entity.cycles_remaining
        }

        fields.grace_period = this.props.entity.grace_period > 0 ? this.props.entity.grace_period : translations.payment_term
        fields.auto_billing_enabled = this.props.entity.auto_billing_enabled === true ? translations.yes : translations.no

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
                            {translations.documents} ({this.invoiceModel.fileCount})
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
                        <Overview invoices={this.invoiceModel.invoices} entity={this.props.entity} user={user}
                            customer={customer}
                            customers={this.props.customers} fields={fields}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <ViewContacts entity={this.invoiceModel} customers={this.props.customers}/>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="3">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader> {translations.documents} </CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Invoice" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="4">
                        <Row>
                            <Col>
                                <Audit entity="Quote" audits={this.props.entity.audits}/>
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
                    button2_click={(e) => this.triggerAction('clone_to_invoice')}
                    button2={{ label: translations.clone_to_invoice }}/>
            </React.Fragment>

        )
    }
}
