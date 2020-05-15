import React, { Component } from 'react'
import axios from 'axios'
import { Input, FormGroup } from 'reactstrap'

export default class AttributeValueDropdown extends Component {
    constructor (props) {
        super(props)
        this.state = {
            values: []
        }

        this.getValues = this.getValues.bind(this)
    }

    componentDidMount () {
        if (!this.props.values || !this.props.values.length) {
            this.getValues()
        } else {
            this.setState({ values: this.props.values })
        }
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback d-block'>
                    <strong>{this.props.errors[field][0]}</strong>
                </span>
            )
        }
    }

    hasErrorFor (field) {
        return this.props.errors && !!this.props.errors[field]
    }

    getDepartments () {
        axios.get('/api/attributeValues')
            .then((r) => {
                this.setState({
                    values: r.data
                })
            })
            .catch((e) => {
                console.error(e)
            })
    }

    render () {
        let valueList = null
        if (!this.state.values.length) {
            valueList = <option value="">Loading...</option>
        } else {
            valueList = this.state.values.map((value, index) => (
                <option key={index} value={value.id}>{value.name}</option>
            ))
        }

        const name = this.props.name && this.props.name ? this.props.name : 'attribute_value_id'

        return (
            <FormGroup className="mr-2">
                <Input value={this.props.attribute_value} onChange={this.props.handleInputChanges} type="select"
                    name={name} id={name}>
                    <option value="">Select Department</option>
                    {valueList}
                </Input>
                {this.renderErrorFor('attribute_value_id')}
            </FormGroup>
        )
    }
}
