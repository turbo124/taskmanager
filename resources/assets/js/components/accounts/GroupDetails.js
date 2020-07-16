import React from 'react'
import {
    CustomInput,
    Input,
    FormGroup,
    Label,
    Card,
    CardBody,
    CardHeader
} from 'reactstrap'
import { translations } from '../common/_translations'

export default class GroupDetails extends React.Component {
    constructor (props) {
        super(props)
    }

    render () {
        return (<Card>
            <CardHeader>{translations.details}</CardHeader>
            <CardBody>
                <FormGroup>
                    <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                    <Input className={this.props.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                        id="name" value={this.props.group.name} placeholder={translations.name}
                        onChange={this.props.handleInput.bind(this)}/>
                    {this.props.renderErrorFor('name')}
                </FormGroup>
            </CardBody>
        </Card>
        )
    }
}
