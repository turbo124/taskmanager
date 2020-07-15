import React, { Component } from 'react'
import {
    Row,
    Card,
    CardText,
    ListGroup
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import InfoItem from '../common/entityContainers/InfoItem'

export default class Lead extends Component {
    render () {
        const address = <React.Fragment>
            {this.props.entity.address_1} <br />
            {this.props.entity.address_2} <br />
            {this.props.entity.city} {this.props.entity.zip}
        </React.Fragment>

        return (
            <React.Fragment>
                <Card body outline color="primary">
                    <CardText className="text-white">
                        <div className="d-flex">
                            {/* <div */}
                            {/*    className="p-2 flex-fill"> */}
                            {/*    <h4 className="text-muted">{translations.paid_to_date}</h4> */}
                            {/*    {<FormatMoney className="text-value-lg" */}
                            {/*        amount={this.props.entity.paid_to_date}/>} */}
                            {/* </div> */}

                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted">{translations.valued_at}</h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.valued_at}/>}
                            </div>
                        </div>
                    </CardText>
                </Card>

                <Row>
                    <ListGroup className="col-12">
                        <InfoItem icon={icons.user}
                            value={`${this.props.entity.first_name} ${this.props.entity.last_name}`}
                            title={translations.full_name}/>

                        <InfoItem icon={icons.envelope} value={this.props.entity.email}
                            title={translations.email}/>

                        <InfoItem icon={icons.phone} value={this.props.entity.phone}
                            title={translations.phone_number}/>

                        <InfoItem icon={icons.link} value={this.props.entity.website}
                            title={translations.website}/>

                        <InfoItem icon={icons.building} value={this.props.entity.vat_number}
                            title={translations.vat_number}/>

                        <InfoItem icon={icons.list} value={this.props.entity.number}
                            title={translations.number}/>

                        <InfoItem icon={icons.map_marker} value={address}
                            title={translations.billing_address}/>

                    </ListGroup>
                </Row>
            </React.Fragment>
        )
    }
}
