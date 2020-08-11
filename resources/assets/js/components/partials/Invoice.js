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
    ListGroupItemText,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import InvoicePresenter from '../presenters/InvoicePresenter'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import axios from 'axios'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import InvoiceModel from '../models/InvoiceModel'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SimpleSectionItem from '../common/entityContainers/SimpleSectionItem'
import LineItem from '../common/entityContainers/LineItem'
import TotalsBox from '../common/entityContainers/TotalsBox'
import BottomNavigation from '@material-ui/core/BottomNavigation'
import BottomNavigationAction from '@material-ui/core/BottomNavigationAction'
import BottomNavigationButtons from '../common/BottomNavigationButtons'

export default class Invoice extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.invoiceModel = new InvoiceModel(this.props.entity)
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
        axios.post('/api/preview', {
            entity: 'Invoice',
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
        const listClass = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'list-group-item-dark' : ''

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
                            {translations.documents} ({this.invoiceModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.total} value_1={this.props.entity.total}
                            heading_2={translations.balance} value_2={this.props.entity.balance}/>

                        <InvoicePresenter entity={this.props.entity} field="status_field"/>

                        {this.props.entity.paymentables.length &&
                        <Row>
                            <ListGroup className="col-12 mt-4">
                                {this.props.entity.paymentables.map((line_item, index) => (
                                    <a key={index} href={`/#/payments?number=${line_item.number}`}>
                                        <ListGroupItem className={listClass}>
                                            <ListGroupItemHeading
                                                className="">
                                                <i className={`fa ${icons.credit_card} mr-4`}/>{line_item.number}
                                            </ListGroupItemHeading>

                                            <ListGroupItemText>
                                                <FormatMoney amount={line_item.amount}/> - {line_item.date}
                                            </ListGroupItemText>
                                        </ListGroupItem>
                                    </a>
                                ))}
                            </ListGroup>
                        </Row>
                        }

                        <Row>
                            <ListGroup className="mt-4 col-12">
                                <ListGroupItem className={listClass}>
                                    <ListGroupItemHeading><i
                                        className={`fa ${icons.customer} mr-4`}/>{customer[0].name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>
                        </Row>

                        <Row>
                            <ul className="mt-4 col-12">
                                <SimpleSectionItem heading={translations.date}
                                    value={<FormatDate date={this.props.entity.date}/>}/>
                                <SimpleSectionItem heading={translations.due_date}
                                    value={<FormatDate date={this.props.entity.due_date}/>}/>

                                {this.props.entity.po_number && this.props.entity.po_number.length &&
                                <SimpleSectionItem heading={translations.po_number}
                                    value={this.props.entity.po_number}/>
                                }

                                <SimpleSectionItem heading={translations.discount}
                                    value={<FormatMoney customers={this.props.customers}
                                        amount={this.props.entity.discount_total}/>}/>
                            </ul>
                        </Row>

                        {this.props.entity.private_notes.length &&
                        <Alert color="dark col-12">
                            {this.props.entity.private_notes}
                        </Alert>
                        }

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
                                        <FileUploads entity_type="Invoice" entity={this.props.entity}
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

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('3')} button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction('clone_to_invoice')} button2={{ label: translations.clone_to_invoice }}/>
            </React.Fragment>

        )
    }
}
