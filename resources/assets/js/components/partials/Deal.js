import React, { Component } from 'react'
import { Alert, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import { translations } from '../common/_translations'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import DealModel from '../models/DealModel'
import FieldGrid from '../common/entityContainers/FieldGrid'
import axios from 'axios'
import FileUploads from '../attachments/FileUploads'
import FormatMoney from '../common/FormatMoney'
import BottomNavigationButtons from '../common/BottomNavigationButtons'

export default class Deal extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.dealModel = new DealModel(this.props.entity)
        this.toggleTab = this.toggleTab.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
        this.loadPdf = this.loadPdf.bind(this)
    }

    triggerAction (action) {
        this.dealModel.completeAction(this.props.entity, action).then(response => {
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

    loadPdf () {
        axios.post('/api/preview', {
            entity: 'Deal',
            entity_id: this.props.entity.id
        })
            .then((response) => {
                console.log('respons', response.data.data)
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
        const fields = []

        if (this.props.entity.status_name && this.props.entity.status_name.length) {
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
                            {translations.documents} ({this.dealModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.valued_at}
                            value_1={<FormatMoney amount={this.props.entity.valued_at}/>}/>

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
                                        <FileUploads entity_type="Deal" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
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

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('3')}
                    button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction('clone_to_invoice')}
                    button2={{ label: translations.clone_to_invoice }}/>

            </React.Fragment>
        )
    }
}
