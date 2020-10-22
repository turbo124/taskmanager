import React from 'react'
import { Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../utils/_translations'
import InfoMessage from '../../common/entityContainers/InfoMessage'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import ExpensePresenter from '../../presenters/ExpensePresenter'

export default function Overview (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

    const header = props.model.isConverted
        ? <ViewEntityHeader heading_1={translations.amount} value_1={props.model.amountWithTax}
            heading_2={translations.converted} value_2={props.model.convertedAmountWithTax}/>
        : <ViewEntityHeader heading_1={translations.amount} value_1={props.model.amountWithTax} />

    return <React.Fragment>
        {header}

        <ExpensePresenter entity={props.entity} field="status_field"/>

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
            <EntityListTile entity={translations.customer} title={props.customer[0].name}
                icon={icons.customer}/>
        </Row>

        {!!props.user &&
        <Row>
            {props.user}
        </Row>
        }

        <FieldGrid fields={props.fields}/>

        {!!Object.keys(props.recurring).length &&
        <div>
            <h5>{translations.recurring}</h5>
            <FieldGrid fields={props.recurring}/>
        </div>
        }
    </React.Fragment>
}
