import React from 'react'
import { Col, Row, Button, FormGroup, Label, Input } from 'reactstrap'
import { translations } from './_translations'

const ContactInputs = (props) => {
    return (
        props.contacts.map((val, idx) => {
            return (
                <div className="border-bottom border-success border-dashed pb-4 pt-4" key={idx}>
                    <Row form>
                        <Col md={2}>
                            <FormGroup className="mt-4" check>
                                <Label check>
                                    <Input type="checkbox"
                                        onChange={props.handleChange}
                                        data-id={idx}
                                        checked={props.contacts[idx].is_primary}
                                        data-field="is_primary"
                                    />
                                    Primary Contact
                                </Label>
                            </FormGroup>
                        </Col>

                        <Col md={5}>
                            <FormGroup>
                                <Label for="exampleEmail">{translations.first_name}</Label>
                                <Input type="text"
                                    onChange={props.handleChange}
                                    data-id={idx}
                                    value={props.contacts[idx].first_name}
                                    data-field="first_name"
                                />
                            </FormGroup>
                        </Col>
                        <Col md={5}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.last_name}</Label>
                                <Input type="text"
                                    onChange={props.handleChange}
                                    data-id={idx}
                                    value={props.contacts[idx].last_name}
                                    data-field="last_name"
                                />
                            </FormGroup>
                        </Col>
                    </Row>

                    <Row form>
                        <Col md={4}>
                            <FormGroup>
                                <Label for="exampleEmail">{translations.email}</Label>
                                <Input type="text"
                                    onChange={props.handleChange}
                                    data-id={idx}
                                    value={props.contacts[idx].email}
                                    data-field="email"
                                />
                            </FormGroup>
                        </Col>
                        <Col md={4}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.phone_number}</Label>
                                <Input type="text"
                                    onChange={props.handleChange}
                                    data-id={idx}
                                    value={props.contacts[idx].phone}
                                    data-field="phone"
                                />
                            </FormGroup>
                        </Col>

                        <Col md={4}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.password}</Label>
                                <Input type="password"
                                    onChange={props.handleChange}
                                    data-id={idx}
                                    value={props.contacts[idx].password}
                                    data-field="password"
                                />
                            </FormGroup>
                        </Col>
                    </Row>

                    <Button color="danger" size="lg" block onClick={() => props.removeContact(idx)}>
                        {translations.remove}
                    </Button>
                </div>
            )
        })
    )
}
export default ContactInputs
