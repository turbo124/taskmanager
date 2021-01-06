import React from 'react'

export default function UserPresenter (props) {
    const { field, entity } = props

    switch (field) {
        case 'name': {
            return `${entity.first_name} ${entity.last_name}`
        }

        default:
            return entity[field]
    }
}
