import React from 'react'
import { ListGroupItemHeading } from 'reactstrap'
import FormatDate from '../FormatDate'
import moment from 'moment'
import TaskModel from '../../models/TaskModel'

export default function TaskTimeItem (props) {
    const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''
    const startDateString = <FormatDate date={props.taskTime.start_time} show_date={false} show_time={true}/>
    const endDateString = <FormatDate date={props.taskTime.end_time} show_date={false} show_time={true}/>
    const taskModel = new TaskModel()

    const title = moment(props.taskTime.date).format('dddd MMM D YYYY')
    const subtitle = `${startDateString} - ${endDateString}`
    const duration = taskModel.calculateDuration(props.taskTime.start_time, props.taskTime.end_time)
    const formatted_duration = taskModel.formatDuration(duration)

    return <a href={props.link}
        className={`${listClass} list-group-item list-group-item-action flex-column align-items-start mb-2`}>
        <div className="d-flex w-100 justify-content-between">
            <ListGroupItemHeading>{title}</ListGroupItemHeading>
            {formatted_duration}
        </div>
        {props.subtitle &&
        <p className="mb-1">{subtitle}</p>
        }
    </a>
}
