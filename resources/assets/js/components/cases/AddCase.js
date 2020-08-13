import React from 'react'
import { Modal, ModalBody, Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_translations'
import Details from './Details'
import CaseModel from '../models/CaseModel'
import DefaultModalHeader from '../common/ModalHeader'
import DefaultModalFooter from '../common/ModalFooter'

export default class AddCase extends React.Component {
    constructor (props) {
        super(props)

        this.caseModel = new CaseModel(null, this.props.customers)
        this.initialState = this.caseModel.fields
        this.state = this.initialState

        this.toggle = this.toggle.bind(this)
        this.handleInput = this.handleInput.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    componentDidMount () {
        if (Object.prototype.hasOwnProperty.call(localStorage, 'caseForm')) {
            const storedValues = JSON.parse(localStorage.getItem('caseForm'))
            this.setState({ ...storedValues }, () => console.log('new state', this.state))
        }
    }

    handleInput (e) {
        this.setState({
            [e.target.name]: e.target.value
        }, () => localStorage.setItem('caseForm', JSON.stringify(this.state)))
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

    handleClick () {
        const data = {
            subject: this.state.subject,
            message: this.state.message,
            customer_id: this.state.customer_id,
            due_date: this.state.due_date,
            priority_id: this.state.priority_id,
            private_notes: this.state.private_notes,
            category_id: this.state.category_id,
            assigned_to: this.state.assigned_to
        }

        this.caseModel.save(data).then(response => {
            if (!response) {
                this.setState({ errors: this.caseModel.errors, message: this.caseModel.error_message })
                return
            }
            this.props.cases.push(response)
            this.props.action(this.props.cases)
            this.setState(this.initialState)
            localStorage.removeItem('caseForm')
        })
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    toggle () {
        this.setState({
            modal: !this.state.modal,
            errors: []
        }, () => {
            if (!this.state.modal) {
                this.setState({
                    subject: '',
                    message: '',
                    customer_id: '',
                    due_date: '',
                    private_notes: '',
                    priority_id: '',
                    category_id: ''
                }, () => localStorage.removeItem('caseForm'))
            }
        })
    }

    render () {
        const theme = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'dark-theme' : 'light-theme'

        return (
            <React.Fragment>
                <AddButtons toggle={this.toggle}/>
                <Modal isOpen={this.state.modal} toggle={this.toggle} className={this.props.className}>
                    <DefaultModalHeader toggle={this.toggle} title={translations.add_case}/>

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
                        </Nav>

                        <TabContent activeTab={this.state.activeTab}>
                            <TabPane tabId="1">
                                <Details cases={this.props.cases} customers={this.props.customers}
                                    errors={this.state.errors}
                                    hasErrorFor={this.hasErrorFor} case={this.state}
                                    handleInput={this.handleInput} renderErrorFor={this.renderErrorFor}/>
                            </TabPane>

                            <TabPane tabId="2"/>
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
