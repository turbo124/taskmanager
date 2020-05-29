import React from 'react'
import { ListGroupItem, ListGroupItemHeading } from 'reactstrap'
import { icons } from '../_icons'

export default function SectionItem (props) {
    return <a href={props.link}>
        <ListGroupItem
            className="list-group-item-dark d-flex justify-content-between align-items-center">
            <ListGroupItemHeading><i style={{ fontSize: '24px' }}
                className={`fa ${props.icon} mr-4`}/>{props.title}
            </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
        </ListGroupItem>
    </a>
}
