import React, { Component } from 'react'
import { Input, InputGroup, InputGroupAddon, InputGroupText } from 'reactstrap'

export default class PasswordField extends Component {
    render () {
        return (
            <InputGroup>
                <Input name="password" placeholder="password" />
                <InputGroupAddon addonType="append">
                    <InputGroupText>@example.com</InputGroupText>
                </InputGroupAddon>
             </InputGroup>
        )
    }
}
