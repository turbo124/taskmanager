import React, { Component } from 'react'
import { ListGroup, Row } from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import InfoItem from '../common/entityContainers/InfoItem'

export default class Token extends Component {
    render () {
        return (
            <React.Fragment>
                <Row>
                    <ListGroup className="col-12">
                        <InfoItem icon={icons.building} value={this.props.entity.name}
                            title={translations.name}/>

                        <InfoItem icon={icons.link} value={`${this.props.entity.token.substring(0, 10)}xxxxxxxxxx`}
                            title={translations.token}/>
                    </ListGroup>
                </Row>
            </React.Fragment>

        )
    }
}
