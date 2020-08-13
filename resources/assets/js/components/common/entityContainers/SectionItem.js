import React from 'react'
import { ListGroupItem, ListGroupItemHeading } from 'reactstrap'
import { icons } from '../_icons'

export default function SectionItem (props) {
    const listClass = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'list-group-item-dark' : ''

    return <a className="mb-2" href={props.link}>
        <ListGroupItem
            className={`${listClass} d-flex justify-content-between align-items-center`}>
            <ListGroupItemHeading><i style={{ fontSize: '24px' }}
                className={`fa ${props.icon} mr-4`}/>{props.title}
            </ListGroupItemHeading> <i className={`fa ${icons.right}`}/>
        </ListGroupItem>
    </a>
}
