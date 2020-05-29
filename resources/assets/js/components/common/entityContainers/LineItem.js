import { ListGroupItem, ListGroupItemHeading, ListGroupItemText } from 'reactstrap'
import { translations } from '../_icons'
import React from 'react'
import FormatMoney from '../FormatMoney'

export default function LineItem (props) {
    return <ListGroupItem className="list-group-item-dark">
        <ListGroupItemHeading
            className="d-flex justify-content-between align-items-center">
            {props.line_item.product_id}
            <span><FormatMoney amount={props.line_item.sub_total} customers={props.customers}/></span>
        </ListGroupItemHeading>
        <ListGroupItemText>
            {props.line_item.quantity} x <FormatMoney amount={props.line_item.unit_price}
                customers={props.customers}/> {translations.discount}: <FormatMoney
                amount={props.line_item.unit_discount} customers={props.customers}/> {translations.tax}: <FormatMoney
                amount={props.line_item.unit_tax} customers={props.customers}/>
            <br/>
            {props.line_item.description}
        </ListGroupItemText>
    </ListGroupItem>
}
