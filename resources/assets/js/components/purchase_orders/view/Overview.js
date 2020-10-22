import React from 'react'
import { ListGroup, Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../utils/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import LineItem from '../../common/entityContainers/LineItem'
import TotalsBox from '../../common/entityContainers/TotalsBox'
import PurchaseOrderPresenter from '../../presenters/PurchaseOrderPresenter'

export default function Overview (props) {
    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.total} value_1={props.entity.total}
            heading_2={translations.balance} value_2={props.entity.balance}/>

        <PurchaseOrderPresenter entity={props.entity} field="status_field"/>

        {!!props.entity.private_notes.length &&
        <Row>
            <InfoMessage icon={icons.lock} message={props.entity.private_notes}/>
        </Row>
        }

        {!!props.entity.public_notes.length &&
        <Row>
            <InfoMessage message={props.entity.public_notes}/>
        </Row>
        }

        <Row>
            <EntityListTile entity={translations.company} title={props.company[0].name}
                icon={icons.company}/>
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
                    <LineItem customers={props.companies} key={index} line_item={line_item}/>
                ))}
            </ListGroup>
        </Row>

        <Row className="justify-content-end">
            <TotalsBox customers={props.companies} entity={props.entity}/>
        </Row>
    </React.Fragment>
}
