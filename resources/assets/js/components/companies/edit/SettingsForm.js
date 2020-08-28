import React from 'react'
import { Card, CardBody, CardHeader, FormGroup, Label } from 'reactstrap'
import CurrencyDropdown from '../../common/CurrencyDropdown'
import IndustryDropdown from '../../common/IndustryDropdown'
import UserDropdown from '../../common/UserDropdown'
import { translations } from '../../common/_translations'

export default class SettingsForm extends React.Component {
    constructor (props) {
        super(props)
        this.state = {}
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

    render () {
        return (<Card>
            <CardHeader>{translations.settings}</CardHeader>
            <CardBody>
                <FormGroup>
                    <Label for="postcode">Currency(*):</Label>
                    <CurrencyDropdown
                        currency_id={this.props.company.currency_id}
                        errors={this.props.errors}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>

                <FormGroup>
                    <Label for="postcode">Industry:</Label>
                    <IndustryDropdown
                        industry_id={this.props.company.industry_id}
                        errors={this.props.errors}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>

                <FormGroup>
                    <Label for="postcode">{translations.assigned_user}:</Label>
                    <UserDropdown
                        user_id={this.props.company.assigned_to}
                        name="assigned_to"
                        errors={this.props.errors}
                        handleInputChanges={this.props.handleInput}
                    />
                </FormGroup>
            </CardBody>
        </Card>
        )
    }
}
