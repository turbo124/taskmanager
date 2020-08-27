import React, { Component } from 'react'
import { ListGroup, Row } from 'reactstrap'
import { icons } from '../../common/_icons'
import { translations } from '../../common/_translations'
import SectionItem from '../../common/entityContainers/SectionItem'
import ProjectModel from '../../models/ProjectModel'
import FormatMoney from '../../common/FormatMoney'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import FormatDate from '../../common/FormatDate'
import formatDuration from '../../common/_formatting'
import PlainEntityHeader from '../../common/entityContainers/PlanEntityHeader'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import FieldGrid from '../../common/entityContainers/FieldGrid'

export default class Project extends Component {
    render () {
        const projectModel = new ProjectModel(this.props.entity)
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))
        let user = null

        if (this.props.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.props.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const fields = []
        fields.due_date = <FormatDate date={this.props.entity.due_date}/>
        fields.task_rate = <FormatMoney amount={this.props.entity.task_rate} customers={this.props.customers}/>

        const total = projectModel.taskDurationForProject()

        if (this.props.entity.custom_value1.length) {
            const label1 = this.invoiceModel.getCustomFieldLabel('Project', 'custom_value1')
            fields[label1] = this.invoiceModel.formatCustomValue(
                'Project',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.invoiceModel.getCustomFieldLabel('Project', 'custom_value2')
            fields[label2] = this.invoiceModel.formatCustomValue(
                'Project',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.paymentModel.getCustomFieldLabel('Project', 'custom_value3')
            fields[label3] = this.paymentModel.formatCustomValue(
                'Project',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.paymentModel.getCustomFieldLabel('Project', 'custom_value4')
            fields[label4] = this.paymentModel.formatCustomValue(
                'Project',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

        return (
            <React.Fragment>
                <PlainEntityHeader heading_1={translations.total} value_1={formatDuration(total)}
                    heading_2={translations.budgeted} value_2={this.props.entity.budgeted_hours}/>

                {!!this.props.entity.title.length &&
                <Row>
                    <InfoMessage message={this.props.entity.title}/>
                </Row>
                }

                <Row>
                    <EntityListTile entity={translations.customer} title={customer[0].name}
                        icon={icons.customer}/>
                </Row>

                <Row>
                    <ListGroup className="col-12 mt-2 mb-2">
                        {!!this.props.entity.tasks && this.props.entity.tasks.map((task, index) => (
                            <EntityListTile key={index} entity={translations.task} title={task.title}
                                icon={icons.task}/>
                        ))}
                    </ListGroup>
                </Row>

                {!!this.props.entity.private_notes.length &&
                <Row>
                    <InfoMessage message={this.props.entity.private_notes}/>
                </Row>
                }

                <FieldGrid fields={fields}/>

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
