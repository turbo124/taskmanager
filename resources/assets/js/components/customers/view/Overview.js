import React from 'react'
import { ListGroup, ListGroupItem, ListGroupItemHeading, Row, TabPane } from 'reactstrap'
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

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
    const modules = JSON.parse(localStorage.getItem('modules'))

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
            <ListGroup className="col-12 mb-2">
                {props.gateway_tokens}
            </ListGroup>

            <ListGroup className="col-12">
                {modules && modules.invoices &&
                <SectionItem link={`/#/invoice?customer_id=${props.entity.id}`}
                    icon={icons.document} title={translations.invoices}/>
                }

                {modules && modules.payments &&
                <SectionItem link={`/#/payments?customer_id=${props.entity.id}`}
                    icon={icons.credit_card} title={translations.payments}/>
                }

                {modules && modules.invoices &&
                <SectionItem link={`/#/projects?customer_id=${props.entity.id}`}
                    icon={icons.project} title={translations.projects}/>

                }

                {modules && modules.tasks &&
                <SectionItem link={`/#/tasks?customer_id=${props.entity.id}`} icon={icons.task}
                    title={translations.tasks}/>
                }

                {modules && modules.expenses &&
                <SectionItem link={`/#/expenses?customer_id=${props.entity.id}`}
                    icon={icons.expense} title={translations.expenses}/>
                }

                {modules && modules.orders &&
                <SectionItem link={`/#/orders?customer_id=${props.entity.id}`} icon={icons.order}
                    title={translations.orders}/>
                }

            </ListGroup>
        </Row>
    </React.Fragment>
}
