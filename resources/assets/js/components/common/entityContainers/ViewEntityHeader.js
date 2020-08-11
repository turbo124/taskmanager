import React from 'react'
import { Card, CardBody } from 'reactstrap'
import FormatMoney from '../FormatMoney'

export default function ViewEntityHeader (props) {
    const text_color = localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true' ? 'text-white' : 'text-dark'

    return <Card body outline color="primary">
        <CardBody className={text_color}>
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
        </CardBody>
    </Card>
}
