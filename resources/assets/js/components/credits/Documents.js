import React from 'react'
import { Card, CardBody, CardHeader } from 'reactstrap'
import FileUploads from '../attachments/FileUploads'
import { translations } from '../common/_translations'

export default function Documents (props) {
    return (
        <Card>
            <CardHeader>{translations.documents}</CardHeader>
            <CardBody>
                <FileUploads entity_type="Credit" entity={props.credit}
                    user_id={props.credit.user_id}/>
            </CardBody>
        </Card>

    )
}
