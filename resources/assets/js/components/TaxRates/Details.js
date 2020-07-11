import React, { Component } from 'react'
import {
    Input, FormGroup, Label
} from 'reactstrap'
import { icons, translations } from '../common/_icons'
import DecoratedFormField from '../common/DecoratedFormField'

export default class Details extends Component {
    render () {
        return (
            <React.Fragment>
                <FormGroup className="mb-3">
                    <Label>{translations.name}</Label>
                    <Input className={this.props.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                        value={this.props.tax_rate.name} onChange={this.props.handleInput}/>
                    {this.props.renderErrorFor('name')}
                </FormGroup>

                <Label>{translations.amount}</Label>
                <DecoratedFormField hasErrorFor={this.props.hasErrorFor}
                    renderErrorFor={this.props.renderErrorFor} name="rate"
                    handleChange={this.props.handleInput}
                    value={this.props.tax_rate.rate} icon={icons.percent}/>
            </React.Fragment>
        )
    }
}
