import React from 'react'

export function getDefaultTableFields () {
    return [
        'name',
        'target_url',
        'event_id',
        'format'
    ]
}

export default function SubscriptionPresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'event_id':
            return 'TODO HERE'
        default:
            return entity[field]
    }
}
