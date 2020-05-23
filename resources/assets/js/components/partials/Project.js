import React, { Component } from 'react'
import {
    Card,
    CardText
} from 'reactstrap'
import FormatMoney from '../common/FormatMoney'
import { translations } from '../common/_icons'

export default class Project extends Component {
    render () {
        return (
            <React.Fragment>
                <Card body outline color="success">
                    <CardText className="text-white">
                        <div className="d-flex">
                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted"> {translations.total} </h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={0}/>}
                            </div>

                            <div
                                className="p-2 flex-fill">
                                <h4 className="text-muted"> {translations.budgeted} </h4>
                                {<FormatMoney className="text-value-lg"
                                    amount={this.props.entity.budgeted_hours}/>}
                            </div>
                        </div>
                    </CardText>
                </Card>
            </React.Fragment>

        )
    }
}
