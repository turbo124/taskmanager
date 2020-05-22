import React, { Component } from 'react'
import {
    Row,
    Card,
    CardText
} from 'reactstrap'
import FormatMoney from './FormatMoney'

export default class Company extends Component {
    render () {
        return (
            <Card body outline color="danger">
                <CardText className="text-white">
                    <div className="d-flex">
                        <div
                            className="p-2 flex-fill">
                            <h4>Paid to Date</h4>
                            {<FormatMoney
                                amount={this.props.entity.paid_to_date}/>}
                        </div>

                        <div
                            className="p-2 flex-fill">
                            <h4>Balance</h4>
                            {<FormatMoney
                                amount={this.props.entity.balance} />}
                        </div>
                    </div>

                    <Row>
                          <ListGroup>
         {this.props.entity.line_items.map((line_item, index) => (
      <ListGroupItem>
        <ListGroupItemHeading> 
            {this.props.entity.first_name} {this.props.entity.last_name} <br />
            {this.props.entity.email}
        </ListGroupItemHeading>
        <ListGroupItemText>
         Email
        </ListGroupItemText>
      </ListGroupItem>
       <ListGroupItem>
        <ListGroupItemHeading> 
            {this.props.entity.first_name} {this.props.entity.last_name} <br />
            {this.props.entity.phone}
        </ListGroupItemHeading>
        <ListGroupItemText>
         Phone
        </ListGroupItemText>
      </ListGroupItem>
     ))}

       <ListGroupItem>
        <ListGroupItemHeading> {this.props.entity.website}</ListGroupItemHeading>
        <ListGroupItemText>
         Website
        </ListGroupItemText>
      </ListGroupItem>

       <ListGroupItem>
        <ListGroupItemHeading> {this.props.entity.vat_number}</ListGroupItemHeading>
        <ListGroupItemText>
         Vat Number
        </ListGroupItemText>
      </ListGroupItem>

       <ListGroupItem>
        <ListGroupItemHeading> {this.props.entity.number}</ListGroupItemHeading>
        <ListGroupItemText>
         Number
        </ListGroupItemText>
      </ListGroupItem>

      <ListGroupItem>
        <ListGroupItemHeading> {this.props.entity.vat_number}</ListGroupItemHeading>
        <ListGroupItemText>
         Billing Address 
        </ListGroupItemText>
      </ListGroupItem>

       <ListGroupItem>
        <ListGroupItemHeading> {this.props.entity.vat_number}</ListGroupItemHeading>
        <ListGroupItemText>
         Shipping Address
        </ListGroupItemText>
      </ListGroupItem>
    </ListGroup>
                    </Row>
                </CardText>
            </Card>
        )
    }
}
