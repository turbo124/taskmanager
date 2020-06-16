import React, { Component } from 'react'
import {
    InputGroup,
    InputGroupAddon,
    InputGroupText,
    Input
} from 'reactstrap'

export default class DecoratedFormField extends Component {
    render () {
        return (
            <InputGroup>
                <InputGroupAddon addonType="prepend">
                    <InputGroupText><i className={`fa ${this.props.icon}`} /></InputGroupText>
                </InputGroupAddon>
                <Input name={this.props.name} onChange={this.props.handleChange} value={this.props.value}/>
            </InputGroup>
        )
    }
}
