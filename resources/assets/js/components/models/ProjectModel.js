import axios from 'axios'
import BaseModel from './BaseModel'
import TaskModel from './TaskModel'

export default class ProjectModel extends BaseModel {
    constructor (data = null) {
        super()

        this._url = '/api/projects'
        this.entity = 'Project'

        this._fields = {
            id: null,
            modal: false,
            name: '',
            description: '',
            customer_id: '',
            private_notes: '',
            public_notes: '',
            due_date: '',
            assigned_to: '',
            budgeted_hours: '',
            task_rate: '',
            count: 2,
            errors: [],
            customers: [],
            tasks: []
        }

        if (data !== null) {
            this._fields = { ...this.fields, ...data }
        }
    }

    get fields () {
        return this._fields
    }

    get url () {
        return this._url
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

    taskDurationForProject () {
        console.log('tasks', this.fields.tasks)
        let total = 0
        this.fields.tasks.map(task => {
            if (task.is_active && task.project_id === parseInt(this.fields.id)) {
                const taskModel = new TaskModel(task)
                total += taskModel.getTotalDuration()
            }
        })

        return total
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

    async save (data) {
        if (this.fields.id) {
            return this.update(data)
        }

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
}
