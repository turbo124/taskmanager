import React, { Component } from 'react'
import { Input, InputGroup, InputGroupAddon, InputGroupText, FormGroup, Label, Input } from 'reactstrap'

export default class TaxRateField extends Component {
    render () {
        return (
            <div className="form-inline">
                <FormGroup>
                    <Label for="exampleEmail" hidden>{translations.tax_name}</Label>
                    <Input type="email" name="tax_name" id="tax_name" onChange={(event) => {
                        const e = {}
                        
                        e.target = {
                            name: event.target.name,
                            value: event.target.value
                        }
                        this.props.onNameChanged(e)
                    }} />
                </FormGroup>
                <FormGroup>
                    <Label for="examplePassword" hidden>{translations.tax_amount}</Label>
                    <Input type="email" name="tax_amount" id="tax_amount" onChange={(event) => {
                        const e = {}
                        
                        e.target = {
                            name: event.target.name,
                            value: event.target.value
                        }
                        this.props.onAmountChanged(e)
                    }} />
                </FormGroup>
            </Form>
        )
    }
}
