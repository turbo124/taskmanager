import React from 'react'
import { Col, ListGroupItem, ListGroupItemHeading, ListGroupItemText } from 'reactstrap'

export default function InfoItem (props) {
    return <ListGroupItem className="list-group-item-dark">
        <Col className="p-0" sm={1}>
            <ListGroupItemHeading><i
                className={`fa ${props.icon} mr-4`}/></ListGroupItemHeading>
        </Col>

        <Col sm={11}>
            <ListGroupItemHeading>
                {props.first_value && props.first_value.length &&
                    <React.Fragment>
                        {props.first_value} <br />
                    </React.Fragment>

                }

                {props.value}</ListGroupItemHeading>
            <ListGroupItemText>
                {props.title}
            </ListGroupItemText>
        </Col>
    </ListGroupItem>
}
