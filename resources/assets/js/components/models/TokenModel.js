import axios from 'axios'
import BaseModel from './BaseModel'

export default class TokenModel extends BaseModel {
    constructor (data = null) {
        super()

        this._url = '/api/tokens'
        this.entity = 'Token'

        this._fields = {
            modal: false,
            token: '',
            name: '',
            loading: false,
            errors: []
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

        actions.push('copy')

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

    copyToken () {
        const mark = document.createElement('textarea')
        mark.setAttribute('readonly', 'readonly')
        mark.value = this.fields.token
        mark.style.position = 'fixed'
        mark.style.top = 0
        mark.style.clip = 'rect(0, 0, 0, 0)'
        document.body.appendChild(mark)
        mark.select()
        document.execCommand('copy')
        document.body.removeChild(mark)
        return true
    }

    async completeAction (data, action) {
        if (action === 'copy') {
            return this.copyToken()
        }

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
