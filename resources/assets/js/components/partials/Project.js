import React, { Component } from 'react'
import {
    ListGroup, Row
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import SectionItem from '../common/entityContainers/SectionItem'

export default class Project extends Component {
    render () {
        return (
            <React.Fragment>
                <ViewEntityHeader heading_1={translations.total} value_1={0}
                    heading_2={translations.budgeted} value_2={this.props.entity.budgeted_hours}/>

                <Row>
                    <ListGroup className="col-12">
                        <SectionItem link={`/#/tasks?project_id=${this.props.entity.id}`}
                            icon={icons.task} title={translations.tasks}/>
                    </ListGroup>
                </Row>
            </React.Fragment>

        )
    }
}
