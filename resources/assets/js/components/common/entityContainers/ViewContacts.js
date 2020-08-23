import React from 'react'
import { ListGroup, ListGroupItem, ListGroupItemHeading } from 'reactstrap'

export default function ViewContacts (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
    const customer = props.customers && props.customers.length ? props.customers.filter(customer => customer.id === props.entity.customer_id) : []
        const contacts = customer.length && customer[0] ? customer[0].contacts : []
        const invitations = props.entity && props.customers && props.customers.length ? props.entity.invitations : []

        const contactList = invitations.length && contacts.length ? invitations.map((invitation, index) => {
            const contact = contacts.filter(contact => contact.id === invitation.client_contact_id)
            return <option
                value={contact[0].id}>{`${contact[0].first_name} ${contact[0].last_name} <${contact[0].email}>`}</option>
        }) : null

    return <ListGroup className="mt-4 col-12">
        <ListGroupItem className={listClass}>
            <ListGroupItemHeading><i
                className={`fa ${props.icon} mr-4`}/>{`${props.entity} > ${props.title}`}
            </ListGroupItemHeading>
        </ListGroupItem>
    </ListGroup>
}
