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
import PaymentPresenter from '../../presenters/PaymentPresenter'
import FormatDate from '../../common/FormatDate'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.cost} value_1={props.entity.cost}
            heading_2={translations.price} value_2={props.entity.price}/>

        <Row>
            <ListGroup className="col-12">
                <InfoItem icon={icons.credit_card}
                    value={<FormatMoney amount={props.entity.price}/>}
                    title={translations.price}/>
                <InfoItem icon={icons.credit_card}
                    value={<FormatMoney amount={props.entity.cost}/>}
                    title={translations.cost}/>
                <InfoItem icon={icons.building} value={props.entity.name}
                    title={translations.name}/>
                <InfoItem icon={icons.building} value={props.entity.description}
                    title={translations.description}/>
                <InfoItem icon={icons.building} value={props.entity.sku}
                    title={translations.sku}/>
                <InfoItem icon={icons.list} value={props.entity.quantity}
                    title={translations.quantity}/>
            </ListGroup>
        </Row>
    </React.Fragment>
}
