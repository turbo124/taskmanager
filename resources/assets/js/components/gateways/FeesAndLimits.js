import React from 'react'
import {
    Card,
    CardBody,
    CardHeader, Col,
    FormGroup,
    Row
} from 'reactstrap'
import { translations } from '../common/_translations'
import FormBuilder from '../accounts/FormBuilder'
import Checkbox from '../common/Checkbox'

export default class FeesAndLimits extends React.Component {
    constructor (props) {
        super(props)
    }

    getSettingFieldsSectionOne () {
        const settings = this.props.gateway.fees_and_limits.length ? this.props.gateway.fees_and_limits[0] : ''

        const formFields = [
            [
                {
                    name: 'min_limit',
                    label: translations.min_limit,
                    type: 'text',
                    placeholder: translations.min_limit,
                    value: settings && settings.min_limit ? settings.min_limit : '',
                    group: 1
                },
                {
                    name: 'max_limit',
                    label: translations.max_limit,
                    type: 'text',
                    placeholder: translations.max_limit,
                    value: settings && settings.max_limit ? settings.max_limit : '',
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getSettingFieldsSectionTwo () {
        const settings = this.props.gateway.fees_and_limits.length ? this.props.gateway.fees_and_limits[0] : ''

        const formFields = [
            [
                {
                    name: 'fee_amount',
                    label: translations.fee_amount,
                    type: 'text',
                    placeholder: translations.fee_amount,
                    value: settings && settings.fee_amount ? settings.fee_amount : '',
                    group: 1
                },
                {
                    name: 'fee_percent',
                    label: translations.fee_percent,
                    type: 'text',
                    placeholder: translations.fee_percent,
                    value: settings && settings.fee_percent ? settings.fee_percent : '',
                    group: 1
                },
                {
                    name: 'fee_cap',
                    label: translations.fee_cap,
                    type: 'text',
                    placeholder: translations.fee_cap,
                    value: settings && settings.fee_cap ? settings.fee_cap : '',
                    group: 2
                }
            ]
        ]

        return formFields
    }

    render () {
        return <React.Fragment>
            <Card>
                <CardBody>
                    <FormBuilder
                        handleChange={this.props.updateFeesAndLimits}
                        formFieldsRows={this.getSettingFieldsSectionOne()}
                    />
                </CardBody>
            </Card>

            <Card>
                <CardBody>
                    <FormBuilder
                        handleChange={this.props.updateFeesAndLimits}
                        formFieldsRows={this.getSettingFieldsSectionTwo()}
                    />
                </CardBody>
            </Card>
        </React.Fragment>
    }
}
