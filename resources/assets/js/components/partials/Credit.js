import React, { Component } from 'react'
import FileUploads from '../attachments/FileUploads'
import {
    Alert,
    Card,
    CardBody,
    CardHeader,
    Col,
    ListGroup,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import CreditPresenter from '../presenters/CreditPresenter'
import FormatDate from '../common/FormatDate'
import axios from 'axios'
import { translations } from '../common/_translations'
import CreditModel from '../models/CreditModel'
import LineItem from '../common/entityContainers/LineItem'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import TotalsBox from '../common/entityContainers/TotalsBox'
import FormatMoney from '../common/FormatMoney'
import BottomNavigationButtons from '../common/BottomNavigationButtons'
import Audit from './Audit'
import FieldGrid from '../common/entityContainers/FieldGrid'
import InfoMessage from '../common/entityContainers/InfoMessage'
import EntityListTile from '../common/entityContainers/EntityListTile'
import { icons } from '../common/_icons'

export default class Credit extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.creditModel = new CreditModel(this.props.entity)

        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        this.creditModel.completeAction(this.props.entity, action).then(response => {
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
        axios.post('/api/preview', {
            entity: 'Credit',
            entity_id: this.props.entity.id
        })
            .then((response) => {
                console.log('respons', response.data.data)
                var base64str = response.data.data

                // decode base64 string, remove space for IE compatibility
                var binary = atob(base64str.replace(/\s/g, ''))
                var len = binary.length
                var buffer = new ArrayBuffer(len)
                var view = new Uint8Array(buffer)
                for (var i = 0; i < len; i++) {
                    view[i] = binary.charCodeAt(i)
                }

                // create the blob object with content-type "application/pdf"
                var blob = new Blob([view], { type: 'application/pdf' })
                var url = URL.createObjectURL(blob)

                /* const file = new Blob (
                 [ response.data.data ],
                 { type: 'application/pdf' } ) */
                // const fileURL = URL.createObjectURL ( file )

                this.setState({ obj_url: url }, () => URL.revokeObjectURL(url))
            })
            .catch((error) => {
                alert(error)
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
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))
        const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

        let user = null

        if (this.props.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.props.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const fields = []

        if (this.props.entity.custom_value1.length) {
            const label1 = this.creditModel.getCustomFieldLabel('Credit', 'custom_value1')
            fields[label1] = this.creditModel.formatCustomValue(
                'Credit',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.creditModel.getCustomFieldLabel('Credit', 'custom_value2')
            fields[label2] = this.creditModel.formatCustomValue(
                'Credit',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.creditModel.getCustomFieldLabel('Credit', 'custom_value3')
            fields[label3] = this.creditModel.formatCustomValue(
                'Credit',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.creditModel.getCustomFieldLabel('Credit', 'custom_value4')
            fields[label4] = this.creditModel.formatCustomValue(
                'Credit',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

        fields.date = <FormatDate date={this.props.entity.date}/>

        if (this.props.entity.po_number && this.props.entity.po_number.length) {
            fields.po_number = this.props.entity.po_number
        }

        if (this.props.entity.discount_total && this.props.entity.discount_total.toString().length) {
            fields.discount = <FormatMoney customers={this.props.customers}
                amount={this.props.entity.discount_total}/>
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
                            className={this.state.activeTab === '1' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}
                        >
                            {translations.contacts}
                        </NavLink>
                    </NavItem>

                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('3')
                            }}
                        >
                            {translations.documents} ({this.creditModel.fileCount})
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
                        <ViewEntityHeader heading_1={translations.total} value_1={this.props.entity.total}
                            heading_2={translations.credit_remaining}
                            value_2={this.props.entity.balance}/>

                        <CreditPresenter entity={this.props.entity} field="status_field"/>

                        {!!this.props.entity.private_notes.length &&
                        <Row>
                            <InfoMessage message={this.props.entity.private_notes}/>
                        </Row>
                        }

                        <Row>
                            <EntityListTile entity={translations.customer} title={customer[0].name}
                                icon={icons.customer}/>
                        </Row>

                        {!!user &&
                        <Row>
                            {user}
                        </Row>
                        }

                        <FieldGrid fields={fields}/>

                        <Row>
                            <ListGroup className="col-12 mt-4">
                                {this.props.entity.line_items.map((line_item, index) => (
                                    <LineItem customers={this.props.customers} key={index} line_item={line_item}/>
                                ))}
                            </ListGroup>
                        </Row>

                        <Row className="justify-content-end">
                            <TotalsBox customers={this.props.customers} entity={this.props.entity}/>
                        </Row>
                    </TabPane>

                    <TabPane tabId="2">

                    </TabPane>

                    <TabPane tabId="3">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Credit" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="4">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.pdf}</CardHeader>
                                    <CardBody>
                                        <iframe style={{ width: '400px', height: '400px' }}
                                            className="embed-responsive-item" id="viewer" src={this.state.obj_url}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="5">
                        <Row>
                            <Col>
                                <Audit entity="Credit" audits={this.props.entity.audits}/>
                            </Col>
                        </Row>
                    </TabPane>
                </TabContent>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('3')}
                    button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction('clone_to_invoice')}
                    button2={{ label: translations.clone_to_invoice }}/>

            </React.Fragment>

        )
    }
}
