import React, { Component } from 'react'
import {
    Button,
    FormGroup,
    Form,
    Input,
    Label
} from 'reactstrap'
import axios from 'axios'
import SuccessMessage from '../common/SucessMessage'
import ErrorMessage from '../common/ErrorMessage'
import { translations } from '../common/_translations'

export default class EmailEditorForm extends Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            check: false,
            mark_sent: false,
            errors: [],
            showSuccessMessage: false,
            showErrorMessage: false,
            subject: this.props.subject,
            design: '',
            body: this.props.body
        }

        this.editor = null
        this.isEditorLoaded = false
        this.isComponentMounted = false

        this.sendMessage = this.sendMessage.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleCheck = this.handleCheck.bind(this)
        this.exportHtml = this.exportHtml.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
    }

    componentDidMount () {
        this.isComponentMounted = true
        this.loadTemplate()
    }

    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback d-inline-block'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    onLoad () {
        this.isEditorLoaded = true
        this.loadTemplate()
    }

    loadTemplate () {
        if (!this.isEditorLoaded || !this.isComponentMounted) return
        this.editor.loadDesign('<p>test mike</p>')
    }

    handleCheck () {
        this.setState({ mark_sent: !this.state.checked })
    }

    sendMessage () {
        this.setState({ showSuccessMessage: false, showErrorMessage: false })

        axios.post('/api/emails', {
            subject: this.state.subject,
            body: this.state.body,
            template: this.props.template_type,
            entity: this.props.entity,
            entity_id: this.props.entity_id,
            mark_sent: this.state.mark_sent,
            design: this.state.design
        })
            .then((r) => {
                this.setState({ showSuccessMessage: true, showErrorMessage: false })
                console.warn(this.state.users)
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors,
                    showSuccessMessage: false,
                    showErrorMessage: true
                })
            })
    }

    handleChange (e) {
        const subjectKey = this.props.calculated_template.replace('template', 'subject')
        const bodyKey = this.props.calculated_template
        const name = e.target.name === 'subject' ? subjectKey : bodyKey
        const value = e.target.value

        this.setState({
            [e.target.name]: value
        }, () => this.props.handleSettingsChange(name, value))
    }

    exportHtml () {
        this.editor.exportHtml(data => {
            const { design, html } = data
            this.setState({ design: design, html: html }, () => this.sendMessage())
        })
    }

    render () {
        const successMessage = this.state.showSuccessMessage === true
            ? <SuccessMessage message={translations.successfully_sent}/> : null
        const errorMessage = this.state.showErrorMessage === true ? <ErrorMessage
            message={translations.failed_to_send}/> : null

        const customer = this.props.entity_object && this.props.customers && this.props.customers.length ? this.props.customers.filter(customer => customer.id === this.props.entity_object.customer_id) : []
        const contacts = customer.length && customer[0] ? customer[0].contacts : []
        const invitations = this.props.entity_object && this.props.customers && this.props.customers.length ? this.props.entity_object.invitations : []

        const contactList = invitations.length && contacts.length ? invitations.map((invitation, index) => {
            const contact = contacts.filter(contact => contact.id === invitation.client_contact_id)
            return <li>{`${contact[0].first_name} ${contact[0].last_name} <${contact[0].email}>`}</li>
        }) : null

        return (
            <Form>
                {successMessage}
                {errorMessage}
                {contactList}

                {customer.length &&
                <FormGroup>
                    <Label for="exampleEmail">{translations.to}</Label>
                    <Input value={customer[0].name} type="text" name="to"
                        id="to"
                        placeholder={translations.to} />
                    {this.renderErrorFor('subject')}
                </FormGroup>
                }

                <FormGroup>
                    <Label for="exampleEmail">{translations.subject}</Label>
                    <Input value={this.props.subject} type="text" onChange={this.handleChange} name="subject"
                        id="subject"
                        placeholder={translations.subject}/>
                    {this.renderErrorFor('subject')}
                </FormGroup>

                <FormGroup>
                    <Label for="exampleEmail">{translations.body}</Label>
                    <Input className="textarea-lg" size="lg" type="textarea" onChange={this.handleChange}
                        value={this.props.body} name="body"/>
                    {this.renderErrorFor('body')}
                </FormGroup>

                <FormGroup check>
                    <Label check>
                        <Input value={this.state.mark_sent} onChange={this.props.handleCheck} type="checkbox"/>
                        {translations.mark_sent}
                    </Label>
                </FormGroup>

                <Button onClick={this.sendMessage} color="primary">{translations.send}</Button>
            </Form>
        )
    }
}
