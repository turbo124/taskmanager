import React from 'react'
import { gb_translations } from '../translations/translations_gb'

export const icons = {
    refresh: 'fa-gear',
    checkbox: 'fa-check-square',
    table: 'fa-table',
    columns: 'fa-columns',
    download: 'fa-download',
    email: 'fa-envelope-square',
    mark_sent: 'fa-share-square',
    customer: 'fa-user-circle-o',
    approve: 'fa-check-square',
    clone: 'fa-copy',
    delete: 'fa-trash',
    mark_paid: 'fa-credit-card',
    credit_card: 'fa-credit-card-alt',
    cancel: 'fa-power-off',
    reverse: 'fa-backward',
    archive: 'fa-archive',
    document: 'fa-file',
    products: 'fa-barcode',
    add: 'fa-plus',
    ellipsis: 'fa-ellipsis-h',
    down: 'fa-chevron-down',
    right: 'fa-chevron-right',
    restore: 'fa-window-restore',
    clear: 'fa-times',
    edit: 'fa-edit',
    refund: 'fa-credit-card',
    phone: 'fa-phone',
    link: 'fa-link',
    building: 'fa-building',
    list: 'fa-list-ol',
    map_marker: 'fa-map-marker',
    envelope: 'fa-envelope',
    user: 'fa-user',
    project: 'fa-briefcase',
    task: 'fa-clock-o',
    expense: 'fa-bar-chart-o',
    company: 'fa-building',
    product: 'fa-barcode',
    order: 'fa-shopping-cart',
    calendar: 'fa-calendar',
    percent: 'fa-percent',
    spinner: 'fa-circle-o-notch fa-spin'
}

export const default_language = 'gb'
export let translations = null
switch (default_language) {
    case 'gb':
        translations = gb_translations
        break
}
