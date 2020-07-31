import React, { Component } from 'react'
import {
    Card,
    CardHeader,
    CardBody,
    Nav,
    NavItem,
    NavLink,
    TabContent, TabPane, Form, Button, Spinner
} from 'reactstrap'
import axios from 'axios'
import { ToastContainer, toast } from 'react-toastify'
import EmailFields from './EmailFields'
import EmailPreview from './EmailPreview'
import { translations } from '../common/_translations'

class TemplateSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            showSpinner: false,
            showPreview: false,
            id: localStorage.getItem('account_id'),
            loaded: false,
            preview: null,
            activeTab: '1',
            template_type: 'email_template_invoice',
            template_name: 'Invoice',
            company_logo: null,
            settings: {
                email_template_payment: '',
                email_subject_payment: '',
                email_template_quote: '',
                email_subject_quote: '',
                email_template_credit: '',
                email_subject_credit: '',
                email_template_reminder1: '',
                email_subject_reminder1: '',
                email_template_reminder2: '',
                email_subject_reminder2: '',
                email_template_reminder3: '',
                email_subject_reminder3: '',
                email_subject_invoice: '',
                email_template_invoice: '',
                email_subject_lead: '',
                email_template_lead: '',
                email_subject_order_received: '',
                email_subject_order_sent: '',
                email_template_order_received: '',
                email_template_order_sent: ''
            }
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.toggle = this.toggle.bind(this)
        this.getAccount = this.getAccount.bind(this)
        this.getPreview = this.getPreview.bind(this)
    }

    componentDidMount () {
        this.getAccount()
    }

    getAccount () {
        axios.get(`api/accounts/${this.state.id}`)
            .then((r) => {
                this.setState({
                    loaded: true,
                    settings: r.data.settings
                })
            })
            .catch((e) => {
                alert('There was an issue updating the settings')
            })
    }

    handleChange (event) {
        this.setState({ [event.target.name]: event.target.value })

        if (event.target.name === 'template_type') {
            const name = event.target[event.target.selectedIndex].getAttribute('data-name')
            this.setState({ template_name: name })
        }
    }

    handleSettingsChange (event) {
        const name = event.target.name
        const value = event.target.type === 'checkbox' ? event.target.checked : event.target.value

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }))
    }

    handleFileChange (e) {
        this.setState({
            [e.target.name]: e.target.files[0]
        })
    }

    toggle (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab }, () => {
                if (tab === '2') {
                    this.getPreview()
                }
            })
        }
    }

    getPreview () {
        this.setState({ showSpinner: true, showPreview: false })
        const subjectKey = this.state.template_type.replace('template', 'subject')
        const bodyKey = this.state.template_type

        const subject = !this.state.settings[subjectKey] ? '' : this.state.settings[subjectKey]
        const body = !this.state.settings[bodyKey] ? '' : this.state.settings[bodyKey]

        axios.post('api/template', {
            subject: subject,
            body: body,
            template: bodyKey,
            entity_id: this.props.entity_id,
            entity: this.props.entity
        })
            .then((r) => {
                this.setState({
                    preview: r.data,
                    showSpinner: false,
                    showPreview: true
                })
            })
            .catch((e) => {
                toast.error('There was an issue updating the settings')
            })
    }

    handleSubmit (e) {
        const formData = new FormData()
        formData.append('settings', JSON.stringify(this.state.settings))
        formData.append('company_logo', this.state.company_logo)
        formData.append('_method', 'PUT')

        axios.post('/api/accounts/1', formData, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then((response) => {
                toast.success('Settings updated successfully')
            })
            .catch((error) => {
                console.error(error)
                // this.setState({
                //     errors: error.response.data.errors
                // })
            })
    }

    render () {
        const fields = <EmailFields return_form={true} settings={this.state.settings} template_type={this.state.template_type}
            handleSettingsChange={this.handleSettingsChange}
            handleChange={this.handleChange}/>

        console.log('fields', fields)

        const preview = this.state.showPreview && this.state.preview && Object.keys(this.state.preview).length && this.state.settings[this.state.template_type] && this.state.settings[this.state.template_type].length
            ? <EmailPreview preview={this.state.preview} entity={this.props.entity} entity_id={this.props.entity_id}
                template_type={this.state.template_type}/> : null
        const spinner = this.state.showSpinner === true ? <Spinner style={{ width: '3rem', height: '3rem' }}/> : null

        return (
            <React.Fragment>
                <ToastContainer/>

                <div className="topbar">
                    <Card className="m-0">
                        <CardBody className="p-0">
                            <div className="d-flex justify-content-between align-items-center">
                                <h4 className="pl-3 pt-2">{translations.template_settings}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>
                            <Nav tabs className="nav-justified setting-tabs disable-scrollbars">
                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '1' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('1')
                                        }}>
                                        {translations.edit}
                                    </NavLink>
                                </NavItem>
                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '2' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('2')
                                        }}>
                                        {translations.preview}
                                    </NavLink>
                                </NavItem>
                            </Nav>
                        </CardBody>
                    </Card>
                </div>

                <TabContent className="fixed-margin-mobile bg-transparent" activeTab={this.state.activeTab}>
                    <TabPane className="px-0" tabId="1">
                        <Card className="border-0">
                            <CardBody>
                                <Form>
                                    {fields}
                                </Form>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="px-0" tabId="2">
                        <Card className="border-0">
                            <CardBody>
                                {spinner}
                                {preview}
                            </CardBody>
                        </Card>
                    </TabPane>
                </TabContent>
            </React.Fragment>
        )
    }
}

export default TemplateSettings
