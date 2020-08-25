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

export default function Paymentable (props) {
    console.log('line item', props.line_item)
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <a className="mb-2" href={props.link}>
        <ListGroupItem className={listClass}>
            <ListGroupItemHeading>
                <i className={`fa ${icons.document} mr-4`}/> {props.entity} > {props.line_item.number}

            </ListGroupItemHeading>

            <ListGroupItemText>
                <FormatMoney amount={props.line_item.amount}/> - <FormatDate
                    date={props.line_item.date}/>
            </ListGroupItemText>
        </ListGroupItem>
    </a>
}
