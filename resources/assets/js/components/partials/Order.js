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
    CardText,
    ListGroupItemText,
    ListGroupItemHeading,
    ListGroupItem,
    ListGroup,
    Col,
    CardTitle
} from 'reactstrap'
import InvoicePresenter from '../presenters/InvoicePresenter'
import FormatMoney from '../common/FormatMoney'
import FormatDate from '../common/FormatDate'
import axios from 'axios'

export default class Order extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null
        }

        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
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
                            Details
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}
                        >
                            Documents
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Card body outline color="primary">
                            <CardText className="text-white">
                                <div className="d-flex">
                                    <div
                                        className="p-2 flex-fill">
                                        <h4 className="text-muted">Total</h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={this.props.entity.total}/>}
                                    </div>

                                    <div
                                        className="p-2 flex-fill">
                                        <h4 className="text-muted">Balance</h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={this.props.entity.balance}/>}
                                    </div>
                                </div>
                            </CardText>
                        </Card>

                        <InvoicePresenter entity={this.props.entity} field="status_field"/>

                        <Row>
                            <ListGroup className="mt-4">
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i
                                        className="fa fa-user-circle-o mr-2"/>{this.props.entity.customer_name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>
                            <ul className="mt-4">
                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>Invoice Date</ListGroupItemHeading>
                                    <ListGroupItemText>
                                        <FormatDate date={this.props.entity.date}/>
                                    </ListGroupItemText>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>Due Date</ListGroupItemHeading>
                                    <ListGroupItemText>
                                        <FormatDate date={this.props.entity.due_date}/>
                                    </ListGroupItemText>
                                </ListGroupItem>

                                {this.props.entity.po_number && this.props.entity.po_number.length &&
                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>PO Number</ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {this.props.entity.po_number}
                                    </ListGroupItemText>
                                </ListGroupItem>
                                }

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading>Discount</ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {this.props.entity.discount_total}
                                    </ListGroupItemText>
                                </ListGroupItem>
                            </ul>
                        </Row>

                        <Row>
                            <ListGroup className="col-12 mt-4">
                                {this.props.entity.line_items.map((line_item, index) => (
                                    <ListGroupItem className="list-group-item-dark">
                                        <ListGroupItemHeading
                                            className="d-flex justify-content-between align-items-center">
                                            {line_item.product_id}
                                            <span>{line_item.sub_total}</span>
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {line_item.quantity} x {line_item.unit_price} Discount: {line_item.unit_discount} Tax: {line_item.unit_tax}
                                            <br/>
                                            {line_item.description}
                                        </ListGroupItemText>
                                    </ListGroupItem>
                                ))}
                            </ListGroup>
                        </Row>

                        <Row className="justify-content-end">
                            <ListGroup className="col-6 mt-4">
                                <ListGroupItem
                                    className="list-group-item-dark d-flex justify-content-between align-items-center">
                                    Tax
                                    <span>{this.props.entity.tax_total}</span>
                                </ListGroupItem>
                                <ListGroupItem
                                    className="list-group-item-dark d-flex justify-content-between align-items-center">
                                    Discount
                                    <span> {this.props.entity.discount_total}</span>
                                </ListGroupItem>
                                <ListGroupItem
                                    className="list-group-item-dark d-flex justify-content-between align-items-center">
                                    Subtotal
                                    <span> {this.props.entity.sub_total} </span>
                                </ListGroupItem>
                                <ListGroupItem
                                    className="list-group-item-dark d-flex justify-content-between align-items-center">
                                    Total
                                    <span> {this.props.entity.total} </span>
                                </ListGroupItem>
                            </ListGroup>
                        </Row>
                    </TabPane>
                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card body>
                                    <CardTitle>Documents</CardTitle>
                                    <CardText>
                                        <FileUploads entity_type="Order" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardText>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>
                    <TabPane tabId="3">
                        <Row>
                            <Col>
                                <Card body>
                                    <CardTitle>PDF</CardTitle>
                                    <CardText>
                                        <iframe style={{ width: '400px', height: '400px' }} className="embed-responsive-item" id="viewer" src={this.state.obj_url}/>
                                    </CardText>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>
                </TabContent>

                <div className="navbar d-flex p-0">
                    <NavLink className="flex-fill border border-secondary"
                        onClick={() => {
                            this.toggleTab('3')
                        }}>
                        PDF
                    </NavLink>
                    <NavLink className="flex-fill border border-secondary"
                        onClick={() => {
                            this.toggleTab('4')
                        }}>
                        Link 4
                    </NavLink>
                </div>
            </React.Fragment>

        )
    }
}
