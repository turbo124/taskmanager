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
        this._status_url = 'api/taskStatus'
        this._timerUrl = '/api/timer'
        this._time_log = []

        this._fields = {
            modal: false,
            title: '',
            rating: '',
            source_type: 0,
            errors: [],
            valued_at: '',
            customer_id: '',
            content: '',
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

    set time_log (time_log) {
        this._time_log = time_log
    }

    get time_log () {
        return this._time_log
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

    calculateAmount (taskRate, duration) {
        return (taskRate * duration).toFixed(3)
    }

    calculateDuration (currentStartTime, currentEndTime) {
        const startTime = moment(currentStartTime, 'hh:mm:ss a')
        let endTime = ''

        if (currentEndTime.length) {
            endTime = moment(currentEndTime, 'hh:mm:ss a')
            let totalHours = (endTime.diff(startTime, 'hours'))
            totalHours = ('0' + totalHours).slice(-2)
            const totalMinutes = endTime.diff(startTime, 'minutes')
            let clearMinutes = totalMinutes % 60
            clearMinutes = ('0' + clearMinutes).slice(-2)

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

    async getStatuses () {
        this.errors = []
        this.error_message = ''

        try {
            const res = await axios.get(this._status_url)

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
