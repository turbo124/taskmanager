import React from 'react'
import { ListGroupItem, ListGroupItemHeading, ListGroupItemText } from 'reactstrap'

export default function SimpleSectionItem (props) {
    const listClass = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'list-group-item-dark' : ''

    return <ListGroupItem className={`${listClass} col-12 col-md-6 pull-left`}>
        <ListGroupItemHeading>{props.heading}</ListGroupItemHeading>
        <ListGroupItemText>
            {props.value}
        </ListGroupItemText>
    </ListGroupItem>
}
