import React, { Component } from 'react'
import { CustomInput, FormGroup, Input, Label } from 'reactstrap'
import { translations } from '../../common/_translations'

export default class Details extends Component {
    render () {
        return (
            <React.Fragment>
                <FormGroup>
                    <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                    <Input className={this.props.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                        value={this.props.template.name} id="name" placeholder={translations.name}
                        onChange={this.props.handleInput}/>
                    {this.props.renderErrorFor('name')}
                </FormGroup>

                <FormGroup>
                    <Label for="description">{translations.description} </Label>
                    <Input className={this.props.hasErrorFor('description') ? 'is-invalid' : ''} type="textarea"
                        name="description" id="description" rows="5"
                        value={this.props.template.description}
                        placeholder={translations.description} onChange={this.props.handleInput}/>
                    {this.props.renderErrorFor('description')}
                </FormGroup>

                <FormGroup>
                    <Label for="status">{translations.status} </Label>
                    <Input className={this.props.hasErrorFor('status') ? 'is-invalid' : ''} type="select"
                        name="send_on"
                        id="send_on"
                        value={this.props.template.send_on}
                        onChange={this.props.handleInput}
                    >
                        <option value="0">{translations.disable}</option>
                        <option value="1">{translations.new}</option>
                        <option value="1">{translations.open}</option>
                        <option value="1">{translations.closed}</option>
                    </Input>
                    {this.props.renderErrorFor('status')}
                </FormGroup>
            </React.Fragment>
        )
    }
}
