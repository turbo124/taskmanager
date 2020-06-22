import React from 'react'
import { Card, CardHeader, CardBody } from 'reactstrap'
import FileUploads from '../attachments/FileUploads'
import { translations } from '../common/_icons'

export default function Documents (props) {
    return (
        <Card>
            <CardHeader>{translations.documents}</CardHeader>
            <CardBody>
                <FileUploads entity_type="Payment" entity={props.payment}
                    user_id={props.payment.user_id}/>
            </CardBody>
        </Card>

    )
}
