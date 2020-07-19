import React from 'react'
import { Col, Row, Button, FormGroup, Label, Input } from 'reactstrap'
import CreditDropdown from '../common/CreditDropdown'
import { translations } from '../common/_translations'

const CreditLineInputs = (props) => {
    return (
        props.lines.map((val, idx) => {
            return (
                <div key={idx}>
                    <Row form>
                        <Col md={6}>
                            <FormGroup>
                                <Label for="exampleEmail">{translations.credit}</Label>
                                <CreditDropdown
                                    customer={props.payment.customer_id}
                                    credits={props.credits}
                                    data_id={idx}
                                    credit_id={props.lines[idx].credit_id}
                                    status={props.status}
                                    handleInputChanges={props.onChange}
                                    name="credit_id"
                                    errors={props.errors}
                                />
                            </FormGroup>
                        </Col>
                        <Col md={6}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.amount}</Label>
                                <Input type="text"
                                    data-credit={props.credits.length === 1 ? props.credits[0].id : null}
                                    data-id={idx}
                                    onChange={props.onChange}
                                    value={props.lines[idx].amount}
                                    autoFocus={(props.credits && props.credits.length === 1) || idx === 0}
                                    name="amount"
                                />
                            </FormGroup>
                        </Col>
                    </Row>

                    {props.credits.length > 1 &&
                        <React.Fragment>
                            <Button color="danger" onClick={() => props.removeLine(idx)}>
                                {translations.remove}
                            </Button>
                            <Button color="primary" onClick={() => props.addLine(idx)}>
                                {translations.add}
                            </Button>
                        </React.Fragment>
                    }
                </div>
            )
        })
    )
}
export default CreditLineInputs
