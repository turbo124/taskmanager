import React from 'react'
import { FormGroup, Input, Label } from 'reactstrap'
import CustomerDropdown from '../../common/dropdowns/CustomerDropdown'
import { translations } from '../../utils/_translations'
import TaskStatusDropdown from '../../common/dropdowns/TaskStatusDropdown'
import ProjectDropdown from '../../common/dropdowns/ProjectDropdown'
import DesignDropdown from '../../common/dropdowns/DesignDropdown'

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
                <Label for="name">{translations.name}(*):</Label>
                <Input className={props.hasErrorFor('name') ? 'is-invalid' : ''} type="text" name="name"
                    value={props.task.name}
                    id="taskTitle" onChange={props.handleInput.bind(this)}/>
                {props.renderErrorFor('name')}
            </FormGroup>

            {!props.task.invoice_id &&
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
            }

            {/* <FormGroup> */}
            {/*    <Label>{translations.assigned_user}</Label> */}
            {/*    <UserDropdown handleInputChanges={props.handleInput} */}
            {/*        user_id={props.task.assigned_to} name="assigned_to" */}
            {/*        users={props.users}/> */}
            {/* </FormGroup> */}

            {!props.task.invoice_id &&
            <FormGroup>
                <Label>{translations.project}</Label>
                <ProjectDropdown handleInputChanges={props.handleInput} customer-id={props.task.customer_id}
                    project={props.task.project_id} name="project_id"
                />
            </FormGroup>
            }

            <FormGroup>
                <Label for="description">{translations.description}:</Label>
                <Input className={props.hasErrorFor('description') ? 'is-invalid' : ''} type="textarea"
                    name="description" value={props.task.description} id="description"
                    onChange={props.handleInput.bind(this)}/>
                {props.renderErrorFor('description')}
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

            <FormGroup>
                <Label>{translations.design}</Label>
                <DesignDropdown name="design_id" design={props.task.design_id} handleChange={props.handleInput}/>
            </FormGroup>
        </React.Fragment>
    )
}
