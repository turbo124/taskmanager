import React from 'react'
import { FormGroup, Label, Input, Card, CardHeader, CardBody, Row, Col } from 'reactstrap'
import TaxRateDropdown from '../common/TaxRateDropdown'
import DesignDropdown from '../common/DesignDropdown'
import { translations } from './_icons'

export default function InvoiceSettings (props) {
    console.log('settings', props.settings)
    return (
        <Card>
            <CardHeader>Items</CardHeader>
            <CardBody>
                <Row form>
                    <Col md={6}>
                        <FormGroup>
                            <Label for="exampleEmail">{translations.transaction_fee}</Label>
                            <Input onChange={props.handleSurcharge} type="text" name="transaction_fee" id="transaction_fee" value={props.settings.transaction_fee} />
                        </FormGroup>
                    </Col>
                    <Col md={6}>
                        <FormGroup check>
                            <Label check for="examplePassword">
                                <Input onChange={props.handleSurcharge} type="checkbox" name="transaction_fee_tax" id="examplePassword" checked={props.settings.transaction_fee_tax} />
                                {translations.transaction_tax}
                            </Label>
                        </FormGroup>
                    </Col>
                </Row>

                <Row form>
                    <Col md={6}>
                        <FormGroup>
                            <Label for="exampleEmail">{translations.shipping_cost}</Label>
                            <Input onChange={props.handleSurcharge} type="text" name="shipping_cost" id="shipping_cost" value={props.settings.shipping_cost} />
                        </FormGroup>
                    </Col>
                    <Col md={6}>
                        <FormGroup check>
                            <Label check for="examplePassword">
                                <Input onChange={props.handleSurcharge} type="checkbox" name="shipping_cost_tax" id="shipping_cost_tax" checked={props.settings.shipping_cost_tax} />
                                {translations.shipping_tax}
                            </Label>
                        </FormGroup>
                    </Col>
                </Row>

                <FormGroup>
                    <Label>Tax</Label>
                    <TaxRateDropdown
                        name="tax"
                        handleInputChanges={props.handleInput}
                        errors={props.errors}
                    />
                </FormGroup>

                <Row form>
                    <Col>
                        <FormGroup>
                            <Label>Discount</Label>
                            <Input
                                value={props.discount}
                                type='text'
                                name='discount'
                                id='discount'
                                onChange={props.handleInput}
                            />
                        </FormGroup>
                    </Col>

                    <Col>
                        <FormGroup>
                            <Label>Discount Type</Label>
                            <Input
                                bsSize="sm"
                                value={props.is_amount_discount}
                                type='select'
                                name='is_amount_discount'
                                id='is_amount_discount'
                                onChange={props.handleInput}
                            >
                                <option value="false">Percent</option>
                                <option value="true">Amount</option>
                            </Input>
                        </FormGroup>
                    </Col>
                </Row>

                <FormGroup>
                    <Label>Design</Label>
                    <DesignDropdown name="design_id" design={props.design_id} handleChange={props.handleInput}/>
                </FormGroup>
            </CardBody>
        </Card>

    )
}
