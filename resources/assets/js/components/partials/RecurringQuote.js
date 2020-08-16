import React, { Component } from 'react'
import FileUploads from '../attachments/FileUploads'
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
import RecurringQuotePresenter from '../presenters/RecurringQuotePresenter'
import FormatDate from '../common/FormatDate'
import axios from 'axios'
import { translations } from '../common/_translations'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import LineItem from '../common/entityContainers/LineItem'
import TotalsBox from '../common/entityContainers/TotalsBox'
import FormatMoney from '../common/FormatMoney'
import RecurringQuoteModel from '../models/RecurringQuoteModel'
import FieldGrid from '../common/entityContainers/FieldGrid'
import BottomNavigationButtons from '../common/BottomNavigationButtons'

export default class RecurringQuote extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.quoteModel = new RecurringQuoteModel(this.props.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        this.quoteModel.completeAction(this.props.entity, action).then(response => {
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
            entity: 'Quote',
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

        const fields = []

        if (this.props.entity.custom_value1.length) {
            const label1 = this.quoteModel.getCustomFieldLabel('RecurringQuote', 'custom_value1')
            fields[label1] = this.quoteModel.formatCustomValue(
                'RecurringQuote',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.quoteModel.getCustomFieldLabel('RecurringQuote', 'custom_value2')
            fields[label2] = this.quoteModel.formatCustomValue(
                'RecurringQuote',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.quoteModel.getCustomFieldLabel('RecurringQuote', 'custom_value3')
            fields[label3] = this.quoteModel.formatCustomValue(
                'RecurringQuote',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.quoteModel.getCustomFieldLabel('RecurringQuote', 'custom_value4')
            fields[label4] = this.quoteModel.formatCustomValue(
                'RecurringQuote',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

        fields.date = <FormatDate date={this.props.entity.date}/>

        if (this.props.entity.po_number && this.props.entity.po_number.length) {
            fields.po_number = this.props.entity.po_number
        }

        if (this.props.entity.due_date && this.props.entity.due_date.length) {
            fields.expiry_date = <FormatDate date={this.props.entity.due_date}/>
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
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}
                        >
                            {translations.documents} ({this.quoteModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.total} value_1={this.props.entity.total}
                            heading_2={translations.balance} value_2={this.props.entity.balance}/>

                        <RecurringQuotePresenter entity={this.props.entity} field="status_field"/>

                        <Row>
                            <ListGroup className="mt-4 col-12">
                                <ListGroupItem className={listClass}>
                                    <ListGroupItemHeading><i
                                        className="fa fa-user-circle-o mr-2"/>{customer[0].name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>
                        </Row>

                        {this.props.entity.private_notes.length &&
                        <Alert color="dark col-12 mt-2">
                            {this.props.entity.private_notes}
                        </Alert>
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
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader> {translations.documents} </CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Quote" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>
                    <TabPane tabId="3">
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

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('3')}
                    button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction('clone_to_invoice')}
                    button2={{ label: translations.clone_to_invoice }}/>

            </React.Fragment>

        )
    }
}
