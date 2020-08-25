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
import QuotePresenter from '../../presenters/QuotePresenter'
import RecurringInvoicePresenter from '../../presenters/RecurringInvoicePresenter'
import RecurringQuotePresenter from '../../presenters/RecurringQuotePresenter'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.duration} value_1={props.entity.duration}
            heading_2={translations.amount}
            value_2={props.calculatedAmount}/>

        {!!props.entity.title.length &&
        <Row>
            <InfoMessage message={props.entity.title}/>
        </Row>
        }

        {!!props.entity.private_notes.length &&
        <Row>
            <InfoMessage message={props.entity.private_notes}/>
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
