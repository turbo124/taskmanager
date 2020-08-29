import axios from 'axios'
import BaseModel from './BaseModel'

export default class SystemLogModel extends BaseModel {
    constructor (data = null) {
        super()

        this._fields = {
            id: null,
            website: '',
            industry_id: '',
            modal: false,
            first_name: '',
            last_name: '',
        }

        if (data !== null) {
            this._fields = { ...this.fields, ...data }
        }
    }

    get fields () {
        return this._fields
    }

    get category {
        switch (categoryId) {
            case CATEGORY_PAYMENT:
                return 'payment';
            case CATEGORY_EMAIL:
                return 'email';
        }

        return '';
    }

    get event {
        switch (eventId) {
            case 10:
                return 'payment_reconciliation_failure';
            case 11:
                return 'payment_reconciliation_success';
            case 21:
                return 'gateway_success';
            case 22:
                return 'gateway_failure';
            case 23:
                return 'gateway_error';
            case 30:
                return 'email_send';
            case 31:
                return 'email_retry_queue';
        }

        return '';
    }

    get entity {
        switch (entity) {
            case 300:
                return 'PayPal';
            case 301:
                return 'Stripe';
            case 302:
                return 'ledger';
            case 303:
                return 'failure';
            case 304:
                return 'Checkout.com';
            case 305:
                return 'Authorize.net';
            case 400:
                return 'quota_exceeded';
            case 401:
                return 'upstream_failure';
        }

    return '';
  }
}
