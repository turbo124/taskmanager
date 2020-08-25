import React from 'react'
import {
    Card,
    CardBody,
    CardHeader,
    DropdownItem,
    Modal,
    ModalBody,
    Nav,
    NavItem,
    NavLink,
    TabContent,
    TabPane
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import Details from './Details'
import CaseModel from '../models/CaseModel'
import DropdownMenuBuilder from '../common/DropdownMenuBuilder'
import Comments from '../comments/Comments'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'
import FileUploads from '../attachments/FileUploads'
import Emails from '../emails/Emails'

export default class EditCase extends React.Component {
    constructor (props) {
        super(props)

        const data = this.props.case ? this.props.case : null
        this.caseModel = new CaseModel(data, this.props.customers)
        this.initialState = this.caseModel.fields
        this.state = this.initialState

        this.toggle = this.toggle.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value,
            changesMade: true
        })
    }

    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    getFormData () {
        return {
            subject: this.state.subject,
            message: this.state.message,
            customer_id: this.state.customer_id,
            due_date: this.state.due_date,
            priority_id: this.state.priority_id,
            private_notes: this.state.private_notes,
            category_id: this.state.category_id,
            assigned_to: this.state.assigned_to
        }
    }

    handleClick () {
        const formData = this.getFormData()

        this.caseModel.update(formData).then(response => {
            if (!response) {
                this.setState({ errors: this.caseModel.errors, message: this.caseModel.error_message })
                return
            }

            const index = this.props.cases.findIndex(cases => cases.id === this.props.case.id)
            this.props.cases[index] = response
            this.props.action(this.props.cases)
            this.setState({
                editMode: false,
                changesMade: false
            })
            this.toggle()
        })
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    toggle () {
        if (this.state.modal && this.state.changesMade) {
            if (window.confirm('Your changes have not been saved?')) {
                this.setState({ ...this.initialState })
            }

            return
        }

        this.setState({
            modal: !this.state.modal,
            errors: []
        })
    }

    render () {
        const email_editor = this.state.id
            ? <Emails model={this.caseModel} emails={this.state.emails} template="email_template_case" show_editor={true}
                customers={this.props.customers} entity_object={this.state} entity="cases"
                entity_id={this.state.id}/> : null
        const theme = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme'

        return (
            <React.Fragment>
                <DropdownItem onClick={this.toggle}><i className={`fa ${icons.edit}`}/>{translations.edit_case}
                </DropdownItem>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.edit_case}/>

                    <ModalBody className={theme}>
                        <Nav tabs>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '1' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('1')
                                    }}>
                                    {translations.details}
                                </NavLink>
                            </NavItem>
                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '2' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('2')
                                    }}>
                                    {translations.comments}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '3' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('3')
                                    }}>
                                    {translations.documents}
                                </NavLink>
                            </NavItem>

                            <NavItem>
                                <NavLink
                                    className={this.state.activeTab === '4' ? 'active' : ''}
                                    onClick={() => {
                                        this.toggleTab('4')
                                    }}>
                                    {translations.email}
                                </NavLink>
                            </NavItem>
                        </Nav>

                        <TabContent activeTab={this.state.activeTab}>
                            <TabPane tabId="1">
                                <DropdownMenuBuilder invoices={this.props.cases} formData={this.getFormData()}
                                    model={this.caseModel}
                                    action={this.props.action}/>

                                <Details cases={this.props.cases} customers={this.props.customers}
                                    errors={this.state.errors}
                                    hasErrorFor={this.hasErrorFor} case={this.state}
                                    handleInput={this.handleInput} renderErrorFor={this.renderErrorFor}/>
                            </TabPane>

                            <TabPane tabId="2">
                                <Comments entity_type="Cases" entity={this.state}
                                    user_id={this.state.user_id}/>
                            </TabPane>

                            <TabPane tabId="3">
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Cases" entity={this.state}
                                            user_id={this.state.user_id}/>
                                    </CardBody>
                                </Card>
                            </TabPane>

                            <TabPane tabId="4">
                                {email_editor}
                            </TabPane>
                        </TabContent>
                    </ModalBody>

                    <DefaultModalFooter show_success={true} toggle={this.toggle}
                        saveData={this.handleClick.bind(this)}
                        loading={false}/>
                </Modal>
            </React.Fragment>
        )
    }
}
