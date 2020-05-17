import React, { Component } from 'react'
import axios from 'axios'
import { Input, FormGroup } from 'reactstrap'

export default class ProductAttributeDropdown extends Component {
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

    getValues () {
        axios.get(/api/products/${this.props.product_id}')
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
            valueList = this.state.values.map(value => {
                return <option key={value.id} value={value.id}>{value.value}</option>
            })
        }

        const name = this.props.name && this.props.name ? this.props.name : 'attribute_id'
        const dataId = this.props.dataId ? this.props.dataId : 0

        return (
            <FormGroup className="mr-2">
                <Input value={this.props.attribute_value_id} onChange={this.props.handleInputChanges} type="select"
                    data-line={dataId}
                    name={name} id={name}>
                    <option value="">Select Value</option>
                    {valueList}
                </Input>
                {this.renderErrorFor('attribute_value_id')}
            </FormGroup>
        )
    }
}
