import React, { Component } from 'react'
import FileUploads from '../attachments/FileUploads'
import {
    TabContent,
    TabPane,
    Nav,
    NavItem,
    NavLink,
    Row,
    Card,
    CardHeader,
    ListGroupItemHeading,
    ListGroupItem,
    ListGroup,
    Col,
    CardBody, Alert
} from 'reactstrap'
import InvoicePresenter from '../presenters/InvoicePresenter'
import FormatDate from '../common/FormatDate'
import axios from 'axios'
import { translations } from '../common/_icons'
import OrderModel from '../models/OrderModel'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SimpleSectionItem from '../common/entityContainers/SimpleSectionItem'
import LineItem from '../common/entityContainers/LineItem'
import TotalsBox from '../common/entityContainers/TotalsBox'
import FormatMoney from '../common/FormatMoney'

export default class Order extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.orderModel = new OrderModel(this.props.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        this.orderModel.completeAction(this.props.entity, action).then(response => {
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
            entity: 'Order',
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
                            {translations.documents} ({this.orderModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.total} value_1={this.props.entity.total}
                            heading_2={translations.balance} value_2={this.props.entity.balance}/>

                        <InvoicePresenter entity={this.props.entity} field="status_field"/>

                        <Row>
                            <ListGroup className="mt-4 col-12">
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i
                                        className="fa fa-user-circle-o mr-2"/>{customer[0].name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>
                        </Row>

                        <Row>
                            <ul className="mt-4 col-12">
                                <SimpleSectionItem heading={translations.date}
                                    value={<FormatDate date={this.props.entity.date}/>}/>

                                {this.props.entity.due_date && this.props.entity.due_date.length &&

                                <SimpleSectionItem heading={translations.due_date}
                                    value={<FormatDate date={this.props.entity.due_date}/>}/>
                                }
                                {this.props.entity.po_number && this.props.entity.po_number.length &&
                                <SimpleSectionItem heading={translations.po_number}
                                    value={this.props.entity.po_number}/>
                                }

                                <SimpleSectionItem heading={translations.discount}
                                    value={<FormatMoney customers={this.props.customers}
                                        amount={this.props.entity.discount_total}/>}/>
                            </ul>
                        </Row>

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
                                        <FileUploads entity_type="Order" entity={this.props.entity}
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

                <div className="navbar d-flex p-0 view-buttons">
                    <NavLink className="flex-fill border border-secondary btn btn-dark"
                        onClick={() => {
                            this.toggleTab('3')
                        }}>
                        {translations.view_pdf}
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
