import React, { Component } from 'react'
import { Alert, ListGroup, Row } from 'reactstrap'
import { translations } from '../common/_translations'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import TaskModel from '../models/TaskModel'
import DealModel from '../models/DealModel'
import FieldGrid from '../common/entityContainers/FieldGrid'

export default class Deal extends Component {
    constructor (props) {
        super(props)
        this.state = {
            statuses: [],
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.taskModel = new TaskModel(this.props.entity)
        this.dealModel = new DealModel(this.props.entity)
    }

    render () {
        const fields = []

        if (this.props.entity.status_name.length) {
            fields.status = this.props.entity.status_name
        }

        if (this.props.entity.due_date.length) {
            fields.due_date = this.props.entity.due_date
        }

        if (this.props.entity.custom_value1.length) {
            const label1 = this.taskModel.getCustomFieldLabel('Task', 'custom_value1')
            fields[label1] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.taskModel.getCustomFieldLabel('Task', 'custom_value2')
            fields[label2] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.taskModel.getCustomFieldLabel('Task', 'custom_value3')
            fields[label3] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.taskModel.getCustomFieldLabel('Task', 'custom_value4')
            fields[label4] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

       return (
            <React.Fragment>
                 <Nav tabs className="nav-justified disable-scrollbars">
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
                            {translations.documents} ({this.caseModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                <ViewEntityHeader heading_1={translations.duration} value_1={this.props.entity.duration}
                    heading_2={translations.amount}
                    value_2={this.taskModel.calculateAmount(this.props.entity.task_rate, this.props.entity.duration)}/>

                {this.props.entity.title.length &&
                <Alert color="dark col-12 mt-2">
                    {this.props.entity.title}
                </Alert>
                }

                {this.props.entity.private_notes.length &&
                <Alert color="dark col-12 mt-2">
                    {this.props.entity.private_notes}
                </Alert>
                }

                <FieldGrid fields={fields}/>

                </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Case" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
                                    </CardBody>
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
                    <NavLink className={`flex-fill border border-secondary btn ${buttonClass}`}
                        onClick={() => {
                            this.triggerAction('3')
                        }}>
                        {translations.pdf}
                    </NavLink>
                    <NavLink className={`flex-fill border border-secondary btn ${buttonClass}`}
                        onClick={() => {
                            this.triggerAction('4')
                        }}>
                        Link 4
                    </NavLink>
                </div>
              
            </React.Fragment>
        )
    }
}
