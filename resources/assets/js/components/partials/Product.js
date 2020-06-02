import React, { Component } from 'react'
import {
    Row,
    ListGroup
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import InfoItem from '../common/entityContainers/InfoItem'
import FormatDate from '../common/FormatDate'
import FormatMoney from '../common/FormatMoney'

export default class Product extends Component {
    render () {
        return (
            <React.Fragment>
                <ViewEntityHeader heading_1={translations.cost} value_1={this.props.entity.cost}
                    heading_2={translations.price} value_2={this.props.entity.price}/>

                <Row>
                    <ListGroup className="col-12">
                        <InfoItem icon={icons.credit_card} value={<FormatMoney amount={this.props.entity.price}/>}
                            title={translations.price}/>
                        <InfoItem icon={icons.credit_card} value={<FormatMoney amount={this.props.entity.cost}/>}
                            title={translations.cost}/>
                        <InfoItem icon={icons.building} value={this.props.entity.name}
                            title={translations.name}/>
                        <InfoItem icon={icons.building} value={this.props.entity.description}
                            title={translations.description}/>
                        <InfoItem icon={icons.building} value={this.props.entity.sku}
                            title={translations.sku}/>
                        <InfoItem icon={icons.list} value={this.props.entity.quantity}
                            title={translations.quantity}/>
                    </ListGroup>
                </Row>

            </React.Fragment>

        )
    }
}
