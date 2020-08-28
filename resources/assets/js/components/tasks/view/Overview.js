import React from 'react'
import { ListGroup, Row } from 'reactstrap'
import { translations } from '../../common/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import PlainEntityHeader from '../../common/entityContainers/PlanEntityHeader'
import FormatMoney from '../../common/FormatMoney'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../common/_icons'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <React.Fragment>
        <PlainEntityHeader heading_1={translations.duration} value_1={props.totalDuration}
            heading_2={translations.amount}
            value_2={<FormatMoney amount={props.calculatedAmount} customers={props.customers}/>}/>

        {!!props.entity.name.length &&
        <Row>
            <InfoMessage message={props.entity.name}/>
        </Row>
        }

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
            <ListGroup className="col-12">
                {props.task_times}
            </ListGroup>
        </Row>
    </React.Fragment>
}
