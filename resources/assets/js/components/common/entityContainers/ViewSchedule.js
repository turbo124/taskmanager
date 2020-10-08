import React from 'react'
import { ListGroup, ListGroupItem, ListGroupItemHeading } from 'reactstrap'
import CustomerModel from '../../models/CustomerModel'
import { icons } from '../../utils/_icons'
import { translations } from '../../utils/_translations'
import FormatDate from '../FormatDate'

export default function ViewSchedule (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
   
        contactList = this.props.recurringDates.map((schedule, index) => {

            return  <ListGroupItem className="d-flex justify-content-between align-items-center"><FormatDate date={schedule.send_date} /> <FormatDate date={schedule.due_date} /> </ListGroupItem>
        })

    return <React.Fragment>
        <ListGroup horizontal>
        <ListGroupItem tag="a" href="#">{translations.send_date}</ListGroupItem>
        <ListGroupItem tag="a" href="#">{translations.due_date} </ListGroupItem>
      </ListGroup>

        <ListGroup className="mt-2 col-12">
            {contactList}
        </ListGroup>
    </React.Fragment>
    
}
