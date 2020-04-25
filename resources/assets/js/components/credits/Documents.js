import React from 'react'
import { Card, CardHeader, CardBody } from 'reactstrap'
import FileUploads from '../attachments/FileUploads'

export default function Documents (props) {
    return (
        <Card>
            <CardHeader>Documents</CardHeader>
            <CardBody>
                <FileUploads entity_type="Credit" entity={props.credit}
                    user_id={props.credit.user_id}/>
            </CardBody>
        </Card>

    )
}
