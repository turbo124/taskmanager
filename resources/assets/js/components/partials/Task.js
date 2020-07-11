import React, { Component } from 'react'
import {
    Row,
    Card,
    CardText,
    ListGroup
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'
import InfoItem from '../common/entityContainers/InfoItem'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import TaskModel from '../models/TaskModel'
import FormatDate from '../common/FormatDate'

export default class Task extends Component {
    render () {
        const taskModel = new TaskModel(this.props.entity)
        return (
            <React.Fragment>
                <ViewEntityHeader heading_1={translations.duration} value_1={this.props.entity.duration}
                    heading_2={translations.amount} value_2={taskModel.calculateAmount(this.props.entity.task_rate, this.props.entity.duration)}/>

                <Row>
                    <ListGroup className="col-12">
                        <InfoItem icon={icons.building} value={this.props.entity.title}
                            title={translations.name}/>

                        {this.props.entity.start_date &&
                        <InfoItem icon={icons.calendar} value={<FormatDate date={this.props.entity.start_date}/>}
                            title={translations.start_date}/>
                        }

                        <InfoItem icon={icons.calendar} value={<FormatDate date={this.props.entity.due_date}/>}
                            title={translations.due_date}/>

                    </ListGroup>
                </Row>
            </React.Fragment>
        )
    }
}
