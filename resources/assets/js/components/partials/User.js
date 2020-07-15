import React, { Component } from 'react'
import {
    Row,
    ListGroup
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import InfoItem from '../common/entityContainers/InfoItem'
import PlainEntityHeader from '../common/entityContainers/PlanEntityHeader'

export default class User extends Component {
    render () {
        return (
            <React.Fragment>
                <PlainEntityHeader heading_1={translations.email} value_1={this.props.entity.email}
                    heading_2={translations.phone_number} value_2={this.props.entity.phone}/>

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
