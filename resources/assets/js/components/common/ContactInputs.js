import React, { Component } from 'react'
import { Col, Row, Button, FormGroup, Label, Input, Collapse, CardBody, Card, CardHeader } from 'reactstrap'
import { translations } from './_translations'

export default class ContactInputs extends Component {
    constructor (props) {
        super(props)
        this.state = {
            collapse: 0,
            contacts: this.props.contacts,
            errors: [],
            showSuccessMessage: false,
            showErrorMessage: false,
            message: ''
        }

        this.toggle = this.toggle.bind(this)
       
    }

    toggle (e) {
      const event = e.target.dataset.event
      this.setState({ collapse: this.state.collapse === Number(event) ? 0 : Number(event) })
    }

    render() {
        const {contacts, collapse} = this.state
  
        return (
            <div className="container">
                <h3 className="page-header">Reactstrap Accordion using card component</h3>
                contacts.map((val, idx) => {
                    return (
                        <Card style={{ marginBottom: '1rem' }} key={index}>
                            <CardHeader onClick={this.toggle} data-event={index}>Header</CardHeader>
                            <Collapse isOpen={collapse === index}>
                                <CardBody>
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
                            </CardBody>
                        </Collapse>
                    </Card>
                )
            }) 
        </div>
        )
    }
}
