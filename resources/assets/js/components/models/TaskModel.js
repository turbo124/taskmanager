import axios from 'axios'
import moment from 'moment'
import BaseModel from './BaseModel'

const TaskTimeItem = {
    date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
    start_time: moment().format('HH:MM:ss'),
    end_time: ''
}

export default class TaskModel extends BaseModel {
    constructor (data = null, customers) {
        super()
        this.customers = customers
        this.entity = 'Task'
        this._url = '/api/tasks'
        this._timerUrl = '/api/timer'
        this._time_log = []

        this._fields = {
            modal: false,
            name: '',
            assigned_to: '',
            errors: [],
            customer_id: '',
            description: '',
            contributors: '',
            custom_value1: '',
            custom_value2: '',
            custom_value3: '',
            activeTab: '1',
            custom_value4: '',
            public_notes: '',
            private_notes: '',
            timers: [],
            due_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            start_date: moment(new Date()).add(1, 'days').format('YYYY-MM-DD'),
            task_status: null,
            project_id: null,
            loading: false,
            users: [],
            selectedUsers: [],
            is_recurring: false,
            recurring_start_date: '',
            recurring_end_date: '',
            recurring_due_date: '',
            last_sent_date: '',
            next_send_date: '',
            recurring_frequency: 0
        }

        if (data !== null) {
            this._fields = { ...this.fields, ...data }
        }

        this._file_count = 0

        if (data !== null && data.files) {
            this.fileCount = data.files
        }
    }

    get fileCount () {
        return this._file_count || 0
    }

    set fileCount (files) {
        this._file_count = files ? files.length : 0
    }

    set start_date (start_date) {
        this.fields.start_date = moment(start_date, 'YYYY-MM-DD')
    }

    set due_date (due_date) {
        this.fields.due_date = moment(due_date, 'YYYY-MM-DD')
    }

    get time_log () {
        return this._time_log
    }

    set time_log (time_log) {
        this._time_log = time_log
    }

    get id () {
        return this.fields.id
    }

    get duration () {
        return this.fields.duration
    }

    get fields () {
        return this._fields
    }

    get url () {
        return this._url
    }

    addTaskTime () {
        const newArray = this.time_log.slice()
        newArray.push(TaskTimeItem)
        this.time_log = newArray
        return newArray
    }

    updateTaskTime (index, field, value) {
        const data = [...this.time_log]
        data[index][field] = value
        this.time_log = data
        return data
    }

    deleteTaskTime (index) {
        const array = [...this.time_log] // make a separate copy of the array
        array.splice(index, 1)
        this.time_log = array
        return array
    }

    calculateAmount (taskRate) {
        const total_duration = this.duration

        if (!total_duration) {
            return 0
        }

        const duration = taskRate * total_duration
        return Math.round(duration, 3)
    }

    getTotalDuration () {
        let seconds = 0
        this.fields.timers.map(timer => {
            seconds += this.calculateDuration(timer.start_time, timer.end_time, true)
        })

        return seconds
    }

    calculateDuration (currentStartTime, currentEndTime, returnAsSeconds = false) {
        const startTime = moment(currentStartTime, 'hh:mm:ss a')
        let endTime = ''

        if (currentEndTime.length) {
            endTime = moment(currentEndTime, 'hh:mm:ss a')
            const hours = (endTime.diff(startTime, 'hours'))
            const totalHours = ('0' + hours).slice(-2)
            const totalMinutes = endTime.diff(startTime, 'minutes')
            const minutes = totalMinutes % 60
            const clearMinutes = ('0' + minutes).slice(-2)

            if (returnAsSeconds === true) {
                const duration = parseFloat(hours + '.' + minutes)
                return duration * 3600
            }

            return `${totalHours}:${clearMinutes}`
        }

        return ''
    }

    buildDropdownMenu () {
        const actions = []

        if (!this.fields.is_deleted) {
            actions.push('delete')
        }

        if (!this.fields.deleted_at) {
            actions.push('archive')
            actions.push('cloneTaskToDeal')
        }

        if (!this.fields.is_deleted) {
            actions.push('newInvoice')
        }

        if (this.fields.customer_id.toString().length) {
            actions.push('pdf')
        }

        return actions
    }

    performAction () {

    }

    async update (data) {
        if (!this.fields.id) {
            return false
        }

        this.errors = []
        this.error_message = ''

        try {
            const res = await axios.put(`${this.url}/${this.fields.id}`, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    async timerAction (data) {
        try {
            this.errors = []
            this.error_message = ''
            const res = await axios.post(`${this._timerUrl}/${this.fields.id}`, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    async completeAction (data, action) {
        if (!this.fields.id) {
            return false
        }

        this.errors = []
        this.error_message = ''

        try {
            const res = await axios.post(`${this.url}/${this.fields.id}/${action}`, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    async loadPdf () {
        try {
            this.errors = []
            this.error_message = ''
            const res = await axios.post('api/preview', { entity: this.entity, entity_id: this._fields.id })

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }

            // Don't forget to return something
            return this.buildPdf(res.data)
        } catch (e) {
            alert(e)
            this.handleError(e)
            return false
        }
    }

    async save (data) {
        try {
            this.errors = []
            this.error_message = ''
            const res = await axios.post(this.url, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    formatDuration (duration, showSeconds = true) {
        const time = duration.toString().split('.')[0]

        if (showSeconds) {
            return time
        }

        const parts = time.split(':')
        return `${parts[0]}:${parts[1]}`
    }
}
