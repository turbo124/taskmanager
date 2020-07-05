import React, { Component } from 'react'
import { FormGroup, Label, Input, Card, CardHeader, CardBody } from 'reactstrap'
import AddRecurringQuote from '../recurringQuotes/AddRecurringQuote'
import { translations } from '../common/_icons'

export default class Recurring extends Component {
    constructor (props, context) {
        super(props, context)
        this.state = {
            is_recurring: false
        }
        this.handleSlideClick = this.handleSlideClick.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    handleSlideClick (e) {
        this.setState({ is_recurring: e.target.checked })
    }

    hasErrorFor (field) {
        return this.props.errors && !!this.props.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.props.errors[field][0]}</strong>
                </span>
            )
        }
    }

    render () {
        return (
            <Card>
                <CardHeader>{translations.recurring}</CardHeader>
                <CardBody>
                    <FormGroup>
                        <Label>{translations.is_recurring}</Label>
                        <Input type="checkbox" onChange={this.handleSlideClick}/>
                    </FormGroup>

                    <div className={this.state.is_recurring ? 'collapse show' : 'collapse'}>
                        <AddRecurringQuote
                            invoice={this.props.invoice}
                            setRecurring={this.props.setRecurring}
                        />

                    </div>
                </CardBody>
            </Card>

        )
    }
}
