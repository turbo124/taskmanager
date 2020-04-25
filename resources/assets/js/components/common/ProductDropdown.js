import React, { Component } from 'react'
import axios from 'axios'
import { Input } from 'reactstrap'

export default class ProductDropdown extends Component {
    constructor (props) {
        super(props)
        this.state = {
            products: []
        }

        this.getProducts = this.getProducts.bind(this)
    }

    componentDidMount () {
        if (!this.props.products || !this.props.products.length) {
            this.getProducts()
        } else {
            this.setState({ products: this.props.products })
        }
    }

    getProducts () {
        axios.get('/api/products')
            .then((r) => {
                this.setState({
                    products: r.data
                })
            })
            .catch((e) => {
                console.error(e)
            })
    }

    handleChange (value, name) {
        const e = {
            target: {
                id: name,
                name: name,
                value: value.id
            }
        }

        this.props.handleInputChanges(e)
    }

    buuildMultiple (name, productList) {
        return (
            <Input value={this.props.product} onChange={this.props.handleInputChanges} type="select" multiple
                name={name} id={name}>
                {productList}
            </Input>
        )
    }

    buildSingle (name, productList, dataId) {
        return (
            <Input data-line={dataId} value={this.props.product} onChange={this.props.handleInputChanges} type="select"
                name={name} id={name}>
                <option value="">Select Product</option>
                {productList}
            </Input>
        )
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

    render () {
        let productList = null
        if (!this.state.products.length) {
            productList = <option value="">Loading...</option>
        } else {
            productList = this.state.products.map((product, index) => (
                <option key={index} value={product.id}>{product.name}</option>
            ))
        }

        const name = this.props.name && this.props.name ? this.props.name : 'product_id'
        const dataId = this.props.dataId ? this.props.dataId : 0
        const input = this.props.multiple && this.props.multiple === true ? this.buuildMultiple(name, productList) : this.buildSingle(name, productList, dataId)

        return (
            <React.Fragment>
                {input}
                {this.renderErrorFor('product_id')}
            </React.Fragment>
        )
    }
}
