import React from 'react'
import { ListGroup, Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../common/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import { icons } from '../../common/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import SectionItem from '../../common/entityContainers/SectionItem'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.paid_to_date} value_1={props.entity.paid_to_date}
            heading_2={translations.balance} value_2={props.entity.balance}/>

        {props.entity.private_notes.length &&
        <Row>
            <InfoMessage message={props.entity.private_notes}/>
        </Row>
        }

        <Row>
            {props.user}
        </Row>

        <FieldGrid fields={props.fields}/>

        <Row>
            <ListGroup className="col-12">
                <SectionItem link={`/#/expenses?company_id=${props.entity.id}`}
                    icon={icons.expense} title={translations.expenses}/>
            </ListGroup>
        </Row>
    </React.Fragment>
}
