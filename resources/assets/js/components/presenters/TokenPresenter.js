import React from 'react'

export default function TokenPresenter (props) {
    const { field, entity } = props
    const user = props.users.length ? props.users.filter(user => user.id === parseInt(entity.user_id)) : []

    switch (field) {
        case 'token':
            return `${entity.token.substring(0, 10)}xxxxxxxxxx`} <br/>
                {user.length &&
                `${user[0].first_name} ${user[0].last_name}`
                }
        case 'user_id':
            return user.length ? `${user[0].first_name} ${user[0].last_name}` : ''
        default:
            return entity[field]
    }
}
