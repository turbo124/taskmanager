import React from 'react'
import { ListGroup, ListGroupItem, ListGroupItemHeading, ListGroupItemText, Row, TabPane } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../common/_translations'
import CasePresenter from '../../presenters/CasePresenter'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../common/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import ExpensePresenter from '../../presenters/ExpensePresenter'
import InvoicePresenter from '../../presenters/InvoicePresenter'
import FormatMoney from '../../common/FormatMoney'
import LineItem from '../../common/entityContainers/LineItem'
import TotalsBox from '../../common/entityContainers/TotalsBox'
import InfoItem from '../../common/entityContainers/InfoItem'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.valued_at}
            value_1={<FormatMoney amount={props.entity.valued_at}/>}/>

        <Row>
            <ListGroup className="col-12">
                <InfoItem icon={icons.user}
                    value={`${props.entity.first_name} ${props.entity.last_name}`}
                    title={translations.full_name}/>

                <InfoItem icon={icons.envelope} value={props.entity.email}
                    title={translations.email}/>

                <InfoItem icon={icons.phone} value={props.entity.phone}
                    title={translations.phone_number}/>

                <InfoItem icon={icons.link} value={props.entity.website}
                    title={translations.website}/>

                <InfoItem icon={icons.building} value={props.entity.vat_number}
                    title={translations.vat_number}/>

                <InfoItem icon={icons.list} value={props.entity.number}
                    title={translations.number}/>

                <InfoItem icon={icons.map_marker} value={props.address}
                    title={translations.billing_address}/>

            </ListGroup>
        </Row>
    </React.Fragment>
}
