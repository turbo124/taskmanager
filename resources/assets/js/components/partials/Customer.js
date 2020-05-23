import React, { Component } from 'react'
import {
    Row,
    Card,
    CardText,
    ListGroupItemText,
    ListGroupItemHeading,
    ListGroupItem,
    ListGroup,
    Col
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'

export default class Customer extends Component {
    render () {
        return (
            <React.Fragment>
                <Card body outline color="primary">
                    <CardText className="text-white">
                        <div className="d-flex">
                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted">{translations.paid_to_date}</h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.paid_to_date}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted">{translations.balance}</h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.balance}/>}
                            </div>
                        </div>
                    </CardText>
                </Card>

                <Row>
                    <ListGroup className="col-12">
                        {this.props.entity.contacts.map((contact, index) => (
                            <React.Fragment>
                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i className={`fa ${icons.envelope} mr-4`} /></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading>
                                            {contact.first_name} {contact.last_name}
                                            <br/>
                                            {contact.email}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.email}
                                        </ListGroupItemText>
                                    </Col>
                                </ListGroupItem>

                                <ListGroupItem className="list-group-item-dark">
                                    <Col className="p-0" sm={1}>
                                        <ListGroupItemHeading><i className={`fa ${icons.phone} mr-4`} /></ListGroupItemHeading>
                                    </Col>

                                    <Col sm={11}>
                                        <ListGroupItemHeading>
                                            {contact.first_name} {contact.last_name} <br/>
                                            {contact.phone}
                                        </ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {translations.phone_number}
                                        </ListGroupItemText>
                                    </Col>
                                </ListGroupItem>
                            </React.Fragment>

                        ))}

                        <ListGroupItem className="list-group-item-dark">
                            <Col className="p-0" sm={1}>
                                <ListGroupItemHeading><i className={`fa ${icons.link} mr-4`} /></ListGroupItemHeading>
                            </Col>

                            <Col sm={11}>
                                <ListGroupItemHeading> {this.props.entity.website}
                                </ListGroupItemHeading>
                                <ListGroupItemText>
                                    {translations.website}
                                </ListGroupItemText>
                            </Col>
                        </ListGroupItem>

                        <ListGroupItem className="list-group-item-dark">
                            <Col className="p-0" sm={1}>
                                <ListGroupItemHeading><i className={`fa ${icons.building} mr-4`} /></ListGroupItemHeading>
                            </Col>

                            <Col sm={11}>
                                <ListGroupItemHeading>{this.props.entity.vat_number}
                                </ListGroupItemHeading>
                                <ListGroupItemText>
                                    {translations.vat_number}
                                </ListGroupItemText>
                            </Col>

                        </ListGroupItem>

                        <ListGroupItem className="list-group-item-dark">
                            <Col className="p-0" sm={1}>
                                <ListGroupItemHeading><i className={`fa ${icons.list} mr-4`} /></ListGroupItemHeading>
                            </Col>

                            <Col sm={11}>
                                <ListGroupItemHeading> {this.props.entity.number}
                                </ListGroupItemHeading>
                                <ListGroupItemText>
                                    {translations.number}
                                </ListGroupItemText>
                            </Col>
                        </ListGroupItem>

                        {this.props.entity.billing && Object.keys(this.props.entity.billing).length &&
                        <ListGroupItem className="list-group-item-dark">
                            <Col className="p-0" sm={1}>
                                <ListGroupItemHeading><i className={`fa ${icons.map_marker} mr-4`} /></ListGroupItemHeading>
                            </Col>

                            <Col sm={11}>
                                <ListGroupItemHeading>
                                    {this.props.entity.billing.address_1} <br/>
                                    {this.props.entity.billing.address_2} <br/>
                                    {this.props.entity.billing.city} {this.props.entity.billing.zip}
                                </ListGroupItemHeading>
                                <ListGroupItemText>
                                    {translations.billing_address}
                                </ListGroupItemText>
                            </Col>
                        </ListGroupItem>
                        }

                        {this.props.entity.shipping && Object.keys(this.props.entity.shipping).length &&
                        <ListGroupItem className="list-group-item-dark">
                            <Col className="p-0" sm={1}>
                                <ListGroupItemHeading><i className={`fa ${icons.map_marker}} mr-4`} /></ListGroupItemHeading>
                            </Col>

                            <Col sm={11}>
                                <ListGroupItemHeading>
                                    {this.props.entity.shipping.address_1} <br/>
                                    {this.props.entity.shipping.address_2} <br/>
                                    {this.props.entity.shipping.city} {this.props.entity.shipping.zip}
                                </ListGroupItemHeading>
                                <ListGroupItemText>
                                    {translations.shipping_address}
                                </ListGroupItemText>
                            </Col>
                        </ListGroupItem>
                        }
                    </ListGroup>
                </Row>
            </React.Fragment>

        )
    }
}
