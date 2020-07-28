import React from 'react'
import {
    Card,
    CardBody,
    CardHeader, Col,
    FormGroup,
    Row
} from 'reactstrap'
import AddButtons from '../common/AddButtons'
import { translations } from '../common/_translations'
import Details from './Details'
import FormBuilder from '../accounts/FormBuilder'
import Checkbox from '../common/Checkbox'

export default class Settings extends React.Component {
    constructor (props) {
        super(props)

        this.card_types = [
            {
                name: 'visa',
                label: 'Visa'
            },
            {
                name: 'mastercard',
                label: 'Mastercard'
            },
            {
                name: 'american_express',
                label: 'American Express'
            },
            {
                name: 'diners_card',
                label: 'Diners Card'
            },
            {
                name: 'discover_card',
                label: 'Discover Card'
            }
        ]
    }

    getSettingFields () {
        const settings = this.props.gateway

        return [
            [
                {
                    name: 'require_cvv',
                    label: translations.require_cvv,
                    type: 'switch',
                    placeholder: translations.require_cvv,
                    value: settings.require_cvv ? settings.require_cvv : '',
                    group: 1,
                    class_name: 'col-12'
                },
                {
                    name: 'update_details',
                    label: translations.update_details,
                    help_text: translations.update_details_help_text,
                    type: 'switch',
                    placeholder: translations.update_details,
                    value: settings.update_details ? settings.update_details : '',
                    group: 1,
                    class_name: 'col-12'
                },
                {
                    name: 'show_billing_address',
                    label: translations.show_billing_address,
                    help_text: translations.show_billing_address_help_text,
                    type: 'switch',
                    placeholder: translations.show_billing_address,
                    value: settings.show_billing_address ? settings.show_billing_address : '',
                    group: 1,
                    class_name: 'col-12'
                },

                {
                    name: 'show_shipping_address',
                    label: translations.show_shipping_address,
                    help_text: translations.show_shipping_address_help_text,
                    type: 'switch',
                    placeholder: translations.show_shipping_address,
                    value: settings.show_shipping_address ? settings.show_shipping_address : '',
                    group: 1,
                    class_name: 'col-12'
                }
            ]
        ]
    }

    render () {
        return <React.Fragment>
            <Card>
                <CardHeader>{translations.settings}</CardHeader>
                <CardBody>
                    <FormBuilder
                        handleCheckboxChange={this.props.handleInput}
                        handleChange={this.props.handleInput}
                        formFieldsRows={this.getSettingFields()}
                    />
                </CardBody>
            </Card>

            <Card>
                <CardHeader>{translations.accepted_cards}</CardHeader>
                <CardBody>
                    <FormGroup>
                        <Row>
                            <Col sm={10}>
                                {
                                    this.card_types.map((item, index) => (
                                        <div key={index} className="form-check">
                                            <Checkbox name={item.name}
                                                checked={this.props.gateway.accepted_cards.get(item.name)}
                                                onChange={this.props.updateCards}/>
                                            <label className="form-check-label" htmlFor="gridRadios1">
                                                {item.label}
                                            </label>
                                        </div>
                                    ))
                                }
                            </Col>
                        </Row>
                    </FormGroup>

                </CardBody>
            </Card>
        </React.Fragment>
    }
}
