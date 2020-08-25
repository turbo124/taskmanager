import React from 'react'
import { Alert, ListGroup, ListGroupItem, ListGroupItemHeading, Row, TabPane } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../common/_translations'
import CasePresenter from '../../presenters/CasePresenter'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../common/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import CreditPresenter from '../../presenters/CreditPresenter'
import LineItem from '../../common/entityContainers/LineItem'
import TotalsBox from '../../common/entityContainers/TotalsBox'
import SectionItem from '../../common/entityContainers/SectionItem'
import FormatMoney from '../../common/FormatMoney'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
    const modules = JSON.parse(localStorage.getItem('modules'))

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.valued_at}
            value_1={<FormatMoney amount={props.entity.valued_at}/>}/>

        {props.entity.title.length &&
        <Alert color="dark col-12 mt-2">
            {props.entity.title}
        </Alert>
        }

        {props.entity.private_notes.length &&
        <Alert color="dark col-12 mt-2">
            {props.entity.private_notes}
        </Alert>
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
