import React, { Component } from 'react'
import {
    Nav,
    NavItem,
    NavLink,
    TabContent,
    TabPane,
    Row,
    ListGroup
} from 'reactstrap'
import { icons } from '../common/_icons'
import { translations } from '../common/_icons'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import InfoItem from '../common/entityContainers/InfoItem'
import FormatMoney from '../common/FormatMoney'

export default class Product extends Component {
    constructor (props) {
        super(props)

        this.state = {
            activeTab: '1',
            show_success: false
        }

        // this.triggerAction = this.triggerAction.bind(this)
        this.toggleTab = this.toggleTab.bind(this)
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab }, () => {
                if (this.state.activeTab === '3') {
                    this.loadPdf()
                }
            })
        }
    }

    render () {
        console.log('attributes', this.props.entity.attributes)

        const variations = this.props.entity.attributes.length ? this.props.entity.attributes.map((attribute, index) => {
            const values = attribute.values.length ? Array.prototype.map.call(attribute.values, function (value) {
                return value.value
            }).join(',') : null

            return <ListGroup className="col-12 mt-2">
                <InfoItem icon={icons.credit_card}
                    value={<FormatMoney amount={attribute.price}/>} title={translations.price}/>
                <InfoItem icon={icons.credit_card}
                    value={`${attribute.cost}`} title={translations.cost}/>
                <InfoItem icon={icons.list}
                    value={`${attribute.quantity}`} title={translations.quantity}/>
                <InfoItem icon={icons.list}
                    value={`${values}`} title={translations.variations}/>
            </ListGroup>
        }) : null

        return (
            <React.Fragment>
                <Nav tabs>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '1' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('1')
                            }}
                        >
                            {translations.overview}
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}
                        >
                            {translations.variations}
                        </NavLink>
                    </NavItem>
                </Nav>

                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <ViewEntityHeader heading_1={translations.cost} value_1={this.props.entity.cost}
                            heading_2={translations.price} value_2={this.props.entity.price}/>

                        <Row>
                            <ListGroup className="col-12">
                                <InfoItem icon={icons.credit_card}
                                    value={<FormatMoney amount={this.props.entity.price}/>}
                                    title={translations.price}/>
                                <InfoItem icon={icons.credit_card}
                                    value={<FormatMoney amount={this.props.entity.cost}/>}
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
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            {variations}
                        </Row>
                    </TabPane>
                </TabContent>
            </React.Fragment>

        )
    }
}
