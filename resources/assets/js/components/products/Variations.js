import React, { Component } from 'react'
import InvoiceLineInputs from './InvoiceLineInputs'
import PaymentModel from '../models/PaymentModel'

export default class Variations extends Component {
    constructor (props) {
        super(props)

        //this.paymentModel = new PaymentModel(this.props.invoices)

        this.state = {
            variations: this.props.variations && this.props.variations.length ? this.props.variations : [{ price: null, sale_price: 0, quantity: 0, is_default: false }],
            amount: 0,
            customer_id: null
        }

        this.handleChange = this.handleChange.bind(this)
        this.addLine = this.addLine.bind(this)
        this.removeLine = this.removeLine.bind(this)
    }

    handleChange (e) {
        const name = e.target.name
        const idx = e.target.dataset.id
        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value 

        const variations = [...this.state.variations]
        let amount = 0

        variations[e.target.dataset.id][e.target.name] = value
        this.setState({ variations }, () => {
            this.props.onChange(this.state.variations)
        }
        )
    }

    addLine (e) {

        this.setState((prevState) => ({
            variations: [...prevState.variations, { price: 0, sales_price: 0, quantity: 0, is_default: false}]
        }), () => this.props.onChange(this.state.variations))
    }

    removeLine (idx) {
        this.setState({
            variations: this.state.variations.filter(function (variation, sidx) {
                return sidx !== idx
            })
        }, () => this.props.onChange(this.state.variations))
    }

    render () {
        const { variations } = this.state
        return (
            <form>
                <VariationInputs errors={this.props.errors}
                    onChange={this.handleChange} variations={variations}
                    removeLine={this.removeLine}/>
                 <Button color="primary" onClick={this.addLine}>Add</Button>
            </form>
        )
    }
}
