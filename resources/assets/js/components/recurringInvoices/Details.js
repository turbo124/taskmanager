import React from 'react'
import { FormGroup, Label, Input } from 'reactstrap'
import { translations } from '../common/_icons'
import Datepicker from '../common/Datepicker'

export default function Details (props) {
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
        </React.Fragment>

    )
}
