import React, { Component } from 'react'
import { ListGroup, Row } from 'reactstrap'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import SectionItem from '../../common/entityContainers/SectionItem'
import ProjectModel from '../../models/ProjectModel'
import FormatMoney from '../../common/FormatMoney'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import FormatDate from '../../common/FormatDate'
import formatDuration from '../../utils/_formatting'
import PlainEntityHeader from '../../common/entityContainers/PlanEntityHeader'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import Overview from './Overview'
import FileUploads from '../../documents/FileUploads'
import AddTask from '../../tasks/AddTask'

export default class Project extends Component {
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
                            {translations.documents} ({this.projectModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview user={user} customer={customer}
                            entity={this.state.entity} fields={fields} total={total}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Project" entity={this.state.entity}
                                            user_id={this.state.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>

                     <TabPane tabId="3">
                          <AddTask modal={false} />
                    </TabPane>
                </TabContent>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                  <BottomNavigationButtons button1_click={(e) => this.triggerAction('archive')}
                    button1={{ label: translations.archive }}
                    button2_click={(e) => this.toggleTab('3')
                    button2={{ label: translations.new_task }}/>
            </React.Fragment>
        )
    }
}
