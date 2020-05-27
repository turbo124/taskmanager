import React, { Component } from 'react'
import {
    Card,
    CardText, ListGroup, ListGroupItem, ListGroupItemHeading, Row
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { icons, translations } from '../common/_icons'

export default class Project extends Component {
    render () {
        return (
            <React.Fragment>
                <Card body outline color="success">
                    <CardText className="text-white">
                        <div className="d-flex">
                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted"> {translations.total} </h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={0}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted"> {translations.budgeted} </h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.budgeted_hours}/>}
                            </div>
                        </div>
                    </CardText>
                </Card>

                <Row>
                    <ListGroup className="col-12">
                        <a href={`/#/tasks?project_id=${this.props.entity.id}`}>
                            <ListGroupItem
                                className="list-group-item-dark d-flex justify-content-between align-items-center">
                                <ListGroupItemHeading><i style={{ fontSize: '24px' }}
                                    className={`fa ${icons.task} mr-4`}/>{translations.tasks}
                                </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
                            </ListGroupItem>
                        </a>
                    </ListGroup>
                </Row>
            </React.Fragment>

        )
    }
}
