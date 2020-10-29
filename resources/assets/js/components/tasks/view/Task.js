import React, { Component } from 'react'
import { Alert, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import { translations } from '../../utils/_translations'
import FileUploads from '../../documents/FileUploads'
import TaskModel from '../../models/TaskModel'
import TaskTimeItem from '../../common/entityContainers/TaskTimeItem'
import axios from 'axios'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'
import Overview from './Overview'
import formatDuration from '../../utils/_formatting'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'
import FormatDate from '../../common/FormatDate'

export default class Task extends Component {
    constructor (props) {
        super(props)
        this.state = {
            entity: this.props.entity,
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.taskModel = new TaskModel(this.state.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
        this.refresh = this.refresh.bind(this)

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = user_account[0].account.settings
    }

    refresh (entity) {
        this.taskModel = new TaskModel(entity)
        this.setState({ entity: entity })
    }

    triggerAction (action) {
        this.taskModel.completeAction(this.state.entity, action).then(response => {
            this.setState({ show_success: true }, () => {
                this.props.updateState(response, this.refresh)
            })

            setTimeout(
                function () {
                    this.setState({ show_success: false })
                }
                    .bind(this),
                2000
            )
        })
    }

    loadPdf () {
        axios.post('/api/preview', {
            entity: 'Task',
            entity_id: this.state.entity.id
        })
            .then((response) => {
                var base64str = response.data.data

                // decode base64 string, remove space for IE compatibility
                var binary = atob(base64str.replace(/\s/g, ''))
                var len = binary.length
                var buffer = new ArrayBuffer(len)
                var view = new Uint8Array(buffer)
                for (var i = 0; i < len; i++) {
                    view[i] = binary.charCodeAt(i)
                }

                // create the blob object with content-type "application/pdf"
                var blob = new Blob([view], { type: 'application/pdf' })
                var url = URL.createObjectURL(blob)

                /* const file = new Blob (
                 [ response.data.data ],
                 { type: 'application/pdf' } ) */
                // const fileURL = URL.createObjectURL ( file )

                this.setState({ obj_url: url }, () => URL.revokeObjectURL(url))
            })
            .catch((error) => {
                alert(error)
            })
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab }, () => {
                if (this.state.activeTab === '3') {
                    this.loadPdf()
                }
            })
        }
    }

    render () {
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.state.entity.customer_id))
        let user
        let project
        let invoice = null

        if (this.state.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.state.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        if (this.state.entity.project_id && this.state.entity.project) {
            project = <EntityListTile entity={translations.project}
                title={`${this.state.entity.project.number} ${this.state.entity.project.name}`}
                icon={icons.user}/>
        }

        if (this.state.entity.invoice_id && this.state.entity.invoice) {
            invoice = <EntityListTile entity={translations.invoice}
                title={`${this.state.entity.invoice.number} ${this.state.entity.invoice.total}`}
                icon={icons.user}/>
        }

        const fields = []

        if (this.state.entity.status_name.length) {
            fields.status = this.state.entity.status_name
        }

        if (this.state.entity.description.length) {
            fields.description = this.state.entity.description
        }

        if (this.state.entity.start_date.length) {
            fields.start_date = this.state.entity.start_date
        }

        if (this.state.entity.due_date.length) {
            fields.due_date = this.state.entity.due_date
        }

        if (this.state.entity.custom_value1.length) {
            const label1 = this.taskModel.getCustomFieldLabel('Task', 'custom_value1')
            fields[label1] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value1',
                this.state.entity.custom_value1
            )
        }

        if (this.state.entity.custom_value2.length) {
            const label2 = this.taskModel.getCustomFieldLabel('Task', 'custom_value2')
            fields[label2] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value2',
                this.state.entity.custom_value2
            )
        }

        if (this.state.entity.custom_value3.length) {
            const label3 = this.taskModel.getCustomFieldLabel('Task', 'custom_value3')
            fields[label3] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value3',
                this.state.entity.custom_value3
            )
        }

        if (this.state.entity.custom_value4.length) {
            const label4 = this.taskModel.getCustomFieldLabel('Task', 'custom_value4')
            fields[label4] = this.taskModel.formatCustomValue(
                'Task',
                'custom_value4',
                this.state.entity.custom_value4
            )
        }

        const recurring = []

        if (this.state.entity.is_recurring === true) {
            if (this.state.entity.recurring_start_date.length) {
                recurring.start_date = <FormatDate date={this.state.entity.recurring_start_date}/>
            }

            if (this.state.entity.recurring_end_date.length) {
                recurring.end_date = <FormatDate date={this.state.entity.recurring_end_date}/>
            }

            if (this.state.entity.recurring_due_date.length) {
                recurring.due_date = <FormatDate date={this.state.entity.recurring_due_date}/>
            }

            if (this.state.entity.recurring_frequency.toString().length) {
                recurring.frequency = this.state.entity.recurring_frequency.toString()
            }
        }

        const task_times = this.state.entity.timers && this.state.entity.timers.length ? this.state.entity.timers.map((timer, index) => {
            return <TaskTimeItem key={index} taskTime={timer}/>
        }) : null

        const task_rate = this.state.entity.calculated_task_rate && this.state.entity.calculated_task_rate > 0 ? this.state.entity.calculated_task_rate : this.settings.task_rate
        const button1_action = !this.state.entity.invoice_id ? (e) => location.href = '/#/invoice?entity_type=task&entity_id=' + this.state.entity.id : (e) => this.toggleTab('6')
        const button1_label = !this.state.entity.invoice_id ? translations.new_invoice : translations.view_pdf

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
                            {translations.documents} ({this.taskModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview invoice={invoice} project={project} user={user} customer={customer}
                            recurring={recurring}
                            totalDuration={formatDuration(this.taskModel.duration)}
                            calculatedAmount={this.taskModel.calculateAmount(task_rate)}
                            entity={this.state.entity} fields={fields} task_times={task_times}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Task" entity={this.state.entity}
                                            user_id={this.state.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                    <TabPane tabId="3">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader> {translations.pdf} </CardHeader>
                                    <CardBody>
                                        <iframe style={{ width: '400px', height: '400px' }}
                                            className="embed-responsive-item" id="viewer"
                                            src={this.state.obj_url}/>
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

                <BottomNavigationButtons button1_click={button1_action}
                    button1={{ label: button1_label }}
                    button2_click={(e) => this.triggerAction((this.taskModel.isRunning) ? ('stop_timer') : ((!this.state.entity.timers || !this.state.entity.timers.length) ? ('start_timer') : ('resume_timer')))}
                    button2={{ label: (this.taskModel.isRunning) ? (translations.stop) : ((!this.state.entity.timers || !this.state.entity.timers.length) ? (translations.start) : (translations.resume)) }}/>
            </React.Fragment>
        )
    }
}
