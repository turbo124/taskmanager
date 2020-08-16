import { consts } from '../common/_consts'
import { translations } from '../common/_translations'
import FormatDate from '../common/FormatDate'

export const LineItem = {
    unit_discount: 0,
    unit_tax: 0,
    quantity: 0,
    unit_price: 0,
    product_id: 0,
    custom_value1: '',
    custom_value2: '',
    custom_value3: '',
    custom_value4: ''
}

export default class BaseModel {
    constructor () {
        this.errors = []
        this.error_message = ''

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        this.user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = this.user_account[0].account.settings
        this.custom_fields = this.user_account[0].account.custom_fields
    }

    handleError (error) {
        if (error.response.data.message) {
            this.error_message = error.response.data.message
        }

        if (error.response.data.errors) {
            this.errors = error.response.data.errors
        }
    }

    isModuleEnabled (module) {
        return JSON.parse(localStorage.getItem('modules'))[module]
    }

    getCustomFieldLabel (entity, field) {
        const custom_fields = this.custom_fields[entity]
        const custom_field = custom_fields.filter(current_field => current_field.name === field)

        if (custom_field.length && custom_field[0].label.length && custom_field[0].type.length) {
            return custom_field[0].label
        }

        return ''
    }

    getCustomFieldType (field, entity) {
        const custom_fields = this.custom_fields[entity]
        const custom_field = custom_fields.filter(current_field => current_field.name === field)

        if (!custom_field.length || !custom_field[0].label.length || !custom_field[0].type.length) {
            return consts.text
        }

        return custom_field[0].type
    }

    formatCustomValue (entity, field, value) {
        switch (this.getCustomFieldType(field, entity)) {
            case consts.switch:
                return value === 'yes' || value === 'true' || value === true ? translations.yes : translations.no
            case consts.date:
                return <FormatDate date={value}/>
            default:
                return value
        }
    }
}
