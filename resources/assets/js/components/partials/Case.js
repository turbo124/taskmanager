import React, { Component } from 'react'
import { Alert, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import FormatDate from '../common/FormatDate'
import { translations } from '../common/_translations'
import FileUploads from '../attachments/FileUploads'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import CaseModel from '../models/CaseModel'
import CasePresenter from '../presenters/CasePresenter'
import FieldGrid from '../common/entityContainers/FieldGrid'
import InfoMessage from '../common/entityContainers/InfoMessage'
import EntityListTile from '../common/entityContainers/EntityListTile'
import { icons } from '../common/_icons'

export default class Case extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.caseModel = new CaseModel(this.props.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    triggerAction (action) {
        this.caseModel.completeAction(this.props.entity, action).then(response => {
            this.setState({ show_success: true })

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
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))
        const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
        const buttonClass = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'btn-dark' : ''

        let user = null

        if (this.props.entity.assigned_to) {
            console.log('users', JSON.parse(localStorage.getItem('users')))
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.props.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const fields = []

        if (this.props.entity.custom_value1.length) {
            const label1 = this.caseModel.getCustomFieldLabel('Case', 'custom_value1')
            fields[label1] = this.caseModel.formatCustomValue(
                'Case',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.caseModel.getCustomFieldLabel('Case', 'custom_value2')
            fields[label2] = this.caseModel.formatCustomValue(
                'Case',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.caseModel.getCustomFieldLabel('Case', 'custom_value3')
            fields[label3] = this.caseModel.formatCustomValue(
                'Case',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.caseModel.getCustomFieldLabel('Case', 'custom_value4')
            fields[label4] = this.caseModel.formatCustomValue(
                'Case',
                'custom_value4',
                this.props.entity.custom_value4
            )
        }

        fields.date = <FormatDate date={this.props.entity.created_at}/>
        fields.due_date = <FormatDate date={this.props.entity.due_date}/>

        if (this.props.entity.subject && this.props.entity.subject.length) {
            fields.subject = this.props.entity.subject
        }

        if (this.props.entity.priority && this.props.entity.priority.toString().length) {
            fields.priority = this.props.priority
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
                        <ViewEntityHeader heading_1={translations.amount} value_1={this.props.entity.amount}
                            heading_2={translations.converted} value_2={0}/>

                        <CasePresenter entity={this.props.entity} field="status_field"/>

                        {!!this.props.entity.private_notes.length &&
                        <Row>
                            <InfoMessage message={this.props.entity.private_notes}/>
                        </Row>
                        }

                        <Row>
                            <EntityListTile entity={translations.customer} title={customer[0].name}
                                icon={icons.customer}/>
                        </Row>

                        {!!user &&
                        <Row>
                            {user}
                        </Row>
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
