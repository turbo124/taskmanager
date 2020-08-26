import React from 'react'
import { FormGroup, Input, Label } from 'reactstrap'
import CustomerDropdown from '../common/CustomerDropdown'
import { translations } from '../common/_translations'
import TaskStatusDropdown from '../common/TaskStatusDropdown'

export default function TaskDetails (props) {
    let userContent
    if (!props.users) {
        userContent = <option value="">Loading...</option>
    } else {
        userContent = props.users.map((user, index) => (
            <option key={index} value={user.id}>{user.first_name + ' ' + user.last_name}</option>
        ))
    }

    const userOptions =
        <FormGroup>
            <Label for="contributors">{translations.assigned_user}:</Label>
            <Input className={props.hasErrorFor('contributors') ? 'is-invalid' : ''} multiple
                type="select"
                value={props.task.selectedUsers}
                name="contributors" id="contributors" onChange={props.handleMultiSelect}>
                {userContent}
            </Input>
            {props.renderErrorFor('contributors')}
        </FormGroup>

    return (
        <React.Fragment>
            <FormGroup>
                <Label for="title">{translations.title}(*):</Label>
                <Input className={props.hasErrorFor('title') ? 'is-invalid' : ''} type="text" name="title"
                    value={props.task.title}
                    id="taskTitle" onChange={props.handleInput.bind(this)}/>
                {props.renderErrorFor('title')}
            </FormGroup>

            <FormGroup className="mb-3">
                <Label>{translations.customer}</Label>
                <CustomerDropdown
                    customer={props.task.customer_id}
                    renderErrorFor={props.renderErrorFor}
                    handleInputChanges={props.handleInput}
                    customers={props.customers}
                />
                {props.renderErrorFor('customer_id')}
            </FormGroup>

            <FormGroup>
                <Label for="content">{translations.description}:</Label>
                <Input className={props.hasErrorFor('content') ? 'is-invalid' : ''} type="textarea"
                    name="content" value={props.task.content} id="content"
                    onChange={props.handleInput.bind(this)}/>
                {props.renderErrorFor('content')}
            </FormGroup>

            {userOptions}

            <FormGroup>
                <Label>{translations.status}</Label>
                <TaskStatusDropdown
                    task_type={1}
                    status={props.task.task_status}
                    handleInputChanges={props.handleInput}
                />
            </FormGroup>
        </React.Fragment>
    )
}
