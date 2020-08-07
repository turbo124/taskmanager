import React, { Component } from 'react'
import { ListGroup, Row } from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import SectionItem from '../common/entityContainers/SectionItem'

export default class Group extends Component {
    render () {
        return (
            <React.Fragment>
                <Row>
                    <ListGroup className="col-12">
                        <SectionItem link={`/#/customers?group_settings_id=${this.props.entity.id}`}
                            icon={icons.customer} title={translations.customers}/>

                    </ListGroup>
                </Row>

            </React.Fragment>

        )
    }
}
