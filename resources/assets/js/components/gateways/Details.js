import React from 'react'
import {
    FormGroup, Input, Label, Card, CardBody
} from 'reactstrap'
import { translations } from '../common/_translations'
import Config from './Config'

export default class Details extends React.Component {
    constructor (props) {
        super(props)

        this.providers = [
            {
                key: '13bb8d58',
                name: translations.stripe
            },
            {
                key: '8ab2dce2',
                name: translations.authorize
            },
            {
                key: '64bcbdce',
                name: translations.paypal
            }
        ]
    }

    render () {
        const options = Object.keys(this.providers).map((index) => {
            return <option key={index} value={this.providers[index].key}>{this.providers[index].name}</option>
        })
        return (
            <Card>
                <CardBody>
                    {!this.props.is_edit &&
                   <FormGroup>
                       <Label for="name">{translations.provider} <span className="text-danger">*</span></Label>
                       <Input value={this.props.gateway.gateway_key} onChange={this.props.handleInput} type="select"
                           name="gateway_key" id="gateway_key">
                           <option value="">{translations.select_option}</option>
                           {options}
                       </Input>
                       {this.props.renderErrorFor('name')}
                   </FormGroup>
                    }

                    <Config gateway={this.props.gateway} handleConfig={this.props.handleConfig}/>
                </CardBody>
            </Card>
        )
    }
}
