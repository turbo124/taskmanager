import { ListGroup, ListGroupItem } from 'reactstrap'
import { translations } from '../../utils/_translations'
import React from 'react'
import FormatMoney from '../FormatMoney'

export default function TotalsBox (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <ListGroup className="col-6 mt-4">
        <ListGroupItem
            className={`${listClass} d-flex justify-content-between align-items-center`}>
            {translations.tax}
            <span><FormatMoney amount={props.entity.tax_total} customers={props.customers}/></span>
        </ListGroupItem>
        <ListGroupItem
            className={`${listClass} d-flex justify-content-between align-items-center`}>
            {translations.discount}
            <span><FormatMoney amount={props.entity.discount_total} customers={props.customers}/></span>
        </ListGroupItem>
        <ListGroupItem
            className={`${listClass} d-flex justify-content-between align-items-center`}>
            {translations.subtotal}
            <span><FormatMoney amount={props.entity.sub_total} customers={props.customers}/></span>
        </ListGroupItem>
        <ListGroupItem
            className={`${listClass} d-flex justify-content-between align-items-center`}>
            {translations.total}
            <span><FormatMoney amount={props.entity.total} customers={props.customers}/></span>
        </ListGroupItem>
    </ListGroup>
}
