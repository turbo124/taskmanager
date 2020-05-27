import React, { Component } from 'react'
import FileUploads from '../attachments/FileUploads'
import {
    TabContent,
    TabPane,
    Nav,
    Alert,
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
import { icons, translations } from '../common/_icons'
import InvoiceModel from '../models/InvoiceModel'

export default class Invoice extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.toggleTab = this.toggleTab.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        const invoiceModel = new InvoiceModel(this.props.entity)
        invoiceModel.completeAction(this.props.entity, action).then(response => {
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
                            {translations.documents}
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
                                        <h4 className="text-muted"> {translations.total} </h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={this.props.entity.total}/>}
                                    </div>

                                    <div
                                        className="p-2 flex-fill">
                                        <h4 className="text-muted"> {translations.balance} </h4>
                                        {<FormatMoney className="text-value-lg"
                                            amount={this.props.entity.balance}/>}
                                    </div>
                                </div>
                            </CardText>
                        </Card>

                        <InvoicePresenter entity={this.props.entity} field="status_field"/>

                        {this.props.entity.paymentables.length &&
                        <Row>
                            <ListGroup className="col-12 mt-4">
                                {this.props.entity.paymentables.map((line_item, index) => (
                                    <a href={`/#/payments?number=${line_item.number}`}>
                                        <ListGroupItem className="list-group-item-dark">
                                            <ListGroupItemHeading
                                                className="">
                                                <i className={`fa ${icons.credit_card} mr-4`} />{line_item.number}
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
                                <ListGroupItem className="list-group-item-dark">
                                    <ListGroupItemHeading><i
                                        className={`fa ${icons.customer} mr-4`}/>{this.props.entity.customer_name}
                                    </ListGroupItemHeading>
                                </ListGroupItem>
                            </ListGroup>
                            <ul className="mt-4">
                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading> {translations.date} </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        <FormatDate date={this.props.entity.date}/>
                                    </ListGroupItemText>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading> {translations.due_date} </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        <FormatDate date={this.props.entity.due_date}/>
                                    </ListGroupItemText>
                                </ListGroupItem>

                                {this.props.entity.po_number && this.props.entity.po_number.length &&
                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading> {translations.po_number} </ListGroupItemHeading>
                                    <ListGroupItemText>
                                        {this.props.entity.po_number}
                                    </ListGroupItemText>
                                </ListGroupItem>
                                }

                                <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                    <ListGroupItemHeading> {translations.discount} </ListGroupItemHeading>
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
                                            {line_item.quantity} x {line_item.unit_price} {translations.discount}: {line_item.unit_discount} {translations.tax}: {line_item.unit_tax}
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
                                    {translations.tax}
                                    <span>{this.props.entity.tax_total}</span>
                                </ListGroupItem>
                                <ListGroupItem
                                    className="list-group-item-dark d-flex justify-content-between align-items-center">
                                    {translations.discount}
                                    <span> {this.props.entity.discount_total}</span>
                                </ListGroupItem>
                                <ListGroupItem
                                    className="list-group-item-dark d-flex justify-content-between align-items-center">
                                    {translations.subtotal}
                                    <span> {this.props.entity.sub_total} </span>
                                </ListGroupItem>
                                <ListGroupItem
                                    className="list-group-item-dark d-flex justify-content-between align-items-center">
                                    {translations.total}
                                    <span> {this.props.entity.total} </span>
                                </ListGroupItem>
                            </ListGroup>
                        </Row>
                    </TabPane>
                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card body>
                                    <CardTitle> {translations.documents} </CardTitle>
                                    <CardText>
                                        <FileUploads entity_type="Invoice" entity={this.props.entity}
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
                                    <CardTitle> {translations.pdf} </CardTitle>
                                    <CardText>
                                        <iframe style={{ width: '400px', height: '400px' }} className="embed-responsive-item" id="viewer" src={this.state.obj_url}/>
                                    </CardText>
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
                        {translations.pdf}
                    </NavLink>
                    <NavLink className="flex-fill border border-secondary btn btn-dark"
                        onClick={() => {
                            this.triggerAction('clone_to_invoice')
                        }}>
                        {translations.clone_to_invoice}
                    </NavLink>
                </div>
            </React.Fragment>

        )
    }
}
