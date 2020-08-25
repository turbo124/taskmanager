import React from 'react'
import { ListGroup, Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../common/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../common/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import PaymentPresenter from '../../presenters/PaymentPresenter'
import Paymentables from './Paymentables'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.amount} value_1={props.entity.amount}
            heading_2={translations.applied} value_2={props.entity.applied}/>

        <PaymentPresenter entity={props.entity} field="status_field"/>

        <Paymentables paymentableInvoices={props.paymentableInvoices} paymentableCredits={props.paymentableCredits}/>

        <Row>
            <ListGroup className="col-12 mt-2">
                {props.gateway}
            </ListGroup>
        </Row>

        {!!props.entity.private_notes.length &&
        <Row>
            <InfoMessage message={props.entity.private_notes}/>
        </Row>
        }

        <Row>
            <EntityListTile entity={translations.customer} title={props.customer[0].name}
                icon={icons.customer}/>
        </Row>

        {!!props.user &&
        <Row>
            {props.user}
        </Row>
        }

        <FieldGrid fields={props.fields}/>
    </React.Fragment>
}
