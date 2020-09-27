import axios from 'axios'
import BaseRepository from './BaseRepository'
import { consts } from '../utils/_consts'

export default class CompanyRepository extends BaseRepository {
    constructor (data = null, customers = []) {
        super()
       
        this._url = '/api/invoice'
        this.entity = 'Invoice'
    }

    async get () {
        this.errors = []
        this.error_message = ''

        try {
            const res = await axios.get(this._url)

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
