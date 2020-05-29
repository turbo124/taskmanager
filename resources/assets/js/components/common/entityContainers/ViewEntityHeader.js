import React from 'react'
import { Card, CardText } from 'reactstrap'
import FormatMoney from '../FormatMoney'

export default function ViewEntityHeader (props) {
    return <Card body outline color="primary">
        <CardText className="text-white">
            <div className="d-flex">
                <div
                    className="p-2 flex-fill">
                    <h4 className="text-muted">{props.heading_1}</h4>
                    {<FormatMoney className="text-value-lg"
                        amount={props.value_1}/>}
                </div>

                <div
                    className="p-2 flex-fill">
                    <h4 className="text-muted">{props.heading_2}</h4>
                    {<FormatMoney className="text-value-lg"
                        amount={props.value_2}/>}
                </div>
            </div>
        </CardText>
    </Card>
}
