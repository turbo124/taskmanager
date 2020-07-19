import React, { Component } from 'react'
import axios from 'axios'
import { Input, FormGroup } from 'reactstrap'
import { translations } from './_translations'

export default class CreditDropdown extends Component {
    constructor (props) {
        super(props)
        this.state = {
            credits: []
        }

        this.getCredits = this.getCredits.bind(this)
    }

    componentDidMount () {
        if (!this.props.credits || !this.props.credits.length) {
            this.getCredits()
        } else {
            this.state.credits = this.props.credits
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

    getCredits () {
        const url = this.props.status && this.props.status !== null ? `api/credits/getCreditsByStatus/${this.props.status}` : '/api/credits'

        axios.get(url)
            .then((r) => {
                this.setState({
                    credits: r.data
                }, () => console.log('credits', this.state.credits))
            })
            .catch((e) => {
                console.error(e)
            })
    }

    render () {
        let creditList = null
        let { credits } = this.state

        if (!credits) {
            creditList = <option value="">Loading...</option>
        } else {
            if (this.props.customer_id && this.props.customer_id !== null) {
                credits = credits.filter(credit => credit.customer_id === parseInt(this.props.customer_id))
            }

            if (this.props.allowed_credits && this.props.allowed_credits !== null) {
                credits = credits.filter(credit => this.props.allowed_credits.includes(credit.id))
            }

            creditList = credits.filter(credit => credit.balance > 0).map((credit, index) => (
                <option key={index} value={credit.id}>{credit.number} ({credit.total})</option>
            ))
        }

        const name = this.props.name && this.props.name ? this.props.name : 'credit_id'
        const error_name = this.props.error_name ? this.props.error_name : name
        const data_id = this.props.data_id ? this.props.data_id : 0

        const selected = this.props.credits && this.props.credits.length === 1 ? this.props.credits[0].id : this.props.credit_id

        const selectList = this.props.multiple && this.props.multiple === true ? (
            <Input onChange={this.props.handleInputChanges} multiple type="select"
                data-id={data_id}
                name={name} id={name}>
                {creditList}
            </Input>
        ) : <Input data-id={data_id} value={selected} onChange={this.props.handleInputChanges}
            type="select"
            name={name} id={name}>
            <option value="">{translations.select_option}</option>
            {creditList}
        </Input>

        return (
            <FormGroup>
                {selectList}
                {this.renderErrorFor(error_name)}
            </FormGroup>
        )
    }
}
