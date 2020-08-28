import React, { Component } from 'react'
import { ListGroup, Row } from 'reactstrap'
import { icons } from '../../common/_icons'
import { translations } from '../../common/_translations'
import SectionItem from '../../common/entityContainers/SectionItem'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import PaymentModel from '../../models/PaymentModel'

export default class Gateway extends Component {
    constructor (props) {
        super(props)

        this.state = {
            payments: []
        }

        this.getPayments = this.getPayments.bind(this)
    }

    componentDidMount () {
        this.getPayments()
    }

    getPayments () {
        const paymentModel = new PaymentModel()
        paymentModel.getPayments().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ payments: response }, () => {
                console.log('payements', this.state.payments)
            })
        })
    }

    render () {
        const payments = this.state.payments.length ? this.state.payments.filter(payment => payment.company_gateway_id === parseInt(this.props.entity.id)) : []
        const sumValues = payments.length ? payments.map(item => item.amount).reduce((prev, next) => prev + next) : 0

        return (
            <React.Fragment>
                <ViewEntityHeader heading_1={translations.processed} value_1={sumValues}/>

                <Row>
                    <ListGroup className="col-12">
                        <SectionItem link={`/#/customers?group_settings_id=${this.props.entity.id}`}
                            icon={icons.customer} title={translations.customers}/>
                        <SectionItem value={payments.length} link={`/#/payments?gateway_id=${this.props.entity.id}`}
                            icon={icons.credit_card} title={`${translations.payments} ${payments.length}`}/>
                    </ListGroup>
                </Row>

            </React.Fragment>

        )
    }
}
