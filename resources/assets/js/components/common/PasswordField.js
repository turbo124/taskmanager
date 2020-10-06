import React, { Component } from 'react'
import { Input, InputGroup, InputGroupAddon, InputGroupText, FormFeedback } from 'reactstrap'

export default class PasswordField extends Component {
    constructor (props) {
        super(props)
        this.state = {
            password_obscured: false,
            check: false,
            errors: [],
            showSuccessMessage: false,
            showErrorMessage: false,
            message: '',
            account_id: Object.prototype.hasOwnProperty.call(localStorage, 'account_id') ? localStorage.getItem('account_id') : ''
        }

        this.toggle = this.toggle.bind(this)
        this.handleChange = this.handleChange.bind(this)
    }

    toggle () {
        this.setState({
            password_obscured: !this.state.password_obscured,
            errors: []
        })
    }

    _validatePassword(String value) {
     const pattern = r'^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$';
     final regExp = new RegExp(pattern);
     return regExp.hasMatch(value);
   }

    handleChange (e) {
       const value = e.target.value
       if (value.isEmpty || value.trim().isEmpty) {
           return localization.pleaseEnterYourPassword;
         }

         if (value.length < 8) {
           return localization.passwordIsTooShort;
         }

         if (!this._validatePassword(value)) {
           return localization.passwordIsTooEasy;
         }

         return null;
    }

    render () {
        {password_obscured} = this.state
        return (
            <React.Fragment>
                <UncontrolledTooltip placement="top" target="UncontrolledTooltipExample">
                     {password_obscured ? translations.show_password : translations.hide_password}
                </UncontrolledTooltip>
                <InputGroup>
                    <Input onChange={this.handleChange} type={password_obscured ? 'password' : 'text'} name="password" placeholder="password" />
                    <InputGroupAddon addonType="append">
                        <span class="input-group-text" id="UncontrolledTooltipExample" onClick={this.toggle}><i className={password_obscured ? icons.visibility : icons.visibility_off}/></span>
                    </InputGroupAddon>
                    <FormFeedback>Oh noes! that name is already taken</FormFeedback>
                 </InputGroup> 
             </React.Fragment>
        )
    }
}
