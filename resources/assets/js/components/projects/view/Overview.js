import React from 'react'
import { ListGroup, Row } from 'reactstrap'
import { translations } from '../../utils/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import PlainEntityHeader from '../../common/entityContainers/PlanEntityHeader'
import FormatMoney from '../../common/FormatMoney'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'

export default function Overview (props) {
    const modules = JSON.parse(localStorage.getItem('modules'))

    return <React.Fragment>
         <PlainEntityHeader heading_1={translations.total} value_1={formatDuration(props.total)}
                    heading_2={translations.budgeted} value_2={props.entity.budgeted_hours}/>

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

        {!!props.invoice &&
        <Row>
            {props.invoice}
        </Row>
        }

         <Row>
                    <ListGroup className="col-12 mt-2 mb-2">
                        {!!props.entity.tasks && props.entity.tasks.map((task, index) => (
                            <EntityListTile key={index} entity={translations.task} title={task.title}
                                icon={icons.task}/>
                        ))}
                    </ListGroup>
                </Row>

                {modules && modules.invoices &&
                <SectionItem link={`/#/invoice?project_id=${props.entity.id}`}
                    icon={icons.document} title={translations.invoices}/>
                }

                {modules && modules.tasks &&
                <SectionItem link={`/#/tasks?project_id=${props.entity.id}`}
                    icon={icons.document} title={translations.tasks}/>
                }

                {modules && modules.credits &&
                <SectionItem link={`/#/credits?project_id=${props.entity.id}`}
                    icon={icons.document} title={translations.credits}/>
                }

                {modules && modules.quotes &&
                <SectionItem link={`/#/quotes?project_id=${props.entity.id}`}
                    icon={icons.document} title={translations.quotes}/>
                }

                {modules && modules.recurring_invoices &&
                <SectionItem link={`/#/recurring-invoices?project_id=${props.entity.id}`}
                    icon={icons.document} title={translations.recurring_invoices}/>
                }

                {modules && modules.recurring_quotes &&
                <SectionItem link={`/#/recurring-quotes?project_id=${props.entity.id}`}
                    icon={icons.document} title={translations.recurring_quotes}/>
                }

        <FieldGrid fields={props.fields}/>

       

       
    </React.Fragment>
}
