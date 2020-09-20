import React from 'react'
import { Col, FormGroup, Input, Label, Row } from 'reactstrap'
import InvoiceDropdown from '../../common/dropdowns/InvoiceDropdown'
import { translations } from '../../utils/_translations'

const InvoiceLineInputs = (props) => {
    return (
        props.lines.map((val, idx) => {
            let amount = props.lines[idx].amount

            if(props.invoices && props.invoices.length === 1 && && props.lines[idx].amount === 0) {
                let paymentTotal = 0	
	        let creditTotal = 0;
	        props.invoices.forEach((invoice) {
	            paymentTotal += invoice.total
	        })

	        creditPaymentables.forEach((credit) {
	            creditTotal += credit.amount
	        })

                if (paymentTotal !== 0) {
                    if (creditTotal == 0) {
	                amount = paymentTotal;
	            } else {
	                amount = paymentTotal - creditTotal
	            }
                }
            }

            return (
                <div key={idx}>
                    <Row form>
                        <Col md={6}>
                            <FormGroup>
                                <Label for="exampleEmail">{translations.invoice}</Label>
                                <InvoiceDropdown
                                    allowed_invoices={props.allowed_invoices}
                                    customer={props.payment.customer_id}
                                    invoices={props.invoices}
                                    data_id={idx}
                                    invoice_id={props.lines[idx].invoice_id}
                                    status={props.status}
                                    handleInputChanges={props.onChange}
                                    name="invoice_id"
                                    errors={props.errors}
                                />
                            </FormGroup>
                        </Col>
                        <Col md={6}>
                            <FormGroup>
                                <Label for="examplePassword">{translations.amount}</Label>
                                <Input type="text"
                                    data-invoice={props.invoices.length === 1 ? props.invoices[0].id : 'test'}
                                    data-id={idx}
                                    onChange={props.onChange}
                                    value={amount}
                                    autoFocus={(props.invoices && props.invoices.length === 1) || idx === 0}
                                    name="amount"
                                />
                            </FormGroup>
                        </Col>
                    </Row>

                    {/* {props.invoices.length > 1 && */}
                    {/*    <React.Fragment> */}
                    {/*        <Button color="danger" onClick={() => props.removeLine(idx)}> */}
                    {/*            {translations.remove} */}
                    {/*        </Button> */}
                    {/*        <Button color="primary" onClick={() => props.addLine(idx)}> */}
                    {/*            {translations.add} */}
                    {/*        </Button> */}
                    {/*    </React.Fragment> */}
                    {/* } */}
                </div>
            )
        })
    )
}
export default InvoiceLineInputs
