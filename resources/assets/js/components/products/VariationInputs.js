import React from 'react'
import { Col, Row, Button, FormGroup, Label, Input } from 'reactstrap'
import AttributeDropdown from '../common/AttributeDropdown'
import AttributeValueDropdown from '../common/AttributeValueDropdown'

const AttributeInputs = (props) => {
    return (
        props.attributes.map((val, idx) => {
            return (
                <div key={idx}>
                    <Row form>
                        <Col md={2}>
                            <FormGroup>
                                <Label for="exampleEmail">Attribute</Label>
                                <AttributeDropdown
                                    attributes={props.attributes}
                                    data_id={idx}
                                    attribute_id={props.variations[idx].attribute_id}
                                    handleInputChanges={props.onChange}
                                    name="attribute_id"
                                    errors={props.errors}
                                />
                            </FormGroup>
                        </Col>

                       <Col md={2}>
                            <FormGroup>
                                <Label for="exampleEmail">Attribute Value</Label>
                                <AttributeValueDropdown
                                    attribute_values={props.attribute_values}
                                    data_id={idx}
                                    attribute_value_id={props.variations[idx].attribute_value_id}
                                    handleInputChanges={props.onChange}
                                    name="attribute_value_id"
                                    errors={props.errors}
                                />
                            </FormGroup>
                        </Col>
                        <Col md={2}>
                            <FormGroup>
                                <Label for="examplePassword">Price</Label>
                                <Input type="text"
                                    data-id={idx}
                                    onChange={props.onChange}
                                    value={props.variations[idx].price}
                                    name="price"
                                />
                            </FormGroup>
                        </Col>

                         <Col md={2}>
                            <FormGroup>
                                <Label for="examplePassword">Sales Price</Label>
                                <Input type="text"
                                    data-id={idx}
                                    onChange={props.onChange}
                                    value={props.variations[idx].sales_price}
                                    name="sales_price"
                                />
                            </FormGroup>
                        </Col>

                         <Col md={2}>
                            <FormGroup>
                                <Label for="examplePassword">Quantity</Label>
                                <Input type="text"
                                    data-id={idx}
                                    onChange={props.onChange}
                                    value={props.variations[idx].quantity}
                                    name="quantity"
                                />
                            </FormGroup>
                        </Col>
                    </Row>

                    <Button color="danger" onClick={() => props.removeLine(idx)}>
                        Remove
                    </Button>
                </div>
            )
        })
    )
}
export default VariationInputs
