import React from 'react'
import { Card, CardBody, CardHeader, FormGroup, Input, Label } from 'reactstrap'
import UserDropdown from '../../common/UserDropdown'
import { translations } from '../../common/_translations'

export default class Details extends React.Component {
    constructor (props) {
        super(props)

        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
        this.buildSourceTypeOptions = this.buildSourceTypeOptions.bind(this)
    }

    hasErrorFor (field) {
        return !!this.props.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.props.errors[field][0]}</strong>
                </span>
            )
        }
    }

    buildSourceTypeOptions () {
        let sourceTypeContent
        if (!this.props.sourceTypes.length) {
            sourceTypeContent = <option value="">Loading...</option>
        } else {
            sourceTypeContent = this.props.sourceTypes.map((user, index) => (
                <option key={index} value={user.id}>{user.name}</option>
            ))
        }

        return (
            <FormGroup>
                <Label for="source_type">Source Type:</Label>
                <Input value={this.props.lead.source_type}
                    className={this.hasErrorFor('source_type') ? 'is-invalid' : ''} type="select"
                    name="source_type" id="source_type" onChange={this.props.handleInputChanges}>
                    <option value="">Choose:</option>
                    {sourceTypeContent}
                </Input>
                {this.renderErrorFor('source_type')}
            </FormGroup>
        )
    }

    render () {
        const sourceTypeOptions = this.buildSourceTypeOptions()

        return (
            <React.Fragment>
                <Card>
                    <CardHeader>{translations.details}</CardHeader>
                    <CardBody>
                        <FormGroup>
                            <Label for="name"> {translations.name} </Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''} type="text"
                                id="name" onChange={this.props.handleInputChanges}
                                name="name"
                                value={this.props.lead.name}
                                placeholder={translations.name}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="description"> {translations.description} </Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''} type="textarea"
                                id="first_name" onChange={this.props.handleInputChanges} name="description"
                                value={this.props.lead.description}
                                placeholder={translations.description}/>
                            {this.renderErrorFor('description')}
                        </FormGroup>

                        <FormGroup>
                            <Label for="valued_at"> {translations.amount} </Label>
                            <Input className={this.hasErrorFor('valued_at') ? 'is-invalid' : ''} type="text"
                                id="valued_at"
                                value={this.props.lead.valued_at}
                                onChange={this.props.handleInputChanges.bind(this)} name="valued_at"
                                placeholder={translations.amount}/>
                            {this.renderErrorFor('valued_at')}
                        </FormGroup>

                        <UserDropdown handleInputChanges={this.props.handleInputChanges}
                            user_id={this.props.lead.assigned_to} name="assigned_to"
                            users={this.props.users}/>

                        {sourceTypeOptions}
                    </CardBody>
                </Card>

            </React.Fragment>
        )
    }
}
