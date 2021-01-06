import React from 'react'

export default function UserPresenter (props) {
    const { field, entity } = props

    switch (field) {
        default:
            return entity[field]
    }
}
