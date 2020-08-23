import React from 'react'
import { ListGroup, ListGroupItem, ListGroupItemHeading } from 'reactstrap'
import CustomerModel from '../../models/CustomerModel'
import { icons } from '../_icons'

export default function ViewContacts (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
    const customer = props.customers && props.customers.length ? props.customers.filter(customer => customer.id === parseInt(props.entity.customer_id)) : []

    let contactList = null

    if (customer.length) {
        const customerModel = new CustomerModel(customer[0])
        const invitations = props.entity.invitations

        contactList = invitations.map((invitation, index) => {
            const contact = customerModel.findContact(invitation.client_contact_id)
            return <ListGroupItem key={index} className={listClass}>
                <ListGroupItemHeading><i
                    className={`fa ${icons.contact} mr-4`}/>${(!contact.fullName.length ? customerModel.displayName : contact.fullName)}`}
                </ListGroupItemHeading>
            </ListGroupItem>
        })
    }

    return <ListGroup className="mt-2 col-12">
        {contactList}
    </ListGroup>
}
