import React from 'react'
import { FormGroup, Input, Label, CustomInput } from 'reactstrap'
import { translations } from '../../common/_translations'
import Datepicker from '../../common/Datepicker'

export default function Recurring (props) {
    return (
        <React.Fragment>
            <FormGroup>
                <Label for="start_date">{translations.start_date}(*):</Label>
                <Datepicker name="start_date" date={props.recurring_invoice.start_date} handleInput={props.handleInput}
                    className={props.hasErrorFor('start_date') ? 'form-control is-invalid' : 'form-control'}/>
                {props.renderErrorFor('start_date')}
            </FormGroup>

            <FormGroup>
                <Label for="end_date">{translations.end_date}(*):</Label>
                <Datepicker name="end_date" date={props.recurring_invoice.end_date} handleInput={props.handleInput}
                    className={props.hasErrorFor('end_date') ? 'form-control is-invalid' : 'form-control'}/>
                {props.renderErrorFor('end_date')}
            </FormGroup>

            <FormGroup>
                <Label for="due_date">{translations.due_date}(*):</Label>
                <Datepicker name="due_date" date={props.recurring_invoice.due_date} handleInput={props.handleInput}
                    className={props.hasErrorFor('due_date') ? 'form-control is-invalid' : 'form-control'}/>
                {props.renderErrorFor('due_date')}
            </FormGroup>

            <FormGroup>
                <Label>{translations.frequency}</Label>
                <Input
                    value={props.recurring_invoice.frequency}
                    type='text'
                    name='frequency'
                    placeholder="Days"
                    id='frequency'
                    onChange={props.handleInput}
                />
            </FormGroup>

            <FormGroup>
                <Label>{translations.frequency}</Label>
                <Input
                    value={props.recurring_invoice.grace_period}
                    type='text'
                    name='grace_period'
                    placeholder="Days"
                    id='grace_period'
                    onChange={props.handleInput}
                />

                <h6 id="passwordHelpBlock" className="form-text text-muted">
                    Help text here
                </h6>
            </FormGroup>

             <a href="#"
                className="list-group-item-dark list-group-item list-group-item-action flex-column align-items-start">
                <div className="d-flex w-100 justify-content-between">
                    <h5 className="mb-1">
                        <i style={{ fontSize: '24px', marginRight: '20px' }} className={field.icon}/>
                        {translations.auto_billing_enabled}
                    </h5>
                    <CustomInput
                        checked={props.recurring_invoice.auto_billing_enabled}
                        type="switch"
                        id="auto_billing_enabled"
                        name="auto_billing_enabled"
                        label=""
                        onChange={this.props.handleInput}/>
                </div>
                
                <h6 id="passwordHelpBlock" className="form-text text-muted">
                    Help text here
                </h6>
            </a>
        </React.Fragment>

    )
}
