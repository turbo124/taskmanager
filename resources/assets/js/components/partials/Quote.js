import React, { Component } from 'react'
import FileUploads from '../attachments/FileUploads'
import {
    TabContent, 
    TabPane,
    Nav,
    NavItem, 
    NavLink,
    Row,
    Card,
    CardText
} from 'reactstrap'
import FormatMoney from './FormatMoney'
import QuotePresenter from '../presenters/QuotePresenter'

export default class Quote extends Component {
    render () {
        return (
            <React.Fragment>

  <Nav tabs>
        <NavItem>
          <NavLink
            className={classnames({ active: activeTab === '1' })}
            onClick={() => { this.toggleTab('1'); }}
          >
            Details
          </NavLink>
        </NavItem>
        <NavItem>
          <NavLink
            className={classnames({ active: activeTab === '2' })}
            onClick={() => { this.toggleTab('2'); }}
          >
            Documents
          </NavLink>
        </NavItem>
      </Nav>
      <TabContent activeTab={this.state.activeTab}>
        <TabPane tabId="1">
                <Card body outline color="primary">
                    <CardText className="text-white">
                     <div className="d-flex">
                            <div
                                className="p-2 flex-fill">
                                <h4>Total</h4>
                                {<FormatMoney
                                    amount={this.props.entity.total}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4>Balance</h4>
                                {<FormatMoney
                                    amount={this.props.entity.balance} />}
                            </div>
                        </div>
                   
                <QuotePresenter entity={this.props.entity} field="status_field" />

             <Row>
                                <ListGroup className="mt-4">
                                    <ListGroupItem className="list-group-item-dark">
                                        <ListGroupItemHeading><i
                                            className="fa fa-user-circle-o mr-2"/>{this.props.entity.customer_name}
                                        </ListGroupItemHeading>
                                    </ListGroupItem>
                                </ListGroup>
                                <ul className="mt-4">
                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>Invoice Date</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            <FormatDate date={this.props.entity.date}/>
                                        </ListGroupItemText>
                                    </ListGroupItem>

                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>Due Date</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {this.props.entity.due_date}
                                        </ListGroupItemText>
                                    </ListGroupItem>

                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>PO Number</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            <FormatDate date={this.props.entity.po_number}/>
                                        </ListGroupItemText>
                                    </ListGroupItem>

                                    <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
                                        <ListGroupItemHeading>Discount</ListGroupItemHeading>
                                        <ListGroupItemText>
                                            {this.props.entity.discount_total}
                                        </ListGroupItemText>
                                    </ListGroupItem>
                                </ul>
                            </Row>

                            <Row>
                                <ListGroup className="col-12 mt-4">
                                    {this.props.entity.line_items.map((line_item, index) => (
                                        <ListGroupItem className="list-group-item-dark">
                                            <ListGroupItemHeading
                                                className="d-flex justify-content-between align-items-center">
                                                {line_item.product_id}
                                                <span>{line_item.sub_total}</span>
                                            </ListGroupItemHeading>
                                            <ListGroupItemText>
                                                {line_item.quantity} x {line_item.unit_price} Discount: {line_item.unit_discount} Tax: {line_item.unit_tax}
                                                <br/>
                                                {line_item.description}
                                            </ListGroupItemText>
                                        </ListGroupItem>
                                    ))}
                                </ListGroup>
                            </Row>

                            <Row className="justify-content-end">
                                <ListGroup className="col-6 mt-4">
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Tax
                                        <span>{this.props.entity.tax_total}</span>
                                    </ListGroupItem>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Discount
                                        <span> {this.props.entity.discount_total}</span>
                                    </ListGroupItem>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Subtotal
                                        <span> {this.props.entity.sub_total} </span>
                                    </ListGroupItem>
                                    <ListGroupItem
                                        className="list-group-item-dark d-flex justify-content-between align-items-center">
                                        Total
                                        <span> {this.props.entity.total} </span>
                                    </ListGroupItem>
                                </ListGroup>
                            </Row>
                    </CardText>
                </Card>

       
        </TabPane>
        <TabPane tabId="2">
          <Row>
            <Col sm="6">
              <Card body>
                <CardTitle>Special Title Treatment</CardTitle>
                 <CardText>
                      <FileUploads entity_type="Quote" entity={this.props.entity}
                    user_id={this.props.entity.user_id}/>
                </CardText>
              </Card>
            </Col>
            <Col sm="6">
              <Card body>
                <CardTitle>Special Title Treatment</CardTitle>
                <CardText>With supporting text below as a natural lead-in to additional content.</CardText>
                <Button>Go somewhere</Button>
              </Card>
            </Col>
          </Row>
        </TabPane>
      </TabContent>

      <div class="navbar">
          <a href="#home" class="active">Home</a>
          <a href="#news">News</a>
      </div>
                     
            </React.Fragment>

        )
    }
}
