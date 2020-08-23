import React, { Component } from 'react'
import {
    Alert,
    Card,
    CardBody,
    CardHeader,
    Col,
    ListGroup,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import InfoItem from '../common/entityContainers/InfoItem'
import LeadModel from '../models/LeadModel'
import axios from 'axios'
import FileUploads from '../attachments/FileUploads'
import BottomNavigationButtons from '../common/BottomNavigationButtons'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'

export default class Lead extends Component {
    constructor (props) {
        super(props)
        this.state = {
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.leadModel = new LeadModel(this.props.entity)
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
            entity: 'Lead',
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
        const address = <React.Fragment>
            {this.props.entity.address_1} <br/>
            {this.props.entity.address_2} <br/>
            {this.props.entity.city} {this.props.entity.zip}
        </React.Fragment>

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
                            {translations.documents} ({this.leadModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.valued_at}
                            value_1={<FormatMoney amount={this.props.entity.valued_at}/>}/>

                        <Row>
                            <ListGroup className="col-12">
                                <InfoItem icon={icons.user}
                                    value={`${this.props.entity.first_name} ${this.props.entity.last_name}`}
                                    title={translations.full_name}/>

                                <InfoItem icon={icons.envelope} value={this.props.entity.email}
                                    title={translations.email}/>

                                <InfoItem icon={icons.phone} value={this.props.entity.phone}
                                    title={translations.phone_number}/>

                                <InfoItem icon={icons.link} value={this.props.entity.website}
                                    title={translations.website}/>

                                <InfoItem icon={icons.building} value={this.props.entity.vat_number}
                                    title={translations.vat_number}/>

                                <InfoItem icon={icons.list} value={this.props.entity.number}
                                    title={translations.number}/>

                                <InfoItem icon={icons.map_marker} value={address}
                                    title={translations.billing_address}/>

                            </ListGroup>
                        </Row>
                    </TabPane>
                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Lead" entity={this.props.entity}
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
