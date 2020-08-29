import React from 'react'
import FormatMoney from '../../common/FormatMoney'
import FormatDate from '../../common/FormatDate'
import { Badge, ListGroup, ListGroupItem, UncontrolledCollapse } from 'reactstrap'
import { getEntityIcon } from '../../common/_icons'
import { translations } from '../../common/_translations'

export default function ErrorLog (props) {
    const isOpen = false
   
    const error_logs = props.error_logs.length ? props.error_logs.map((error_log, index) => {
        const errorLogModel = new ErrorLogModel(error_log)
       return (
           <ListGroupItem>
               <a id="toggler" className="btn collapsed" data-toggle="collapse" href="#collapseExample1">
                   <div className="d-flex w-100 justify-content-between">
                       <h5 className="mb-1">heading here</h5>
                       <span>icon here</span>
                   </div>
                   <p class="mb-1">subheading here</p>
               </a>
                <UncontrolledCollapse toggler="#toggler">
                    Json viewer here
               </UncontrolledCollapse>
           </ListGroupItem>
       )
    }) : null

    return <ListGroup>
        {error_logs}
    </ListGroup>
}
