import React from 'react'

export const icons = {
    view: 'fa-eye',
    refresh: 'fa-gear',
    checkbox: 'fa-check-square',
    checkbox_o: 'fa-check-square-o',
    table: 'fa-table',
    cog: 'fa-cog',
    columns: 'fa-columns',
    tick: 'fa-check',
    download: 'fa-download',
    email: 'fa-envelope-square',
    mark_sent: 'fa-share-square',
    customer: 'fa-user-circle-o',
    contact: 'fa-users',
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
    angle_down: 'fa-angle-down',
    angle_up: 'fa-angle-up',
    right: 'fa-chevron-right',
    left: 'fa-chevron-left',
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
    book: 'fa-book',
    user: 'fa-user',
    project: 'fa-briefcase',
    task: 'fa-clock-o',
    expense: 'fa-bar-chart-o',
    company: 'fa-building',
    product: 'fa-barcode',
    order: 'fa-shopping-cart',
    calendar: 'fa-calendar',
    percent: 'fa-percent',
    spinner: 'fa-circle-o-notch fa-spin',
    token: 'fa-key',
    pencil: 'fa-pencil',
    google: 'fa-google',
    shield: 'fa-shield',
    globe: 'fa-globe',
    double_right: 'fa-angle-double-right',
    cloud: 'fa-cloud',
    cloud_download: 'fa-cloud-download',
    header: 'fa-header',
    group: 'fa-group',
    pound_sign: 'fa-gbp',
    payment_terms: 'fa-handshake-o',
    pdf: 'fa-file-pdf-o',
    archive_file: 'a-file-archive-o',
    text_file: 'fa-file-text',
    word_file: 'fa-file-word-o',
    excel_file: 'fa-file-excel-o',
    powerpoint_file: 'fa-file-powerpoint-o',
    image_file: 'fa-file-image-o',
    desktop: 'fa-desktop',
    info: 'fa-info-circle'
}

export function getEntityIcon (entity) {
    switch (entity) {
        case 'User':
            return icons.user
        case 'Customer':
            return icons.group
        case 'Product':
            return icons.product
        case 'Payment':
            return icons.credit_card
        case 'Company':
            return icons.company
        case 'Invoice':
        case 'Credit':
        case 'Quote':
        case 'Order':
            return icons.document
    }
}

export function getFileTypeIcon (type) {
    switch (type) {
        case 'pdf':
            return icons.document
        case 'psd':
            return icons.pdf
        case 'txt':
            return icons.text_file
        case 'doc':
        case 'docx':
            return icons.word_file
        case 'xls':
        case 'xlsx':
            return icons.excel_file
        case 'ppt':
        case 'pptt':
            return icons.powerpoint_file
        case 'png':
        case 'gif':
        case 'jpg':
        case 'jpeg':
            return icons.image_file
        default:
            return null
    }
}

export function getSettingsIcon (section) {
    switch (section) {
        case 'designs':
            return icons.pencil
        case 'integrations':
            return icons.google
        case 'modules':
            return icons.shield
        case 'localisation':
            return icons.globe
        case 'workflow-settings':
            return icons.double_right
        case 'device-settings':
            return icons.desktop
        case 'portal-settings':
            return icons.cloud
        case 'field-settings':
            return icons.header
        case 'tax-rates':
            return icons.percent
        case 'group-settings':
            return icons.group
        case 'number-settings':
            return icons.list
        case 'product-settings':
            return icons.product
        case 'invoice-settings':
            return icons.pound_sign
        case 'gateway-settings':
            return icons.credit_card
        case 'email-settings':
            return icons.envelope
        case 'template-settings':
            return icons.document
        case 'payment_terms':
            return icons.payment_terms
        case 'accounts':
            return icons.building
    }
}
