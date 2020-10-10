import React, { Component } from 'react'
import FileUploads from '../../documents/FileUploads'
import { Alert, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import FormatMoney from '../../common/FormatMoney'
import FormatDate from '../../common/FormatDate'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import InvoiceModel from '../../models/InvoiceModel'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'
import Audit from '../../common/Audit'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import ViewContacts from '../../common/entityContainers/ViewContacts'
import AddPayment from '../../payments/edit/AddPayment'
import Overview from './Overview'

export default class Invoice extends Component {
    constructor (props) {
        super(props)
        this.state = {
            entity: this.props.entity,
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.invoiceModel = new InvoiceModel(this.state.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
        this.refresh = this.refresh.bind(this)
    }

    refresh (entity) {
        this.invoiceModel = new InvoiceModel(entity)
        this.setState({ entity: entity })
    }

    triggerAction (action, is_add = false) {
        this.invoiceModel.completeAction(this.state.entity, action).then(response => {
            this.setState({ show_success: true }, () => {
                this.props.updateState(response, this.refresh, is_add)
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
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.state.entity.customer_id))

        let user = null

        if (this.state.entity.assigned_to) {
            console.log('users', JSON.parse(localStorage.getItem('users')))
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.state.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const fields = []

        if (this.state.entity.custom_value1.length) {
            const label1 = this.invoiceModel.getCustomFieldLabel('Invoice', 'custom_value1')
            fields[label1] = this.invoiceModel.formatCustomValue(
                'Invoice',
                'custom_value1',
                this.state.entity.custom_value1
            )
        }

        if (this.state.entity.custom_value2.length) {
            const label2 = this.invoiceModel.getCustomFieldLabel('Invoice', 'custom_value2')
            fields[label2] = this.invoiceModel.formatCustomValue(
                'Invoice',
                'custom_value2',
                this.state.entity.custom_value2
            )
        }

        if (this.state.entity.custom_value3.length) {
            const label3 = this.invoiceModel.getCustomFieldLabel('Invoice', 'custom_value3')
            fields[label3] = this.invoiceModel.formatCustomValue(
                'Invoice',
                'custom_value3',
                this.state.entity.custom_value3
            )
        }

        if (this.state.entity.custom_value4.length) {
            const label4 = this.invoiceModel.getCustomFieldLabel('Invoice', 'custom_value4')
            fields[label4] = this.invoiceModel.formatCustomValue(
                'Invoice',
                'custom_value4',
                this.state.entity.custom_value4
            )
        }

        fields.date = <FormatDate date={this.state.entity.date}/>
        fields.due_date = <FormatDate date={this.state.entity.due_date}/>

        if (this.state.entity.po_number && this.state.entity.po_number.length) {
            fields.po_number = this.state.entity.po_number
        }

        if (this.state.entity.discount_total && this.state.entity.discount_total.toString().length) {
            fields.discount = <FormatMoney customers={this.props.customers}
                amount={this.state.entity.discount_total}/>
        }

        const button_2_action = this.invoiceModel.isPaid ? (e) => this.triggerAction('clone_to_invoice', true) : (e) => this.toggleTab('6')
        const button_2_text = this.invoiceModel.isPaid ? translations.clone_invoice : translations.add_payment

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
                        <Overview entity={this.state.entity} customers={this.props.customers} user={user}
                            customer={customer} fields={fields}/>
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
                                        <FileUploads entity_type="Invoice" entity={this.state.entity}
                                            user_id={this.state.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="4">
                        <Row>
                            <Col>
                                <Audit entity="Invoice" audits={this.state.entity.audits}/>
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
                                            className="embed-responsive-item" id="viewer"
                                            src={this.state.obj_url}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="6">
                        <AddPayment invoice_id={this.state.entity.id} showForm={true}/>
                    </TabPane>
                </TabContent>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('5')}
                    button1={{ label: translations.view_pdf }}
                    button2_click={button_2_action}
                    button2={{ label: button_2_text }}/>
            </React.Fragment>

        )
    }
}
