import React from 'react'
import { Alert, Row } from 'reactstrap'
import ViewEntityHeader from '../../common/entityContainers/ViewEntityHeader'
import { translations } from '../../utils/_translations'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'
import FieldGrid from '../../common/entityContainers/FieldGrid'
import FormatMoney from '../../common/FormatMoney'

export default function Overview (props) {
    return <React.Fragment>
        <ViewEntityHeader heading_1={translations.valued_at}
            value_1={<FormatMoney amount={props.entity.valued_at}/>}/>

        {props.entity.name.length &&
        <Alert color="dark col-12 mt-2">
            {props.entity.name}
        </Alert>
        }

        {props.entity.private_notes.length &&
        <Alert color="dark col-12 mt-2">
            {props.entity.private_notes}
        </Alert>
        }

        {props.entity.public_notes.length &&
        <Alert color="dark col-12 mt-2">
            {props.entity.public_notes}
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

        {!!props.project &&
        <Row>
            {props.project}
        </Row>
        }

        <FieldGrid fields={props.fields}/>
    </React.Fragment>
}
