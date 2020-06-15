import React, { Component } from 'react'
import {
    Row,
    ListGroup
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import InfoItem from '../common/entityContainers/InfoItem'
import FormatDate from '../common/FormatDate'
import FormatMoney from '../common/FormatMoney'

export default class User extends Component {
    render () {
        return (
            <React.Fragment>
                {/* <ViewEntityHeader heading_1={translations.amount} value_1={this.props.entity.reward} */}
                {/*    heading_2={translations.amount_type} value_2={this.props.entity.amount_type}/> */}

                <Row>
                    <ListGroup className="col-12">
                        <InfoItem icon={icons.user} value={`${this.props.entity.first_name} ${this.props.entity.last_name}`}
                            title={translations.name}/>
                        <InfoItem icon={icons.email} value={this.props.entity.email}
                            title={translations.email}/>
                        <InfoItem icon={icons.phone} value={this.props.entity.phone_number}
                            title={translations.phone_number}/>
                    </ListGroup>
                </Row>

            </React.Fragment>

        )
    }
}
