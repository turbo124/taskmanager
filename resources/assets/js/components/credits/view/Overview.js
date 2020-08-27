import React from 'react'
import { ListGroup, Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../common/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../common/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import CreditPresenter from '../../presenters/CreditPresenter'
import LineItem from '../../common/entityContainers/LineItem'
import TotalsBox from '../../common/entityContainers/TotalsBox'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.total} value_1={props.entity.total}
            heading_2={translations.credit_remaining}
            value_2={props.entity.balance}/>

        <CreditPresenter entity={props.entity} field="status_field"/>

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

        <Row>
            <ListGroup className="col-12 mt-4">
                {props.entity.line_items.map((line_item, index) => (
                    <LineItem customers={props.customers} key={index} line_item={line_item}/>
                ))}
            </ListGroup>
        </Row>

        <Row className="justify-content-end">
            <TotalsBox customers={props.customers} entity={props.entity}/>
        </Row>
    </React.Fragment>
}
