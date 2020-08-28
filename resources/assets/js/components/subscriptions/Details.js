import React, { Component } from 'react'
import { FormGroup, Input, Label } from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import DecoratedFormField from '../common/DecoratedFormField'
import { consts } from '../common/_consts'

export default class Details extends Component {
    render () {
        return (
            <React.Fragment>
                <FormGroup>
                    <Label for="name">{translations.name} <span className="text-danger">*</span></Label>
                    <Input className={this.props.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                        id="name" value={this.props.subscription.name} placeholder={translations.name}
                        onChange={this.props.handleInput}/>
                    {this.props.renderErrorFor('name')}
                </FormGroup>

                <Label>{translations.target_url}</Label>
                <DecoratedFormField hasErrorFor={this.props.hasErrorFor}
                    renderErrorFor={this.props.renderErrorFor} name="target_url"
                    handleChange={this.props.handleInput}
                    value={this.props.subscription.target_url} icon={icons.link}/>

                <FormGroup>
                    <Label for="event_id">{translations.event}<span className="text-danger">*</span></Label>
                    <Input className={this.props.hasErrorFor('event_id') ? 'is-invalid' : ''} type="select"
                        name="event_id"
                        id="event_id" value={this.props.subscription.event_id}
                        onChange={this.props.handleInput.bind(this)}>
                        <option value="">{translations.select_event}</option>
                        <option value={consts.order_created_subscription}>{translations.order_created}</option>
                        <option value={consts.order_deleted_subscription}>{translations.order_deleted}</option>
                        <option value={consts.order_backordered_subscription}>{translations.order_backordered}</option>
                        <option value={consts.order_held_subscription}>{translations.order_held}</option>
                        <option value={consts.credit_created_subscription}>{translations.credit_created}</option>
                        <option value={consts.credit_deleted_subscription}>{translations.credit_deleted}</option>
                        <option value={consts.customer_created_subscription}>{translations.customer_created}</option>
                        <option value={consts.customer_deleted_subscription}>{translations.customer_deleted}</option>
                        <option value={consts.invoice_created_subscription}>{translations.invoice_created}</option>
                        <option value={consts.invoice_deleted_subscription}>{translations.invoice_deleted}</option>
                        <option value={consts.payment_created_subscription}>{translations.payment_created}</option>
                        <option value={consts.payment_deleted_subscription}>{translations.payment_deleted}</option>
                        <option value={consts.quote_created_subscription}>{translations.quote_created}</option>
                        <option value={consts.quote_deleted_subscription}>{translations.quote_deleted}</option>
                        <option value={consts.lead_created_subscription}>{translations.lead_created}</option>
                    </Input>
                    {this.props.renderErrorFor('event_id')}
                </FormGroup>
            </React.Fragment>
        )
    }
}
