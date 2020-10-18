import TaskRepository from '../repositories/TaskRepository'
import ExpenseRepository from '../repositories/ExpenseRepository'
import ProjectRepository from '../repositories/ProjectRepository'
import { formatDate } from '../common/FormatDate'
import { consts } from '../utils/_consts'
import TaskModel from '../models/TaskModel'
import ExpenseModel from '../models/ExpenseModel'

export default class InvoiceReducer {
    constructor (entity_id, entity_type) {
        this.entity_id = entity_id
        this.entity_type = entity_type

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = user_account[0].account.settings
    }

    build (entity_type, data) {
        switch (entity_type) {
            case 'expense':
                return this.buildExpense(data)
            case 'task':
                return this.buildTask(data)
            case 'project':
                return this.buildProject(data)
        }
    }

    buildExpense (response) {
        const expenseModel = new ExpenseModel(response)

        const line_items = []
        const row = {}

        const line_item = {
            expense_id: parseInt(this.entity_id),
            unit_price: expenseModel.convertedAmount,
            quantity: this.settings.has_minimum_quantity === true ? 1 : null,
            type_id: consts.line_item_expense,
            notes: response.category && Object.keys(response.category).length ? response.category.name : '',
            description: response.category && Object.keys(response.category).length ? response.category.name : '',
        }

        line_items.push(line_item)

        row.customer_id = response.customer_id
        row.line_items = line_items

        console.log('row', row)

        return row
    }

    buildProject () {
        const projectModel = new ProjectModel(response)

        const line_items = []
        const row = {}

        const line_item = {
            project_id: parseInt(this.entity_id),
            unit_price: response.task_rate,
            quantity: Math.round(response.budgeted_hours, 3),
            type_id: consts.line_item_project,
            notes: response.description || '',
            description: response.description || '',
        }

        line_items.push(line_item)

        row.customer_id = response.customer_id
        row.line_items = line_items

        console.log('row', row)

        return row
    }

    buildTask (response) {
        let notes = response.description + '\n'

        if (response.timers) {
            response.timers.filter(time => {
                return time.date.length && time.end_date.length
            }).map(time => {
                const start = formatDate(`${time.date} ${time.start_time}`, true)
                const end = formatDate(`${time.end_date} ${time.end_time}`, true)
                notes += `\n### ${start} - ${end}`
            })
        }

        const taskModel = new TaskModel(response)
        const line_items = []
        const row = {}

        const line_item = {
            task_id: parseInt(this.entity_id),
            unit_price: taskModel.calculateAmount(response.task_rate),
            quantity: Math.round(response.duration, 3),
            type_id: consts.line_item_task,
            notes: notes
        }

        line_items.push(line_item)

        row.customer_id = response.customer_id
        row.line_items = line_items

        console.log('row', row)

        return row
    }
}
