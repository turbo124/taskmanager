import React from 'react'
import { ListGroup, Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../utils/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import { getEntityIcon, icons } from '../../utils/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import SectionItem from '../../common/entityContainers/SectionItem'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
    const modules = JSON.parse(localStorage.getItem('modules'))

    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.paid_to_date} value_1={props.entity.paid_to_date}
            heading_2={translations.balance} value_2={props.entity.balance}/>

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

                {modules && modules.projects &&
                <SectionItem link={`/#/projects?customer_id=${props.entity.id}`}
                    icon={icons.document} title={translations.projects}/>
                }

                {modules && modules.credits &&
                <SectionItem link={`/#/credits?customer_id=${props.entity.id}`}
                    icon={icons.document} title={translations.credits}/>
                }

                {modules && modules.quotes &&
                <SectionItem link={`/#/quotes?customer_id=${props.entity.id}`}
                    icon={icons.document} title={translations.quotes}/>
                }

                {modules && modules.recurring_invoices &&
                <SectionItem link={`/#/recurring-invoices?customer_id=${props.entity.id}`}
                    icon={icons.document} title={translations.recurring_invoices}/>
                }

                {modules && modules.recurring_quotes &&
                <SectionItem link={`/#/recurring-quotes?customer_id=${props.entity.id}`}
                    icon={icons.document} title={translations.recurring_quotes}/>
                }

                {modules && modules.payments &&
                <SectionItem link={`/#/payments?customer_id=${props.entity.id}`}
                    icon={icons.credit_card} title={translations.payments}/>
                }

                {modules && modules.tasks &&
                <SectionItem link={`/#/tasks?customer_id=${props.entity.id}`} icon={icons.task}
                    title={translations.tasks}/>
                }

                {modules && modules.deals &&
                <SectionItem link={`/#/deals?customer_id=${props.entity.id}`} icon={getEntityIcon('Deal')}
                    title={translations.deals}/>
                }

                {modules && modules.leads &&
                <SectionItem link={`/#/leads?customer_id=${props.entity.id}`} icon={getEntityIcon('Lead')}
                    title={translations.leads}/>
                }

                {modules && modules.expenses &&
                <SectionItem link={`/#/expenses?customer_id=${props.entity.id}`}
                    icon={getEntityIcon('Expense')} title={translations.expenses}/>
                }

                {modules && modules.orders &&
                <SectionItem link={`/#/orders?customer_id=${props.entity.id}`} icon={getEntityIcon('Order')}
                    title={translations.orders}/>
                }

            </ListGroup>
        </Row>
    </React.Fragment>
}
