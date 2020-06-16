import React from 'react'
import { Card, CardText } from 'reactstrap'

export default function PlainEntityHeader (props) {
    return <Card body outline color="primary">
        <CardText className="text-white">
            <div className="d-flex">
                <div
                    className="p-2 flex-fill">
                    <h4 className="text-muted">{props.heading_1}</h4>
                    <span className="text-value-lg">{props.value_1} </span>
                </div>

                <div
                    className="p-2 flex-fill">
                    <h4 className="text-muted">{props.heading_2}</h4>
                    <span className="text-value-lg">{props.value_2}</span>
                </div>
            </div>
        </CardText>
    </Card>
}
