import React from 'react'
import { FormGroup, Label, Input, Card, CardHeader, CardBody, Row, Col } from 'reactstrap'
import TaxRateDropdown from '../common/TaxRateDropdown'
import DesignDropdown from '../common/DesignDropdown'
import { translations } from './_translations'

export default function InvoiceSettings (props) {
    console.log('settings', props.settings)
    return (
        <Card>
            <CardHeader>{translations.settings}</CardHeader>
            <CardBody>
                <Row form>
                    <Col md={6}>
                        <FormGroup>
                            <Label for="exampleEmail">{translations.transaction_fee}</Label>
                            <Input onChange={props.handleSurcharge} type="text" name="transaction_fee" id="transaction_fee" value={props.settings.transaction_fee} />
                        </FormGroup>
                    </Col>
                    <Col md={6}>
                        <FormGroup>
                            <Label for="exampleEmail">{translations.shipping_cost}</Label>
                            <Input onChange={props.handleSurcharge} type="text" name="shipping_cost" id="shipping_cost" value={props.settings.shipping_cost} />
                        </FormGroup>
                        {/* <FormGroup check> */}
                        {/*    <Label check for="examplePassword"> */}
                        {/*        <Input onChange={props.handleSurcharge} type="checkbox" name="transaction_fee_tax" id="examplePassword" checked={props.settings.transaction_fee_tax} /> */}
                        {/*        {translations.transaction_tax} */}
                        {/*    </Label> */}
                        {/* </FormGroup> */}
                    </Col>
                </Row>

                <Row form>
                    <Col md={6}>
                        <FormGroup>
                            <Label for="exampleEmail">{translations.gateway_fee}</Label>
                            <Input onChange={props.handleSurcharge} type="text" name="gateway_fee" id="gateway_fee" value={props.settings.gateway_fee} />
                        </FormGroup>
                    </Col>

                    <Col md={6}>
                        <FormGroup check>
                            <Label check for="examplePassword">
                                <Input onChange={props.handleSurcharge} type="checkbox" name="gateway_percentage" id="examplePassword" checked={props.settings.gateway_percentage} />
                                {translations.is_percentage}
                            </Label>
                        </FormGroup>
                    </Col>
                </Row>

                {/* <Row form> */}
                {/*    <Col md={6} /> */}
                {/*    <Col md={6}> */}
                {/*        <FormGroup check> */}
                {/*            <Label check for="examplePassword"> */}
                {/*                <Input onChange={props.handleSurcharge} type="checkbox" name="shipping_cost_tax" id="shipping_cost_tax" checked={props.settings.shipping_cost_tax} /> */}
                {/*                {translations.shipping_tax} */}
                {/*            </Label> */}
                {/*        </FormGroup> */}
                {/*    </Col> */}
                {/* </Row> */}

                <Row form>
                    <Col md={6}>
                        <FormGroup>
                            <Label>{translations.discount}</Label>
                            <Input
                                value={props.discount}
                                type='text'
                                name='discount'
                                id='discount'
                                onChange={props.handleInput}
                            />
                        </FormGroup>
                    </Col>

                    <Col md={6}>
                        <FormGroup>
                            <Label>{translations.discount_type}</Label>
                            <Input
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

                <Row form>
                    <Col md={6}>
                        <FormGroup>
                            <Label>{translations.tax}</Label>
                            <TaxRateDropdown
                                name="tax"
                                handleInputChanges={props.handleInput}
                                errors={props.errors}
                            />
                        </FormGroup>
                    </Col>

                    <Col md={6}>
                        <FormGroup>
                            <Label>{translations.design}</Label>
                            <DesignDropdown name="design_id" design={props.design_id} handleChange={props.handleInput}/>
                        </FormGroup>
                    </Col>
                </Row>
            </CardBody>
        </Card>

    )
}
