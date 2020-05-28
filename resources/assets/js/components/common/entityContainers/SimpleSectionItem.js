import React from 'react'
import { ListGroupItem, ListGroupItemHeading, ListGroupItemText } from 'reactstrap'

export default function SimpleSectionItem (props) {
    return <ListGroupItem className="list-group-item-dark col-12 col-md-6 pull-left">
        <ListGroupItemHeading>{props.heading}</ListGroupItemHeading>
        <ListGroupItemText>
            {props.value}
        </ListGroupItemText>
    </ListGroupItem>
}
